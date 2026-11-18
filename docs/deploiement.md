# Documentation de déploiement – Vite & Gourmand

## 1. Hébergement choisi

Pour l'hébergeur, j'ai choisi OVH avec un VPS (serveur privé virtuel) avec une offre mensuelle

- 2 vCores, 4 Go RAM, 40 Go SSD NVMe
- Sauvegarde quotidienne
- Trafic illimité
- 500 Mbit/s bande passante publique.

Je suis partie sur un VPS qui prend en charge et fait tourner Docker, MongoDB et MySQL, contrairement aux serveurs mutualisés.

L'abonnement me coûte 5,39€ TTC par mois renouvelable tous les mois et sans engagement.

Le serveur marche sur le système Ubuntu version 24.04 (je l'ai choisi dans les options lors de l'achat)

Le serveur est basé en France à Strasbourg, un avantage pour le RGPD pour les données clients.

## 2. Sécurisation du serveur

Une connexion au serveur via une clé SSH : afin de ne pas avoir de soucis de piratage de mot de passe, la connexion sur le serveur se fait via la clé SSH. Il y a 2 clés (publique avec .pub pour le serveur / et privée sans .pub qui reste sur mon PC).

Je me connecte au serveur via

```bash
ssh -i ~/.ssh/id_ed25519_vps ubuntu@152.228.135.220
```

J'ai par ailleurs désactivé la connexion par mot de passe ainsi qu'en root. J'ai écrit "PasswordAuthentication no et PermitRootLogin no" avec nano dans le fichier `/etc/ssh/sshd_config.d/01-securite.conf.`

J'ai désactivé ces connexions pour contrer les attaques de bot qui essayent de deviner les mots de passe. Pour root, étant le premier à être attaqué il a été désactivé pour cette raison. On passe par ubuntu, puis sudo si c’est nécessaire.

J'ai également activé le pare-feu `ufw` pour plus de sécurité. Tout ce qui entre est refusé sauf SSH (22), HTTP (80) et HTTPS (443).

## 3. Installation de Docker

Pour installer Docker, j'ai utilisé la documentation sur le site : https://docs.docker.com/engine/install/ubuntu#install-using-the-repository

Pour utiliser Docker sans `sudo`, j’ai ajouté l’utilisateur `ubuntu` au groupe docker :

```bash
sudo usermod -aG docker ubuntu
```

Une fois validé, il faut faire une déconnexion et reconnexion pour que cela soit bien pris en compte.

Vérification que Docker fonctionne via `hello-world`.

```bash
docker --version          # Docker version 29.9.1
docker compose version    # Docker Compose version v5.6.0
docker run hello-world    # affiche « Hello from Docker! »
```

## 4. Mise en ligne de l'application

### a. Récupération du code depuis GitHub

```bash
git clone https://github.com/Lyna91/vite-et-gourmand.git
cd vite-et-gourmand
```

Pas besoin d'avoir une clé GitHub puisque le dépôt est public.

### b. Création du fichier .env pour le serveur

```bash
cp .env.example .env
chmod 600 .env
```

On copie avec cp le fichier `.env.example` pour créer le .env qu'on remplit après.

Avec le `chmod 600`, seul l'utilisateur ubuntu peut lire et modifier le fichier. Les autres comptes du serveur ne peuvent pas lire les mots de passe.

Les mots de passe sont générés avec `openssl rand -hex 24` qui produit une suite aléatoire de 48 caractères (chiffres et lettres de a à f).

C'est une sécurité en plus : les mots de passe sont différents du local parce que si l’un des deux fuite, l’autre reste protégé.

Les deux réglages qui changent par rapport au local :

| Variable    | Local       | Serveur    |
| ----------- | ----------- | ---------- |
| `HTTP_PORT` | 8080        | 80         |
| `PHP_ENV`   | development | production |

`80` est le port standard du web. Grâce à lui, on tape l’adresse sans :8080.

`PHP_ENV` : en production, les erreurs PHP sont masquées aux visiteurs, parce qu’elles pourraient révéler des informations sur le code. (en mode development en local)

Le fichier `.env` pour le serveur contient les mots de passe mais n'est jamais envoyé sur GitHub grâce au `.gitignore`.

### c. Lancer les conteneurs

On lance Docker avec les commandes suivantes :

```bash
docker compose up -d --build
docker compose ps
```

`-d` pour lancer les conteneurs en arrière plan, `--build` qui construit l'image PHP à partir du Dockerfile

`docker compose ps` affiche le nombre de conteneurs et l'état de fonctionnement, 5 conteneurs (php, nginx, mysql, mongo, mailpit) à l’état Up.

Seul nginx publie des ports ouverts sur Internet (80 et 443). MySQL et MongoDB ne sont pas joignables depuis Internet, et Mailpit n'écoute que sur `127.0.0.1` (le serveur lui-même).

Le dossier `vendor/` (les bibliothèques installées par Composer : PHPMailer, bibliothèque MongoDB) n'est pas dans Git : Composer le recrée à partir de `composer.json` et `composer.lock`. Il faut donc installer les dépendances dans le conteneur PHP :

```bash
docker compose exec php composer install --no-dev --optimize-autoloader
```

`--no-dev` n'installe pas les outils de développement, `--optimize-autoloader` accélère le chargement des classes en production.

### d. Importer la base de données

On va chercher le fichier `creation.sql` pour l'envoyer dans le conteneur MySQL.

Au premier lancement, MySQL met environ 30 secondes à démarrer. On attend le message `ready for connections` :

