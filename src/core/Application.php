<?php

namespace GOM\Core;

use GOM\Core\Data\Internal;
use GOM\Core\Data;
use GOM\Core\Internal\Exception as Exceptions;
/**
 * GOMFactory - Static Class
 *
 * Classe principale du program
 */
class Application
{
  /**
   * @var string
   */
  protected static $_sDbDsn = null;
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
  static function loadDBSettings($psJSONFilePath)
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
      self::$_sDbDsn      =  $ljsonContent['database']['dsn'];
      self::$_sDbUser     =  $ljsonContent['database']['user'];
      self::$_sDbPassword =  $ljsonContent['database']['password'];
    } else {
      $laVal = self::checkMandatoryApplicationSettingsAreDefined($ljsonContent);
      $lAValue = array_shift($laVal);
      throw new Exceptions\ApplicationSettingsMandatorySettingNotDefinedException($lAValue);
    }

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
        self::$_oDbPDOHandler = new \PDO(self::$_sDbDsn , self::$_sDbUser , self::$_sDbPassword);
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
    $lobj = new ObjectDefinition();
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
    } elseif (!\array_key_exists('dsn',$paApplicationSettings['database'])) {
      $laResult[] = "database\dsn";
    } elseif (!\array_key_exists('user',$paApplicationSettings['database'])) {
      $laResult[] = "database\user";
    } elseif (!\array_key_exists('password',$paApplicationSettings['database'])) {
      $laResult[] = "database\password";
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

}//end class
