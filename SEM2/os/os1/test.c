#include <malloc.h>



int main(int argc, char **argv)

{

	    char **c;



	        c = (char**)malloc(3 * sizeof(char*));

		    c[0] = "hello";

		        c[1] = "world";

			    c[2] = "!";

			        return 0;

}
