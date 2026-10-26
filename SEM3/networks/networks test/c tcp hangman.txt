#include <sys/types.h>
#include <stdio.h>
#include <ctype.h>
#include <sys/socket.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <sys/wait.h>
#include <time.h>

int
validate_number (char *str)
{
  while (*str)
    {
      if (!isdigit (*str))
	{			//if the character is not a number, return
	  return 0;
	}
      str++;			//point to next character
    }
  return 1;
}


int
main (int argc, char **argv)
{
  //Validating cmd arguments

  if (argc < 2)
    {
      printf ("Give me a port.\n");
      return 1;
    }

  if (!validate_number (argv[1]))
    {
      printf ("Port must be integer.\n");
      return 2;
    }

  //Initializing socket

  int server_fd;

  struct sockaddr_in server_addr;

  int addrlen = sizeof (server_addr);

  printf ("Creating socket.\n");

  if ((server_fd = socket (AF_INET, SOCK_STREAM, 0)) == 0)
    {
      perror ("Socket creation failed\n");
      exit (EXIT_FAILURE);
    }

  server_addr.sin_family = AF_INET;
  server_addr.sin_addr.s_addr = INADDR_ANY;
  //Seting socket to listen on all available ip addresses
  //If you want the server to listen on a single ip address use the line below insead
  //inet_aton("1.2.3.4", &server_addr.sin_addr)

  //Setting bind port
  server_addr.sin_port = ntohs (atoi (argv[1]));

  printf ("Binding socket to port %s\n", argv[1]);

  //Binding server

  if (bind (server_fd, (struct sockaddr *) &server_addr, sizeof (server_addr))
      < 0)
    {
      perror ("Bind error.\n");
      exit (EXIT_FAILURE);
    }

  printf ("Listening.\n");

  //Listening for incoming connections

  if (listen (server_fd, 3) < 0)
    {
      perror ("Listen error.\n");
      exit (EXIT_FAILURE);
    }

  while (1)
    {
      struct sockaddr_in client_addr;

      printf ("Server waitng for clients.\n");

      //Client has just connected

      int client = accept (server_fd, (struct sockaddr *) &client_addr,
			   (socklen_t *) & addrlen);

      //Creating child proccess in order to "serve" the curent client

      if (!fork ())
	{

	  //Checking if the client connection works

	  if (!client)
	    {
	      perror ("Accept error.\n");
	      exit (EXIT_FAILURE);
	    }

          srand(time(NULL));

	  int ch = (rand() % 3) + 5; 

	  printf ("New client connected: %s:%d.\n",
		  inet_ntoa (client_addr.sin_addr), client_addr.sin_port);

	  char *words[] = {"dimethyltryptamine", "failure", "methanol", "interdisciplinarity", "depresion", "phenylpropanolamine"};
          
	  int index = rand() % 6;
	  
	  //printf("Index=%d word=%s\n", index, words[index]);

	  char *str = malloc(strlen(words[index]) * sizeof(char));
	  
	  memcpy(str, words[index], strlen(words[index]));

	  int l = strlen (str);
	  
	  int sl = ntohl(l);

	  send (client, &sl, sizeof (int), MSG_WAITALL);
	  
	  printf("Client %s has to gues word: %s in %d shots\n", inet_ntoa (client_addr.sin_addr), str, ch);	  

	  printf("Sent to client %s word lemngth: %d\n", inet_ntoa (client_addr.sin_addr), l);

	  int finish = 0;

	  int *arr = malloc (strlen (str) * sizeof (int));
	  
	  int rl = strlen (str); //remaining letters

	  while (!finish)
	    {
	    
	      char let;

	      recv (client, &let, sizeof(char), MSG_WAITALL);
	      
	      printf("%s: %c\n", inet_ntoa (client_addr.sin_addr), let);

	      int i, ok;

	      ok = 0;

	      l = 0;

	      //printf("Looking for letter: %c\n", let);

	      for (i = 0; i < strlen (str); i++)
		{
		  
		  if (str[i] == let)
		    {
		      ok = 1;
		      arr[l] = i;
		      l++;
		      rl--;		      
		      //printf("Found letter %c on position %d\n", let, i);
		      str[i]='*';
		    }
		}

	      if (!rl) //win
		{
		  l = 2;
		  
		  int sl = ntohl(l);
		  
		  send (client, &sl, sizeof (int), MSG_WAITALL);
		  
		  printf("Victory ent to client %s\n", inet_ntoa (client_addr.sin_addr));
		  
		  close (client);
		  
		  finish = 1;
		}
	      else
		{
		  if (ok) //nimerit litera
		    {
		      int sl = htonl(l);
		      
		      int k = htonl(1);
		      
		      send (client, &k, sizeof (int), MSG_WAITALL);
		      
		      send (client, &sl, sizeof (int), MSG_WAITALL);
		      
		      printf("Sent occureence to client %s: %d\n", inet_ntoa (client_addr.sin_addr), l);
		      
		      for (i = 0; i < l; i++)
			{
			  int si = htonl(arr[i]);
			  
			  send (client, &si, sizeof (int), MSG_WAITALL);
			  
			  printf("Sent index to client %s: %d\n", inet_ntoa (client_addr.sin_addr), arr[i]);
			}
		    }
		  else
		    {
		      ch--;
		      
		      printf("Client %s guesed wrong %c; %d changes remaineing\n", inet_ntoa (client_addr.sin_addr), let, ch);

		      if (!ch)
			{
			  l = 3; // lose
			  finish = 1;
			}
		      else
			{
			  l = 0; // wrong letter
			}
			
		      int sl = ntohl(l);	
			
		      send (client, &sl, sizeof (int), MSG_WAITALL);

		      printf("Wl or lost: Sent to client %s: %d\n", inet_ntoa (client_addr.sin_addr), l);
		    }
		}

	    }
	  close (client);

	  free (arr);
	}
    }

  wait (0);

  return 0;
}
