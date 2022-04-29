# PHP-WEBPAGE
## Pouget Elie


# Création d'une page web avec des objets PHP

## Description
Nous avons créer un fichier php WebPage.php contenant la class WebPage qui grace 
à des objets nous permet de créer une page plus facilement et rapidement

## Structure du projet
Dossier public : contient les fichier php créer est utiliser en page web ils 
                utilisent directment nos objets

Dossier src: Contient notre classe WebPage

Dossier tests: Contient les fichier php utiliser pour tester le bon fonctionnement
              de la classe WebPage

Fichier autoload.php : Script de configuration de l'auto chargement

Fichier phpunit.xml : Configuration de PHPUnit pour utiliser les tests

## Mise en route

En se placeant à la racine du projet php-webpage utiliser :
php -d display_errors -S localhost:8000 -t public/

Maintenant dans un navigateur utiliser le lien: 
http://localhost:8000/NomDuFichierPhpDansLeDossierPublic.php

##Tests

Installer phpUnit https://phpunit.de/
Placez vous à la racine et lancez la commande : $phpunit
