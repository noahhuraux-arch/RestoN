# SAE 3.01 Développement d’une application

## Auteurs


Dehêtre-Cordier Bastien : dehe0014  
Fagot-Naude Amélien : fago0014  
Gaboyard Aymeric : gabo0013  
Huraux Noah : hura0003  

### **root** : Travail fait par Noah Huraux -> oublie de configuration git sur la VM

## Installation / Configuration

### Installation par `Composer`

Cloner le dépot : 
```bash
git clone https://iut-info.univ-reims.fr/gitlab/gabo0013/resto-n.git
```

Lancer `composer install` pour installer [PHP Coding Standards Fixer](https://cs.symfony.com/) et le configurer dans PhpStorm (le fichier `.php-cs-fixer.php` contient les règles personnalisées basées sur la recommandation [Symfony](https://symfony.com/doc/current/contributing/code/standards.html)).

### Configurer PhpStorm

Configurer l'intégration de PHP Coding Standards Fixer dans PhpStorm en fixant le jeu de règles sur `Custom` et en désignant `.php-cs-fixer.php` comme fichier de configuration de règles de codage.

## Serveur Web local

### Démarrer le serveur Web

Une fois le projet cloné, mettez en place votre fichier .env.local en y mettant vos informations. 
Voici une commande pour copier le fichier et le modifier :
```bash
cp .env .env.local
nano .env.local
```

Attention la commande suivante est à utiliser la première fois uniquement, car à chaque build la BD sera créer automatiquement
et donc reset par la même occasion.
```bash
docker compose up --build -d
docker compose exec php composer install
```

Dans le cas où vous cherchez à redémarrer votre serveur, utilisez les commandes suivantes : 
```bash
docker compose down
docker compose up
```

### Accéder au serveur Web

Naviguez alors à partir de cette adresse : [https://localhost:8080/](https://127.0.0.1:8000/)  
A noté que le site est déployé à l'adresse suivante (accessible uniquement via le VPN, ou sur une machine de l'IUT) : 
[Resto-N](http://10.31.32.12) 

## Configuration de la base de données

Le projet utilise **Doctrine ORM** pour la gestion de la base de données.

### Paramètres de connexion

La configuration de la base de données se fait dans le fichier `.env` situé à la racine du projet.
Modifiez la variable `DATABASE_URL` avec vos identifiants (utilisateur, mot de passe, hôte, nom de la base).

### Création et mise à jour de la base

Pour initialiser complètement la base de données (création, migrations et chargement des données de test), exécutez la commande suivante :

```bash
composer db
```

### Identifiants de connexion (Fixtures)

Une fois la base de données générée, vous pouvez vous connecter avec les comptes suivants :

#### Propriétaires (6) :

-   **Email :** `prop1@example.com` (Propriétaire 1), `prop2@example.com`, etc.
-   **Mot de passe :** `test`

Ces comptes disposent du rôle `ROLE_PROPRIETAIRE` et permettent d'accéder à l'interface de gestion du restaurant.

#### Serveurs (10) :

-   **Email :** `serv1@example.com` (Serveur 1), `serv1@example.com`, etc.
-   **Mot de passe :** `test`

-   Ces comptes disposent du rôle `ROLE_SERVEUR`.

## Style de codage

Le code suit la recommandation [Symfony](https://symfony.com/doc/current/contributing/code/standards.html) :

## Scripts disponibles

-   `composer start` : lance le serveur web de test (`symfony serve`) sans restriction de durée d'exécution
-   `composer test:phpcs` : vérifie le code PHP avec PHP CS Fixer
-   `composer fix:phpcs` : corrige le code PHP avec PHP CS Fixer
-   `composer test:twigcs` : vérifie le code Twig avec Twig CS Fixer
-   `composer fix:twigcs` : corrige le code Twig avec Twig CS Fixer
-   `composer test` : exécute `test:phpcs` et `test:twigcs`
-   `composer fix` : exécute `fix:phpcs` et `fix:twigcs`
-   `composer migration` : exécute `doctrine:migrations:migrate` pour effectuer les migrations
-   `composer db` : exécute `composer db` Génére de façon fiable une nouvelle base de donnée et qui exécute les commandes suivantes :
    -   `php bin/console doctrine:database:drop --force --if-exists` : Destruction forcée de la base de données.
    -   `php bin/console doctrine:database:create` : Création de la base de données.
    -   `php bin/console doctrine:migrations:migrate --no-interaction` : Application des migrations successives sans questions interactives .
    -   `php bin/console doctrine:fixtures:load --no-interaction` : Génération des données factices sans questions interactives .

    
