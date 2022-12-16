
# Projet tuteuré de LPWMCE : mangAtoZ

Une brève description du projet tuteuré du premier
semestre de Licence professionnelle de développement
web et mobile pour le commerce electronique

Projet réalisé par : BECKER Arthur, DESTREMONT Adrien, FRITSCH Matthieu

## Description du projet

Le but de ce projet est de réaliser un site e-commerce de manga et
de générer une PWA de ce site.

## Consigne d'installation

Après avoir recupéré le projet réaliser ces commandes :

```script
composer install
```
----------

Puis veuillez créer une database qui réceptionnera les données de l'API

Une fois cela réalisé, executer cette commande à la racine du projet

Cette commande permettra de créer les tables dans votre database
```script
php bin/doctrine.php orm:schema-tool:create
```
----------------------------------------------------------
Cette commande executera le script qui permet de remplir la base de données
Ajuster la boucle si vous voulez plus ou moins de données

Dans `src/Script/script.php` à la ligne 7

Changer cette valeur:

`for($i = 0; $i<1000; $i = $i+10)`
pour une installation rapide avec un jeu de données conséquent remplacer `$i<1000`
par `$i<50`.

Puis executer cette commande

```script
php src/App/Script/script.php
```
---------------------------------
Une fois cette commande terminée, pour lancer le site
veuillez vous placer
dans le dossier `public` et exécuter cette commande.
```script
php -S localhost:8000
```
---------------------------------

Le script permettant de supprimer le panier à une fréquence défini se situe dans
`src/App/Script/scriptcron.php`


-------
Compte admin:

username: admin@admin.fr

password: admin

Compte client:

username: client@client.fr

password: client

-------

## Config SMTP

smtp_server=smtp.gmail.com

smtp_port=587

default_domain=gmail.com

error_logfile=error.log

auth_username=arthurbecker57420@gmail.com

auth_password=igalbvwqgomecebb

pop3_server=

pop3_username=

pop3_password=

force_sender=arthurbecker57420@gmail.com

force_recipient=

hostname=