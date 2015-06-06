#!/bin/bash

PROJECT_PATH=$1
PROJECT_NAME=$2
SINCE=$3

if ! test "$PROJECT_PATH"; then
    
    echo "Chemin vers le répertoire git manque"
    exit;
fi

if ! test "$SINCE"; then
	SINCE="1990-01-01"
fi

cd $PROJECT_PATH

git log --branches --stat --date=iso --since="$SINCE" | tr -d ";" | tr -d "\t" | tr "\n" "\t" | sed 's/$/\n/' | sed -r 's/commit ([0-9a-z]+\t)/\n\1/g' | sed 's/Author: /;/' | sed 's/Date: /;/' | sed 's/\t\t$//g' | sed 's/\t\t/;/g' | sed 's/\t;/;/g' | sed -r 's/[ ]+/ /g' | sed "s/\t/\\\n/g" | sed 's/; /;/g' | sed 's/\\n$//' | grep -v "^$" | sed -r 's/^([0-9a-z]+)\\nMerge: [0-9a-z]+ [0-9a-z]+/\1/' | sed "s/^/$PROJECT_NAME;/" | sort -t ";" -k 2,2 -u
