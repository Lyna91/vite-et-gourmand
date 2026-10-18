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

`docker compose ps` affiche le nombre de conteneurs et l'état de fonctionnement, 4 conteneurs (php, nginx, mysql, mongo) à l’état Up.

Seul nginx publie un port `0.0.0.0:80->80/tcp`. MySQL et MongoDB ne sont pas joignables depuis Internet.

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

On vérifie dans le navigateur que tout fonctionne avec l'adresse donnée : http://152.228.135.220

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

**Attention** : ne jamais lancer `docker compose down -v` : le `-v` supprime les volumes, donc toutes les données de la base (commandes, comptes clients…)

`git pull` ne touche pas au `.env` du serveur, puisqu’il n’est pas dans Git.

## 6. Améliorations prévues

- **HTTPS** : chiffrer les échanges. Obligatoire avant la page de connexion, pour que les mots de passe ne circulent pas en clair.
- **Un nom de domaine** : une adresse lisible à la place de l'IP, qui facilite aussi la mise en place du HTTPS.
- **Des sauvegardes de la base de données** : la sauvegarde OVH copie tout le serveur, mais un export régulier de la base avec `mysqldump` permet de restaurer seulement les données.
