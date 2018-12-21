# GDM - Generic Data Management - Notes pour développement

Gestion générique d'objet (persistance et organisation).

-------

## Architecture logiciels

- PHP 7.2
- MySQL 8.0


## Environnement d'execution (Containers Docker)

#### *Démarrage des containers*

```bash
$ docker-compose [-f docker-compose.yml] up
```
#### Autres commandes docker

- Connaitre l'IP d'un container
```bash
$ docker inspect  gdm-dev_db_1
```

L'environnement d'execution est structuré de la manière suivantes :

|Service|Image Docker|Hôte(:port)|Description|Détails|Volumes|
|:-------:|:-------|:-------|:-------:|
|Base de données | mysql:8.0 | localhost:3333 | **L/M:** *root / rootdev* |Instance locale de base de données MySQL.|-|
|RestFull API | webdevops/php-apache:7.2 | [localhost:8989](http://localhost:8989) | Serveur Web|TODO|-|
|Jenkins | jenkins/jenkins:lts-slim | [localhost:8080](http://localhost:8080) | Interface Web Jenkins |Instance de plateforme d'intégration continue.| -|

## Commandes en VRAC

- PHP Unit :
```bash
$  docker inspect  gdm-dev_db_1
```

## Déploiement de la base de données
