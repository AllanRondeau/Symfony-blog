# Projet Symfony avec Tailwind et Docker

Ce projet utilise Symfony pour le backend, Tailwind CSS pour le frontend, et Docker pour gérer la base de données. Ce guide vous aidera à installer et lancer le projet en local.

## Prérequis
Assurez-vous que les outils suivants sont installés sur votre machine :

- [PHP](https://www.php.net/) (version 8.2 ou supérieure)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) (version 16 ou supérieure)
- [npm](https://www.npmjs.com/) 
- [Docker](https://www.docker.com/)

## Installation

2. Installez les dépendances PHP :
   ```bash
   composer install
   ```

3. Installez les dépendances JavaScript :
   ```bash
   npm install
   ```

4. Configurez l'environnement :
   Copiez le fichier `.env` et renommez-le en `.env.local`, puis ajustez les variables d'environnement si nécessaire (par exemple, la configuration de la base de données Docker).
   ```bash
   cp .env .env.local
   ```

## Lancer le projet

1. Démarrez les conteneurs Docker (base de données) :
   ```bash
   docker-compose up -d
   ```

2. Démarrez le serveur Symfony :
   ```bash
   symfony server:start
   ```

3. Compilez les assets avec Tailwind :
   ```bash
   npm run watch
   ```

4. Accédez au projet dans votre navigateur à l'adresse suivante :
   ```
   http://localhost:8000
   ```

## Commandes utiles

- **Arrêter les conteneurs Docker :**
  ```bash
  docker-compose down
  ```

- **Exécuter des migrations :**
  ```bash
  php bin/console doctrine:migrations:migrate
  ```


