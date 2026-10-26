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
  server_addr.sin_port = htons(atoi (argv[1]));

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

	  printf ("New client connected: %s:%d.\n", inet_ntoa (client_addr.sin_addr), client_addr.sin_port);
	  
	  //sin_addr 
	  
	  int a, b;

	  int s;

	  //Receiving data from the client

	  s = recv (client, &a, sizeof (int), MSG_WAITALL);

	  //Checking if the data from the client was recieved

	  if (!s)
	    {
	      printf ("Client %s disconnected.\n",
		      inet_ntoa (client_addr.sin_addr));
	      continue;
	    }

	  printf ("Recived %d bytes from client %s. \n", s,
		  inet_ntoa (client_addr.sin_addr));

	  //Converting data from networck arhitecture to local arhitecture

	  a = ntohs (a);

	  printf ("Recived from client %s - %d.\n",
		  inet_ntoa (client_addr.sin_addr), a);

	  //Same as before

	  s = recv (client, &b, sizeof (int), MSG_WAITALL);

	  if (!s)
	    {
	      printf ("Client %s disconnected.\n",
		      inet_ntoa (client_addr.sin_addr));
	      continue;
	    }

	  printf ("Recived %d bytes from client %s.\n", s,
		  inet_ntoa (client_addr.sin_addr));

	  b = ntohs (b);

	  printf ("Recived from %s - %d.\n", inet_ntoa (client_addr.sin_addr),
		  b);

	  int sum;

	  sum = a + b;

	  //Converting data from local arhitecture to network arhitecture

	  sum = htons (sum);

	  printf ("Sending to %s - %d.\n", inet_ntoa (client_addr.sin_addr),
		  ntohs (sum));

	  //Sending data to client

	  s = send (client, &sum, sizeof (int), MSG_WAITALL);

	  printf ("Sent %d bytes to client %s.\n", s,
		  inet_ntoa (client_addr.sin_addr));

	  //Closing connection with the curent clinet

	  close (client);

	}
    }

  wait (0);

  return 0;
}
