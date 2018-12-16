<?php

namespace GOM\Core;

use GOM\Core\Data\Internal;
use GOM\Core\Data;
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
  static $_sDbDsn = null;
  /**
   * @var string
   */
  static $_sDbUser = null;
  /**
   * @var string
   */
  static $_sDbPassword = null;
  /**
   * @var \PDO
   */
  static $_oDbPDOHandler = null;

  /**
   * Chargement des paramètres de connexion à la base de données depuis
   * un fichier de settings extérieur.
   *
   * @param  $p_sJSONFilePath   string  Chemin du fichier de paramètre à charger.
   * @static
   */
  static function loadDBSettings($p_sJSONFilePath)
  {
    // fichier JSON existant ?
    if (!file_exists($p_sJSONFilePath)) {
      throw new \Exception("Le fichier source ne peux pas être atteint. (i.e : '".$p_sJSONFilePath."')");
    }
    // fichier JSON  bien formé ?
    $json_data = file_get_contents($p_sJSONFilePath);
    $json_data = stripslashes($json_data);
    $ljsonContent = json_decode($json_data ,true);
    if ($ljsonContent === NULL) {
      $lstrErrJSON = json_last_error_msg();
      throw new \Exception("Le fichier source n'est pas interprétable. (i.e : '".$p_sJSONFilePath."')\nJSON Error => ".$lstrErrJSON);
    }

    // Vérification de la présence des attributs obligatoires
    self::$_sDbDsn      =  $ljsonContent['database']['dsn'];
    self::$_sDbUser     =  $ljsonContent['database']['user'];
    self::$_sDbPassword =  $ljsonContent['database']['password'];

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
        self::$_oDbPDOHandler = new \PDO(self::$_sDbDsn ,self::$_sDbUser ,self::$_sDbPassword);
      }
    }
    catch(PDOException $e)
    {
      echo "Connexion à la base GOM échouée : ".$e->getMessage()."\n";
    }
  }//end initDBConnection()

  /**
   * Import d'un modèle de données depuis une donnée au format JSON
   *
   * @param json $p_sJSONData   Données JSON du modèle à importer
   * @static
   */
  static function importModelFromJSONData($p_sJSONData)
  {
    //XXX self::initDBConnection();
    $l_obj = new ObjectDefinition();
  }//end importMDLFromJSONData()

}//end class
