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

  int fdmax, client_count, server_fd;

  struct sockaddr_in server_addr;

  int addrlen = sizeof (server_addr);

  printf ("Creating socket.\n");

  char buf[512];

  fd_set master;

  fd_set read_fds;

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
  server_addr.sin_port = htons (atoi (argv[1]));

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

  FD_ZERO (&master);		// clear the master and temp sets

  FD_ZERO (&read_fds);

  FD_SET (server_fd, &master);

  fdmax = server_fd;

  client_count = 0;

  while (1)
    {
      read_fds = master;

      if (select (fdmax + 1, &read_fds, NULL, NULL, NULL) == -1)
	{
	  perror ("Select error.\n");
	  exit (EXIT_FAILURE);
	}

      int i;

      for (i = 0; i <= fdmax; i++)
	{
	  if (FD_ISSET (i, &read_fds))
	    {
	      //int crt = i;

	      if (i == server_fd)
		{
		  struct sockaddr_in client_addr;

		  int client =
		    accept (server_fd, (struct sockaddr *) &client_addr,
			    (socklen_t *) & addrlen);

		  if (client < 0)
		    {
		      perror ("Accept error.\n");
		      exit (EXIT_FAILURE);
		    }

		  printf ("New client connected: %s:%d.\n",
			  inet_ntoa (client_addr.sin_addr),
			  client_addr.sin_port);

		  FD_SET (client, &master);

		  if (client > fdmax)
		    {
		      fdmax = client;
		    }

		  client_count++;

		  sprintf (buf, "Wellcome to the server!! You are client %d",
			   client_count);

		  send (client, buf, strlen (buf) + 1, 0);

		}
	      else //if a clinet sent a meesage
		{
		  int rcv = recv (i, buf, sizeof (buf), 0);

		  if (rcv == 0)
		    {
		      printf ("Client %d hung up.\n", i - 3);
		      close (i);
		      FD_CLR (i, &master);
		      continue;
		    }
		  if (rcv < 0)
		    {
		      perror ("Recv error.");
		      close (i);
		      FD_CLR (i, &master);
		      continue;
		    }
		  else
		    {
		      char tmpbuf[1024];

		      buf[rcv] = 0;
		      int snd =
			sprintf (tmpbuf, "Client %d saies: %s", i - 3, buf);

		      int j;

		      for (j = 0; j <= fdmax; j++)
			{

			  if (FD_ISSET (j, &master))

			    if (j != server_fd && j != i)

			      if (send (j, tmpbuf, snd, 0) == -1)

				perror ("Send error.");

			}
		    }
		}

	    }
	}

    }

  return 0;
}
