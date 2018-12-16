<?php


use GOM\Data\Internal;

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
  static function setPDOConnection($p_oPDO)
  {
    self::$_oDbPDOHandler = $p_oPDO;
  }//end setPDOConnection()

  /**
   * Import d'un modèle de données depuis une donnée au format JSON
   *
   * @param json $p_sJSONData   Données JSON du modèle à importer
   * @static
   */
  static function importModelFromJSONData($p_sJSONData)
  {
    //XXX self::initDBConnection();
    $l_obj = new Data\Internal\GOMObjectDefinition();
  }//end importMDLFromJSONData()

  /**
   * Retourne l'objet dont le TID est passé en argument
   *
   * @param string $p_sTID  TID de l'objet à charger
   * @return Data\Internal\GOMObject
   */
  static function getObject($p_sTID){

    $l_obj = NULL;
    try {
        // TODO To dev
      $l_obj = new Data\Internal\GOMObjectDefinition($p_sTID);
      $l_obj->loadObject();


    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de l'objet TID : '%s'.",$p_sTID);
      throw new \Exception($l_sMsgException);
    }

    return $l_obj;
  }//end loadObject()

  /**
   * Retourne l'OBD dont le TID est passé en argument
   *
   * @param string $p_sTID  TID de l'OBD à charger
   * @return Data\Internal\GOMObjectDefinition
   */
  static function getObjectDefinition($p_sTID){

    $l_obj = NULL;
    try {
      $l_obj = new Internal\GOMObjectDefinition($p_sTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de l'OBD de TID : '%s'.",$p_sTID);
      throw new \Exception($l_sMsgException);
    }

    return $l_obj;
  }//end getObjectDefinition()

  /**
   * Retourne le model dont le TID est passé en argument
   *
   * @param string $p_sTID  TID du model à charger
   * @return Data\Internal\GOMModel
   */
  static function getModel($p_sTID){

    $l_obj = NULL;
    try {
      $l_obj = new Internal\GOMModel($p_sTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement du model (TID : '%s').",$p_sTID);
      throw new \Exception($l_sMsgException);
    }
    return $l_obj;
  }//end getModel()

  /**
   * Retourne la définition de liens
   *
   * @param string $p_sTID  TID du lien à charger
   * @return Data\Internal\GOMLinkDefinition
   */
  static function getLinkDefinition($p_sTID){

    $l_obj = NULL;
    try {
      $l_obj = new Internal\GOMLinkDefinition($p_sTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de la définition de liens (TID : '%s').",$p_sTID);
      throw new \Exception($l_sMsgException);
    }
    return $l_obj;
  }//end getLinkDefinition()

  /**
   * Retourne la définition de la metadonnées de liens
   *
   * @param string $p_sTID  TID de la metadonnées de liens à charger
   * @return Data\Internal\GOMLinkMetaDefinition
   */
  static function getLinkMetaDefinition($p_sTID){

    $l_obj = NULL;
    try {
      $l_obj = new Internal\GOMLinkMetaDefinition($p_sTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de la définition de metadonnées de liens (TID : '%s').",$p_sTID);
      throw new \Exception($l_sMsgException);
    }
    return $l_obj;
  }//end getLinkMetaDefinition()

  /**
   * Retourne la définition de la metadonnées d'objet
   *
   * @param string $p_sTID  TID de la metadonnées de liens à charger
   * @return Data\Internal\GOMObjectMetaDefinition
   */
  static function getObjectMetaDefinition($p_sTID){

    $l_obj = NULL;
    try {
      $l_obj = new Internal\GOMObjectMetaDefinition($p_sTID);
      $l_obj->loadObject();
    } catch (\Exception $e) {
      $l_sMsgException = sprintf("Une erreur est survenue durant le chargement de la définition de metadonnées d'Objet' (TID : '%s').",$p_sTID);
      throw new \Exception($l_sMsgException);
    }
    return $l_obj;
  }//end getObjectMetaDefinition()

}//end class
 ?>
