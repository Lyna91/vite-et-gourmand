FROM php:8.3-fpm
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
# Extension pour se connecter à MySQL avec PDO installe le pilote PDO pour MySQL / docker-php-ext-install est un petit outil fourni dans l'image PHP officielle pour ajouter des extensions.
RUN docker-php-ext-install pdo_mysql 

# Extension pour se connecter à MongoDB /installe les outils dont il a besoin pour être compilé ;
RUN apt-get update && apt-get install -y libssl-dev pkg-config \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*


#installe les outils dont il a besoin pour être compilé ;pecl install mongodb télécharge et compile l'extension (PECL est le « magasin » d'extensions PHP) ;
# docker-php-ext-enable mongodb l'active ;
# rm -rf /var/lib/apt/lists/* fait le ménage pour que l'image reste légère.
# Les \ en fin de ligne veulent dire « la commande continue à la ligne suivante ».