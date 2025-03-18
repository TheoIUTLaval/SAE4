
Pour l'installation de l'application, vous avez le choix entre 2 options concernant les identifiants de la base de données

- option 1 : vous décidez du nom de l'utilisateur Mysql, de celui de votre base de donnée et du mot de passe de l'utilisateur MySQL

- option 2 : vous conservez les identifiants déjà existants

1. Option 1  
Si vous souhaitez définir vos propres identifiants pour la base de données, récupérez les fichiers  
`ids.txt` et `setIds.sh`  
`setIds.sh` va remplacer tous les identifiants présents dans tous les fichiers php

	- Créez votre base de donnée en lui donnant le nom que vous voulez

	- Assurez vous que le fichier `setIds.sh` possède bien un droit en exécution

	- Placez vos identifiants dans le fichier `ids.txt` en remplaçant les valeurs des 4 premières lignes qui peuvent être

utilisateur    = etu  
serveur        = localhost  
motdepasse     = Achanger!  
basededonnees  = sae  
basedir        = SAE  

(basedir = SAE signifie que l'application se trouve dans `/var/www/html/SAE`)

	- Placez ensuite les fichiers `ids.txt` et `setIds.sh` dans le dossier principal de l'application

	- Exécutez setIds.sh (pour remplacer tous les identifiants) avec
```bash
$ ./setIds.sh
```

- La base de données une fois créée peut être alimentée avec le fichier `gestionDB.sql` sous phpmyadmin ou depuis le terminal avec
```bash
$ (echo "use sae;"; cat gestionDB.sql) | mysql -u etu -p
```


2. Option 2  
Enfin, si vous souhaitez installer l'application Web sous XAMPP, sans rien changer aux identifiants déjà en place, suivez les instructions données  ici : 

<https://docs.google.com/document/d/1TWRei5tZhCpDdAvufN2YUJNev7qF2n1zJmF3wDluFyY/edit?tab=t.0>



3. Voici les comptes déjà créés dans la base de données


	1) Compte Utilisateur :
		- mail : jamesbrown52@gmail.com
		- mot de passe : jamespass567

	2) Compte Producteur :
		- mail : davidwilson4@gmail.com
		- mot de passe : strongpass123

	3) Compte Administrateur :
		- mail : elladavis4@gmail.com
		- mot de passe : ellapass123


4. Problème de proxy avec curl

Il se règle en ajoutant
```php
curl_setopt($ch, CURLOPT_PROXY, 'proxy.univ-lemans.fr');
curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
```
juste après
```php
$ch = curl_init($url);
```
dans `index.php`