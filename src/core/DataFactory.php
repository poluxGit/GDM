<?php
namespace GOM\Core;

/**
 * DataFactory - Classe statique de gestion des données
 *
 */
class DataFactory
{
  /**
   * @var \PDO
   */
  static $_oDbPDOHandler = null;

  /**
   * Définie l'objet PDO de connexion à la base de données
   */
  static function setPDOConnection($poPDO)
  {
    self::$_oDbPDOHandler = $poPDO;
  }//end setPDOConnection()

  /**
   * Import d'un modèle de données depuis une donnée au format JSON
   *
   * @param json $psJSONData   Données JSON du modèle à importer
   * @static
   */
  static function importModelFromJSONData($psJSONData)
  {
    //XXX self::initDBConnection();
    $l_obj = new Data\ObjectDefinition();
  }//end importMDLFromJSONData()

  /**
   * Retourne l'objet dont le TID est passé en argument
   *
   * @param string $psTID  TID de l'objet à charger
   * @return Data\Internal\GOMObject
   */
  static function getObject($psTID){

    $l_obj = NULL;
    try {
        // TODO To dev
      $l_obj = new Data\ObjectDefinition($psTID);
      $l_obj->loadObject();


    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de l'objet TID : '%s'.", $psTID);
      throw new \Exception($l_sMsgException);
    }

    return $l_obj;
  }//end loadObject()

  /**
   * Retourne l'OBD dont le TID est passé en argument
   *
   * @param string $psTID  TID de l'OBD à charger
   * @return Data\ObjectDefinition
   */
  static function getObjectDefinition($psTID){

    $l_obj = NULL;
    try {
      $l_obj = new Data\ObjectDefinition($psTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de l'OBD de TID : '%s'.", $psTID);
      throw new \Exception($l_sMsgException);
    }

    return $l_obj;
  }//end getObjectDefinition()

  /**
   * Retourne le model dont le TID est passé en argument
   *
   * @param string $psTID  TID du model à charger
   * @return Data\Model
   */
  static function getModel($psTID){

    $l_obj = NULL;
    try {
      $l_obj = new Data\Model($psTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement du model (TID : '%s').", $psTID);
      throw new \Exception($l_sMsgException);
    }
    return $l_obj;
  }//end getModel()

  /**
   * Retourne la définition de liens
   *
   * @param string $psTID  TID du lien à charger
   * @return Data\LinkDefinition
   */
  static function getLinkDefinition($psTID){

    $l_obj = NULL;
    try {
      $l_obj = new Data\LinkDefinition($psTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de la définition de liens (TID : '%s').", $psTID);
      throw new \Exception($l_sMsgException);
    }
    return $l_obj;
  }//end getLinkDefinition()

  /**
   * Retourne la définition de la metadonnées de liens
   *
   * @param string $psTID  TID de la metadonnées de liens à charger
   * @return Data\LinkMetaDefinition
   */
  static function getLinkMetaDefinition($psTID){

    $l_obj = NULL;
    try {
      $l_obj = new Data\LinkMetaDefinition($psTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de la définition de metadonnées de liens (TID : '%s').", $psTID);
      throw new \Exception($l_sMsgException);
    }
    return $l_obj;
  }//end getLinkMetaDefinition()

  /**
   * Retourne la définition de la metadonnées d'objet
   *
   * @param string $psTID  TID de la metadonnées de liens à charger
   * @return Data\ObjectMetaDefinition
   */
  static function getObjectMetaDefinition($psTID){

    $l_obj = NULL;
    try {
      $l_obj = new Data\ObjectMetaDefinition($psTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de la définition de metadonnées d'Objet' (TID : '%s').", $psTID);
      throw new \Exception($l_sMsgException);
    }
    return $l_obj;
  }//end getObjectMetaDefinition()

}//end class
