#!/bin/bash
echo $1 is the first argument
echo $# is the nr of arguments
echo $@ is the list of arguments

for arg in $@ ; do
	echo $arg
	if test -f $arg
	then
		echo $arg is a file
	elif [ -d $arg  ] ; then
		echo $arg is a directory
	elif [ $arg -eq $arg  ] ; then
	       #	echo $arg | grep -E -q "^-?[0-9]+$" ; then
		echo $arg is an integer
	else 
		echo $arg is a random string.
	fi

done
