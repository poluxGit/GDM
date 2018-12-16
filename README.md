# GDM - Generic Data Management

Gestion générique d'objet (persistance et organisation).

## Infos Techniques

Projet PHP Composer => composer install --no-dev

*PHP Version => 7.1"


## Concepts du modèle générique

Le modèle GDM implémente et utilise les concepts permettant la gestion dynamique de stockage dans un modèle relationnel d' **"Objets"**.

### Objets *Simple* vs *Complex*

Les objets Objets peuvent être de 2 types : *Simple* ou *Complex*.
- Les **Objets** *Simple* sont définis par des attributs (ou metadonnées) génériquement définis (via la définition du modèle) et ce, de manière pérenne dans le temps.
- Les **Objets** *Complex* sont définis par des attributs génériquement définis et qui vont pouvoir évoluer dans le temps. L'historique des valeurs est conservé par le système interne de *Versionning*.

### Liens entre objets

***TO WRITE***


#### Règle de génération des TID et BID

|Objet|Tablename|TID Pattern|Exemple|
|:-------:|:-------|:-------|:-------:|
|OBD | A000_OBD | MODEL_CODE.OBD-OBDPREFIX_000X| *Ex: E1.OBD-CAT_0001*|
|MDL | A000_MDL | MDL-MODEL_CODE_000X| *Ex: MDL-E1_0010*|


## Structure du projet (Fichiers & Répertoires)

|Nom|Description|Autres|
|:-------:|:-------|:-------|
| _dev | Répertoire de stockage des données durant l'execution.| Utilisé par les containers des Dockers ... php.ini, mysql_data ... |
| data | Répertoire relatif à la base de données.  | Fichiers de modélisation, Scripts SQL de déploiement ...|
| src | Répertoire des sources des différents modules.  | Répertoire principal de développement.|
