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

- Lancer une console interactive (User : default)
```bash
$ docker exec -it gdm-dev_jenkins_1 bash
```

- Lancer une console interactive (User : root)
```bash
$ docker exec -u 0 -it gdm-dev_jenkins_1 bash
```

- (Re)Déployer la base de données DEV depuis le docker JENKINS  (User : root)
```bash
$ docker exec -u 0 -it gdm-dev_jenkins_1 sh -c "cd /var/jenkins_home/workspace/GenericDataManagement/src && echo a && php gom-cli.php DEPLOY_DB 172.17.0.2 3306 GDM_DEV root dev"
```

- Déploiment du model ECM de test dans la base de données DEV depuis le docker JENKINS  (User : root)
```bash
$ docker exec -u 0 -it gdm-dev_jenkins_1 sh -c "cd /var/jenkins_home/workspace/GenericDataManagement/src && echo a && php gom-cli.php IMP_SCH model/ecm.json"
```


L'environnement d'execution est structuré de la manière suivantes :

|Service|Image Docker|Hôte(:port)|Docker #ID|Description|Détails|Volumes|
|:-------:|:-------|:-------|:-------:|
|Base de données | mysql:8.0 | localhost:3336| gdm-dev_db_1| **L/M:** *root / rootdev* |Instance locale de base de données MySQL.|-|
|RestFull API | webdevops/php-apache:7.2|  [localhost:8989](http://localhost:8989) || Serveur Web|TODO|-|
|Jenkins | jenkins/jenkins:lts-slim | [localhost:8080](http://localhost:8080)|gdm-dev_jenkins_1 | Interface Web Jenkins |Instance de plateforme d'intégration continue.| -|

## Commandes en VRAC

- **PHP Unit**
```bash
$ cd tests
$ php phpunit.phar
```
- **SQL**

```SQL
SELECT DMA_createNewObjectDefinition('SI.MDL-SPE-0002','DOC','Document','Document Simple','com' ,'Simple','DOC',20,'','DOCB','','E100_DOCUMENT');

ANALYZE TABLE Z000_LOGS;
ANALYZE TABLE A000_MDL;
ANALYZE TABLE A000_OBD;
```

## Déploiement de la base de données
