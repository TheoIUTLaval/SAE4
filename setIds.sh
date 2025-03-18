#!/bin/bash

# --- Auteur BE ---
# Remplacement des identifiants dans tous les fichiers php
# par ceux fournis dans le fichier ids.txt

{
    read utilisateur 
    read serveur 
    read motdepasse 
    read basededonnees 
    read basedir
} < ids.txt

utilisateur=${utilisateur##* }
serveur=${serveur##* }
motdepasse=${motdepasse##* }
basededonnees=${basededonnees##* }
basedir=${basedir##* }

echo "utilisateur               -> $utilisateur"
echo "serveur                   -> $serveur"
echo "mot de passe MySQL        -> $motdepasse"
echo "nom de la base de données -> $basededonnees"
echo "répertoire racine         -> $basedir"
echo

function processDirectory() {
	(
	# on se déplace dans ce répertoire
	cd "$1"
	if (($2 > 0)); then
		printf "%.0s\t" $(seq 1 "$2")
	fi
	printf "=== `pwd` ===\n"

	# désactivation des erreurs en cas de non correspondance du motif
	shopt -s nullglob

	# obtention dans un tableau de la liste des fichiers php
	files=(*.php)

	if ((${#files[@]})); then # si le tableau n'est pas vide
		for i in ${files[@]}; do # traitement de chacun des fichiers php
			#echo "1ère phase"
			grep '\$utilisateur = ' $i > /dev/null
			if [ $? == 0 ] ; then
				vi "$i" << ! 2> /dev/null
:1,$ s/\(\$utilisateur = "\).*\(".*\)/\1$utilisateur\2/
:1,$ s/\(\$serveur = "\).*\(".*\)/\1$serveur\2/
:1,$ s/\(\$motdepasse = "\).*\(".*\)/\1$motdepasse\2/
:1,$ s/\(\$basededonnees = "\).*\(".*\)/\1$basededonnees\2/
:x
!
				if (($2 > 0)); then
					printf "%.0s\t" $(seq 1 "$2")
				fi
				printf "$i \e[31m(modifié)\e[0m\n"
			fi

			#echo "2ème phase"
			grep '\$user = ' $i > /dev/null
			if [ $? == 0 ] ; then
				vi "$i" << ! 2> /dev/null
:1,$ s/\(\$user = '\).*\('.*\)/\1$utilisateur\2/
:1,$ s/\(\$host = '\).*\('.*\)/\1$serveur\2/
:1,$ s/\(\$password = '\).*\('.*\)/\1$motdepasse\2/
:1,$ s/\(\$dbname = '\).*\('.*\)/\1$basededonnees\2/
:x
!
				if (($2 > 0)); then
					printf "%.0s\t" $(seq 1 "$2")
				fi
				printf "$i \e[31m(modifié)\e[0m\n"
			fi

			#echo "3ème phase"
			grep '/~' "$i" > /dev/null
			if [ $? == 0 ] ; then
				vi $i << ! 2> /dev/null
:1,$ s/\/\~[^/]*/\/$basedir/
:x
!
				if (($2 > 0)); then
					printf "%.0s\t" $(seq 1 "$2")
				fi
				printf "$i \e[31m(repertoire racine modifié)\e[0m\n"
			fi
		done
	fi

	OLDIFS=$IFS
	IFS=$'\n'
	for i in `ls`; do
		if [ -d "$i" ]; then # l'entrée est-elle un répertoire ?
			processDirectory "$i" $(($2 + 1))
		fi
	done
	IFS=$OLDIFS
	)
}

processDirectory . 0

