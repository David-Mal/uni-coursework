#!/bin/bash

F=""
while test -z $F || ! test -f $F || ! test -r $F ; do
	read -p "Give a readable filename: " F
done
