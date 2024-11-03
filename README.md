# Installation

## Avec docker 
```bash
composer install
```
puis : 
```bash
docker compose up
```
Créer la base de donnée 'heritagePHP' (nom modifiable dans Database.php, idem pour les identifiants)

Le site sera accessible à l'adresse : 
```
http://heritage.localhost:8080
```

phpMyAdmin
```
http://heritage.localhost:8888
```
| server                     | username | password                                    |
|---------------------------|--------------|------------------------------------------|
| db       | root          | root                             |

# Initialisation : 
Pour load les fixtures : 
```
http://heritage.localhost:8888/loadFixtures
```

## Identifiants

2 comptes sont crées, pour se connecter:

| Email                     | Mot de passe | Rôles                                    |
|---------------------------|--------------|------------------------------------------|
| admin@admin.com       | admin          | ROLE_ADMIN                             |
| user@user.com   | user          | ROLE_USER                             |