```bash
docker compose logs mysql | tail -3
```

Import du fichier dans le conteneur MySQL :

```bash
docker compose exec -T mysql sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" --default-character-set=utf8mb4 "$MYSQL_DATABASE"' < sql/creation.sql
```

Vérification :

```bash
docker compose exec mysql sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" -e "SHOW TABLES;"'
```

Le mot de passe n'est jamais tapé dans la commande parce que `$MYSQL_ROOT_PASSWORD` est lu dans le conteneur, qui le reçoit du fichier `.env`

Avec `utf8mb4`, les accents s'afficheront correctement. On attend un résultat de 14 tables.

### e. Vérifier

On vérifie dans le navigateur que tout fonctionne avec l'adresse du site : https://vite-et-gourmand.lynamaker.fr (voir la partie 6 pour le nom de domaine et le HTTPS).

J'ai vérifié que le site fonctionne via les pages accueil, menus, détail d’un menu, page 404.

Avec la commande :

```bash
docker compose exec php php -i | grep display_errors   # attendu : display_errors => Off => Off
```

On vérifie que les erreurs PHP sont masquées.

## 5. Mettre le site à jour

La mise à jour du site se fait en 2 temps.

En local sur le PC, la fusion des réalisations dans `main` sur GitHub (feature → `dev` → `main`, avec des pull requests)

Mise à jour sur le serveur avec les commandes :

```bash
cd ~/vite-et-gourmand
git pull
docker compose up -d --build
```

Si `composer.json` a changé (nouvelle bibliothèque), il faut relancer l'installation des dépendances :

```bash
docker compose exec php composer install --no-dev --optimize-autoloader
```

Si le site affiche une **erreur 502** après la recréation du conteneur PHP, Nginx essaie probablement encore de joindre l'ancien conteneur. Il suffit de le redémarrer :

```bash
docker compose restart nginx
```

**Attention** : ne jamais lancer `docker compose down -v` : le `-v` supprime les volumes, donc toutes les données de la base (commandes, comptes clients…)

`git pull` ne touche pas au `.env` du serveur, puisqu’il n’est pas dans Git.

## 6. Nom de domaine et HTTPS

### a. Le nom de domaine

J'ai acheté le nom de domaine `lynamaker.fr` chez OVH, pour tout centraliser chez le même hébergeur. J'ai créé le sous-domaine `vite-et-gourmand.lynamaker.fr` avec un enregistrement DNS de type **A**, qui pointe vers l'adresse IP du VPS.

### b. Le certificat HTTPS (Let's Encrypt)

Le HTTPS chiffre les échanges entre le navigateur et le serveur : les mots de passe ne circulent plus en clair. Le certificat est gratuit et fourni par Let's Encrypt, avec l'outil `certbot`, installé sur le serveur.

J'ai utilisé le mode `--standalone` : certbot lance son propre petit serveur web sur le port 80 pour prouver que le domaine est bien à moi. Comme Nginx occupe déjà ce port, les options `--pre-hook` et `--post-hook` arrêtent Nginx avant et le relancent après :

```bash
sudo certbot certonly --standalone -d vite-et-gourmand.lynamaker.fr \
  --pre-hook "docker compose --project-directory /home/ubuntu/vite-et-gourmand stop nginx" \
  --post-hook "docker compose --project-directory /home/ubuntu/vite-et-gourmand start nginx"
```

`--project-directory` indique le dossier du projet : une première version avec `cd` dans le hook était refusée par certbot.

Le certificat est valable 90 jours. Le renouvellement est automatique, et je l'ai testé avec :

```bash
sudo certbot renew --dry-run
```

### c. La configuration de production

La configuration de production s'ajoute par-dessus celle du local, dans le fichier `docker-compose.prod.yml` :

- Nginx publie aussi le port **443** (HTTPS) ;
- il utilise la configuration `docker/nginx/prod.conf`, qui redirige tout le HTTP (et l'adresse IP) vers `https://vite-et-gourmand.lynamaker.fr` et active le certificat ;
- les certificats du serveur (`/etc/letsencrypt`) sont partagés avec le conteneur Nginx, en lecture seule.

Pour que Docker utilise automatiquement les deux fichiers, j'ai ajouté cette ligne dans le `.env` du serveur (et seulement du serveur) :

```bash
COMPOSE_FILE=docker-compose.yml:docker-compose.prod.yml
```

Ainsi, un simple `docker compose up -d` suffit sur le serveur, sans avoir à préciser les fichiers à chaque commande.

## 7. Consulter les mails (Mailpit)

En production aussi, les mails sont capturés par Mailpit (aucun vrai mail n'est envoyé). Mailpit n'écoute que sur `127.0.0.1`, il n'est donc pas accessible depuis Internet. Pour le consulter, j'ouvre depuis mon PC, dans un terminal à part, un **tunnel SSH**, qui fait passer la connexion de façon chiffrée par mon accès au serveur :

```bash
ssh -i ~/.ssh/id_ed25519_vps -L 8026:127.0.0.1:8025 ubuntu@152.228.135.220
```

Tant que ce terminal reste ouvert, Mailpit est consultable sur http://localhost:8026.

## 8. Améliorations prévues

- **Un vrai serveur d'envoi de mails (SMTP)** : remplacer Mailpit par un service d'envoi réel ; le code est prêt, il suffit de changer les variables `SMTP_` du `.env`.
- **Des sauvegardes de la base de données** : la sauvegarde OVH copie tout le serveur, mais un export régulier de la base avec `mysqldump` permet de restaurer seulement les données.
