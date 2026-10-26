#!/bin/bash
touch filesizes.temp
for f in $@; do
	if [ -f $f ]; then
	du -b $f >> filesizes.temp
	fi
done
sort -n -r filesizes.temp
rm filesizes.temp
