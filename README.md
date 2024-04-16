# Bourse aux stages

*Groupe 5 : Clara Lancelle & Guillaume Couvidou*

## Mise en place :

* Liaison à la base de donnée
    Création d'un fichier .env.local à la racine du projet :

    Remplacer :
    user par votre nom d'utilisateur,
    password par votre mot de passe et
    database_name par le nom de votre base de donnée

    ```bash 
        DATABASE_URL="mysql://user:password@127.0.0.1:3306/database_name?serverVersion=8.0.32&charset=utf8mb4"
    ```
* Installation des dépendances

    ```bash 
        composer install
    ```

* Création de la base de donnée
    
    ```bash 
        php bin/console doctrine:database:create
    ```

* Création de la base de donnée
    
    ```bash 
        php bin/console doctrine:schema:update -f --complete
    ```

* Chargement des fixtures

    ```bash 
        php bin/console doctrine:fixtures:load
    ```

* Lancer l'application sur 127.0.0.1:8000
  
    ```bash 
        symfony serve
    ```

