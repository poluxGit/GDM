<?php
namespace GOM\Core;

use GOM\Core\Internal\Exception\ObjectNotFoundException;

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
   * Retourne l'objet dont le TID est passé en argument
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID  TID de l'objet à charger
   * @return Data\Internal\GOMObject
   */
  static function getObject($psTID){

    $lobj = NULL;
    try {
        // TODO To dev
      $lobj = new Data\ObjectDefinition($psTID);
      $lobj->loadObject();


    } catch (\Exception $e) {
      $lsMsgException = sprintf("Une erreur est survenue durant le chargement de l'objet TID : '%s'.", $psTID);
      throw new \Exception($lsMsgException);
    }

    return $lobj;
  }//end loadObject()

  /**
   * Retourne l'OBD dont le TID est passé en argument
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID  TID de l'OBD à charger
   * @return Data\ObjectDefinition
   */
  static function getObjectDefinition($psTID){

    $lobj = NULL;
    try {
      $lobj = new Data\ObjectDefinition($psTID);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Définition d'objet",$psTID);
    }
    return $lobj;
  }//end getObjectDefinition()

  /**
   * Retourne le model dont le TID est passé en argument
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID  TID du model à charger
   * @return Data\Model
   */
  static function getModel($psTID){

    $lobj = NULL;
    try {
      $lobj = new Data\Model($psTID);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Model",$psTID);
    }
    return $lobj;
  }//end getModel()

  /**
   * Retourne la définition de liens
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID  TID du lien à charger
   * @return Data\LinkDefinition
   */
  static function getLinkDefinition($psTID){

    $lobj = NULL;
    try {
      $lobj = new Data\LinkDefinition($psTID);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Définition de lien",$psTID);
    }
    return $lobj;
  }//end getLinkDefinition()

  /**
   * Retourne la définition de la metadonnées de liens
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID  TID de la metadonnées de liens à charger
   * @return Data\LinkMetaDefinition
   */
  static function getLinkMetaDefinition($psTID){

    $lobj = NULL;
    try {
      $lobj = new Data\LinkMetaDefinition($psTID);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Définition de metadonnées de lien",$psTID);
    }
    return $lobj;
  }//end getLinkMetaDefinition()

  /**
   * Retourne la définition de la metadonnées d'objet
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID  TID de la metadonnées de liens à charger
   * @return Data\ObjectMetaDefinition
   */
  static function getObjectMetaDefinition($psTID){

    $lobj = NULL;
    try {
      $lobj = new Data\ObjectMetaDefinition($psTID);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Définition de metadonnées d'objet",$psTID);
    }
    return $lobj;
  }//end getObjectMetaDefinition()

}//end class
