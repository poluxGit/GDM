<?php

namespace GOM\Core;

use GOM\Core\Data\Internal;
use GOM\Core\Data;
use GOM\Core\Internal\Exception as Exceptions;
/**
 * Application - Static Class
 *
 * Classe principale du program
 */
class Application
{
  /**
   * @var string
   */
  protected static $_sDbType = null;

  /**
   * @var string
   */
  protected static $_sDbHost = null;
  /**
   * @var int
   */
  protected static $_iDbPort = null;
  /**
   * @var string
   */
  protected static $_sDbSchema = null;

  /**
   * @var string
   */
  protected static $_sDbUser = null;
  /**
   * @var string
   */
  protected static $_sDbPassword = null;
  /**
   * @var \PDO
   */
  protected static $_oDbPDOHandler = null;

  /**
   * Chargement des paramètres de connexion à la base de données depuis
   * un fichier de settings extérieur.
   *
   * @param  $psJSONFilePath   string  Chemin du fichier de paramètre à charger.
   * @static
   */
  static function loadDBSettings($psJSONFilePath,$pbAutoRegister=true)
  {
    // fichier JSON existant ?
    if (!file_exists($psJSONFilePath)) {
      throw new Exceptions\ApplicationSettingsFileNotFoundException($psJSONFilePath);
    }
    // fichier JSON  bien formé ?
    $json_data = file_get_contents($psJSONFilePath);
    $json_data = stripslashes($json_data);
    $ljsonContent = json_decode($json_data , true);
    if ($ljsonContent === NULL) {
      $lstrErrJSON = json_last_error_msg();
      throw new Exceptions\ApplicationSettingsFileInvalidFormatException($psJSONFilePath,$lstrErrJSON);
    }

    // Vérification de la présence des attributs obligatoires
    if (self::isAllMandatoryApplicationSettingsAreDefined($ljsonContent)) {
      self::$_sDbType     =  $ljsonContent['database']['dbtype'];
      self::$_sDbHost     =  $ljsonContent['database']['host'];
      self::$_sDbSchema   =  $ljsonContent['database']['schema'];
      self::$_iDbPort     =  $ljsonContent['database']['port'];
      self::$_sDbUser     =  $ljsonContent['database']['user'];
      self::$_sDbPassword =  $ljsonContent['database']['password'];
    } else {
      $laVal = self::checkMandatoryApplicationSettingsAreDefined($ljsonContent);
      $lAValue = array_shift($laVal);
      throw new Exceptions\ApplicationSettingsMandatorySettingNotDefinedException($lAValue);
    }

    // Initialisation de la base et des classes!
    self::initDBConnection();
    self::setupClasses();
  }//end loadDBSettings()

  /**
   * Configuration des classes
   */
  protected static function setupClasses(){
    \GOM\Core\Data\Internal\GOMObject::setCommonPDOConnection(self::$_oDbPDOHandler);
    \GOM\Core\DataFactory::setPDOConnection(self::$_oDbPDOHandler);
  }//end setupClasses

  /**
   * Initialisation de la connexion à la base de données.
   *
   * @static
   */
  static function initDBConnection()
  {
    try{
      if (self::$_oDbPDOHandler == null) {

        $lsDSN = DatabaseManager::buildDatabaseDSN(
            self::$_sDbType,
            self::$_sDbSchema,
            self::$_sDbHost,
            self::$_iDbPort
          );
        self::$_oDbPDOHandler = new \PDO($lsDSN , self::$_sDbUser , self::$_sDbPassword);
      }
    }
    catch(PDOException $e)
    {
      throw new Exceptions\DatabaseException($e->getMessage());
    }
  }//end initDBConnection()

  /**
   * Import d'un modèle de données depuis une donnée au format JSON
   *
   * @param json $psJSONData   Données JSON du modèle à importer
   * @static
   */
  static function importModelFromJSONData($psJSONData)
  {
    //XXX self::initDBConnection();
    //$lobj = new ObjectDefinition();
  }//end importMDLFromJSONData()

  /**
   * Vérifie l'existence de tous les champs obligatoires à l'Application
   *
   * @param array $paApplicationSettings  Tableau des paramètres applicatifs.
   * @return array Renvoi un tableau vide si tous les champs existent, sinon les champs manquants (1 par ligne).
   */
  static function checkMandatoryApplicationSettingsAreDefined($paApplicationSettings)
  {
    $laResult = [];

    if (is_null($paApplicationSettings) || !\array_key_exists('database',$paApplicationSettings)) {
      $laResult[] = "database";
    } elseif (!\array_key_exists('schema',$paApplicationSettings['database'])) {
      $laResult[] = "database\schema";
    } elseif (!\array_key_exists('user',$paApplicationSettings['database'])) {
      $laResult[] = "database\user";
    } elseif (!\array_key_exists('password',$paApplicationSettings['database'])) {
      $laResult[] = "database\password";
    } elseif (!\array_key_exists('host',$paApplicationSettings['database'])) {
      $laResult[] = "database\host";
    } elseif (!\array_key_exists('port',$paApplicationSettings['database'])) {
      $laResult[] = "database\port";
    } elseif (!\array_key_exists('dbtype',$paApplicationSettings['database'])) {
      $laResult[] = "database\dbtype";
    }

    return $laResult;
  }//end checkMandatoryApplicationSettingsAreDefined()

  /**
   * Retourne vrai si tous les champs mandatory sont présents
   *
   * @param array $paApplicationSettings  Tableau des paramètres applicatifs.
   * @return array Renvoi Vrai si tous les champs existent, sinon Faux
   */
  static function isAllMandatoryApplicationSettingsAreDefined($paApplicationSettings)
  {
    return count(self::checkMandatoryApplicationSettingsAreDefined($paApplicationSettings))==0;
  }//end isAllMandatoryApplicationSettingsAreDefined()

  /**
   * Déploie le schéma applicatif 'Core' sur la base spécifiée
   *
   * @param string $psDBType    Type de la connection du DSN.
   * @param string $psDBSchema  Schéma cible.
   * @param string $psDBHost    Hote cible.
   * @param string $piDBPort    Port cible.
   */
  static function deploySchemaToTargetDB($psTargetSchemaName,$psDbUser,$psDbPass,$psDbHost)
  {
    try {
      // Etape 0 -> Identifier les scripts disponibles
      // Etape 1 -> Remplacer le schéma dans le fichier anonyme
      // Etape 3 -> TODO

    } catch (\Exception $e) {

    } finally {

    }

  }//end deploySchemaToTargetDB()

  /**
   * Déploie le schéma applicatif 'Core' sur la base par défaut.
   *
   * @internal Fait appel au script déjà générée à la racine Appicative 'db.sql'
   *
   * @param string $psDBType    Type de la connection du DSN.
   * @param string $psDBSchema  Schéma cible.
   * @param string $psDBHost    Hote cible.
   * @param string $piDBPort    Port cible.
   */
  static function deploySchemaToDefaultTargetDB($psDbUser,$psDbPass,$psDbHost)
  {
    try {
      // Etape 0 -> Identifier les scripts disponibles
      // Etape 1 -> Remplacer le schéma dans le fichier anonyme
      // Etape 3 -> TODO

    } catch (\Exception $e) {

    } finally {

    }

  }//end deploySchemaToTargetDB()

}//end class
