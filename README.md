# Vite & Gourmand

Application web de commande de menus pour un traiteur bordelais.
Projet réalisé dans le cadre de l'ECF du titre professionnel Développeur Web et Web Mobile (Studi).

Site en ligne : https://vite-et-gourmand.lynamaker.fr

## Technologies
PHP 8.3 (MVC, sans framework), MySQL 8.4 (PDO), MongoDB 7.0, Bootstrap 5.3.8, JavaScript vanilla, PHPMailer, Mailpit, Chart.js, Docker.

## Prérequis
- Git
- Docker et Docker Compose

## Installation en local
1. Cloner le dépôt :
```
git clone https://github.com/Lyna91/vite-et-gourmand.git
cd vite-et-gourmand
```
2. Créer le fichier .env à partir du modèle, puis remplir les mots de passe :
```
cp .env.example .env
```
3. Lancer les conteneurs :
```
docker compose up -d
```
4. Installer les dépendances PHP :
```
docker compose exec php composer install
```
5. Créer les tables et les données de démonstration :
```
docker compose exec -T mysql sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" --default-character-set=utf8mb4 "$MYSQL_DATABASE"' < sql/creation.sql
```
Au premier lancement, MySQL met quelques secondes à démarrer : si l'import échoue, attendre un peu et relancer la commande.

## Accès
- Site : http://localhost:8080
- Mailpit (mails de test) : http://localhost:8025

Statistiques admin : cliquer sur « Mettre à jour les statistiques » pour copier les commandes de MySQL vers MongoDB.

## Comptes de test
Les identifiants des comptes de test (site en ligne et installation locale) sont fournis dans la copie de l’ECF.

## Documentation
- docs/ : documentation technique, MCD, MLD, diagramme de cas d'utilisation, diagramme de séquence, déploiement (deploiement.md)
- sql/creation.sql : script de création de la base
