## Préparation

- Installation des dépendances externes
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

## Lancement de l'application

```sh
symfony server:start -d
```