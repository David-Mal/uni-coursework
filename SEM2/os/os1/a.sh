#!/bin/bash

if test $# -lt 1
then
	echo "script needs one argument"
	exit 1
fi

DIR=$1

if ! test -d $DIR ; then
       echo "First parameter must be a directory"
       exit 1
fi

for f in $DIR/*.c
do
	echo $f
	n_lines=`grep -E -c "[^  ]" $f`
	echo "$f has $n_lines lines of C code"
        sum=`expr $sum + $n_lines`
done

echo "in total we have $sum lines"
echo "Today is `date` "


