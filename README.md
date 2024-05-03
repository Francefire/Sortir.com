## Dépendances

- php >= 8.1 (lower untested)
- composer >= 2.7 (lower untested)
- symfony-cli >= 5.8 (lower untested)

## Installation

- Installation des dépendances
    ```sh
    composer install
    ```

- Création de la base de donnée
    ```sh
    php bin/console doctrine:database:create
    ```

- Création des tableaux dans la base donnée
    ```sh
    php bin/console doctrine:schema:update --force
    ```

- Initialisation des données dans la base de données
    ```sh
    php bin/console doctrine:fixtures:load
    ```

## Configuration de l'environnement

Modifier les lignes suivantes du fichier `.env` avec vos valeurs

```
APP_ENV=
APP_SECRET=
DATABASE_URL=
MESSENGER_TRANSPORT_DSN=
MAILER_DSN=
```

## Lancer l'application

```sh
symfony server:start -d
```

## Arrêter l'application

```sh
symfony server:stop -d
```