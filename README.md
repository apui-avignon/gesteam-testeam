# GesTeam - TesTeam
## Présentation

GesTeam - TesTeam est une application qui permet aux étudiants d’apprendre en autonomie à travailler en collaboration.
    
Le champ d’application de GesTeam - TesTeam est l’apprentissage par les pairs lors d’activités collaboratives. Il s’agit pour les membres d’un groupe de maximiser leur intelligence collective en travaillant de concert sur les mêmes tâches.
    
L’application se base sur 4 critères pour estimer l’efficacité de chacun des collaborateurs du groupe : Moteur, Esprit critique, Focus et Social.
    
Les étudiants votent chaque semaine sur ces critères pour chacun des membres de leur groupe selon 4 niveaux de maîtrise pour chacun de ces critères.

Un système de cartons jaunes permet de mettre en évidence un étudiant qui reçoit dans les 3 dernières semaines plus de 5 évaluations à “insuffisant”. Ce carton est affiché pour l’étudiant et visible par tout le groupe. Cela permet de mettre en place de façon douce un système de remédiation au niveau du groupe.
    
Si l’étudiant en question cumul 3 cartons jaunes, il reçoit un carton rouge. L’application sollicite alors l’enseignant comme arbitre pour résoudre le problème.

## Installer et utiliser l'application sur votre serveur

### Pré-requis :
- Apache >= 2.4
- PHP >= 7.0
-  MariaDB >= 10.1

### Installation/Structure de la base de données

L'application GesTeam-TesTeam doit être connectée à deux bases de données :
- la base de données Moodle de votre établissement ;
- la base de données 'gesteam-testeam' dont vous n'aurez qu'à importer le fichier `structure.sql` dans votre propre base.

Vous trouverez les paramètrages de connexion aux bases de données dans les fichier `app/Model.php` des deux application : GesTeam et TesTeam.

### Personnalisation de l'application
Vous devez remplir tous les champs du fichier de configuration `config.php` afin de rendre l'application fonctionnelle pour votre établissement.

Pensez à remplir la page concernant les mentions légales de votre établissement `TesTeam/mentions-legales.html`.

### Placement des dossiers
Vous devez placer les deux dossiers GesTeam et TesTeam au chemin suivant : `/var/www/html` enfin qu'ils soient accessibles directement via le lien `nomdevotreserveur.fr/GesTeam` et `nomdevotreserveur.fr/TesTeam`

### Mise en place du cron
Vous devez mettre en place un cron permettant d'attribuer les cartons jaunes et rouges pour chaque nouvelle semaine. Il est donc nécessaire de programmer celui-ci le lundi à une heure où votre plateforme n'est pas surchargée, à 1 heures du matin par exemple. Voici la commande à ajouter à votre cron tab :

    0 1 * * 1 php /var/www/html/TesTeam/cron.php

### Système d'authentification 
L'application utilise un système de CAS (Central Authentication Service). Si vous utilisez un autre système d'authentification, vous devrez remplacer cette connexion par la votre dans le fichier `controllers/Login.php` des deux applications GesTeam et TesTeam.

### Support

Cette application a été développé en ECMAScript 6 (ES6), elle ne sera donc pas accessible avec les navigateur ne supportant pas cette version.

Exemple de navigateur supporté : Google Chrome, Mozilla Firefox,...

Code HTML valide au W3C : https://validator.w3.org/ 

Code CSS valide au W3C :  https://jigsaw.w3.org/css-validator/

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a><br />Ce(tte) œuvre est mise à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International</a>.
