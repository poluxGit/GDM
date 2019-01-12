<?php
namespace GOM\Core;

use GOM\Core\Internal\Exception\ObjectNotFoundException;
use GOM\Core\Data\Internal\GOMBusinessObject;

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
   * @param string $psTID   TID de l'objet à charger
   * @return \GOM\Core\Data\Internal\GOMBusinessObject
   */
  static function getBusinessObject($psTID)
  {
    // Instance d'objet depuis le référentiel interne.
    $objInst = self::getObjectInstance($psTID);
    $objDef  = self::getObjectDefinition($objInst->getFieldValueFromName('ObjectDefTID'));
    $sObjDefType    = $objDef->getFieldValueFromName('Type');
    $sObjDefDBTable = $objDef->getFieldValueFromName('DBTable');

    $lobj = NULL;

    if ($sObjDefType == 'Simple') {
      $lobj = self::getSimpleObjectInstance($psTID,$sObjDefDBTable);
    } elseif ($sObjDefType == 'Complex') {
        $lobj = self::getComplexObjectInstance($psTID,$sObjDefDBTable);.
    }
    return $lobj;
  }//end getBusinessObject()

  /**
   * Retourne l'objet dont le TID est passé en argument
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID   TID de l'objet à charger
   * @return \GOM\Core\Data\ComplexObjectInstance
   */
  static function getComplexObjectInstance($psTID,$psTablename){

    $lobj = NULL;
    try {
      $lobj = new \GOM\Core\Data\ComplexObjectInstance($psTID,$psTablename);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Instance d'objet OBI",$psTID);
    }
    return $lobj;
  }//end getComplexObjectInstance()

  /**
   * Retourne l'objet dont le TID est passé en argument
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID   TID de l'objet à charger
   * @return \GOM\Core\Data\SimpleObjectInstance
   */
  static function getSimpleObjectInstance($psTID,$psTablename){

    $lobj = NULL;
    try {
      $lobj = new \GOM\Core\Data\SimpleObjectInstance($psTID,$psTablename);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Instance d'objet OBI",$psTID);
    }

    return $lobj;
  }//end getSimpleObjectInstance()


  /**
   * Retourne l'objet dont le TID est passé en argument
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID   TID de l'objet à charger
   * @return \GOM\Core\Data\ObjectInstance
   */
  static function getObjectInstance($psTID){

    $lobj = NULL;
    try {
      $lobj = new \GOM\Core\Data\ObjectInstance($psTID);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Instance d'objet OBI",$psTID);
    }

    return $lobj;
  }//end getObjectInstance()

  /**
   * Retourne l'OBD dont le TID est passé en argument
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID  TID de l'OBD à charger
   * @return \GOM\Core\Data\ObjectDefinition
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
   * @return \GOM\Core\Data\Model
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
   * @return \GOM\Core\Data\LinkDefinition
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
   * @return \GOM\Core\Data\LinkMetaDefinition
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
   * @return \GOM\Core\Data\ObjectMetaDefinition
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

  /**
   * Retourne l'instance de la metadonnées d'objet
   *
   * @throws GOM\Core\Internal\Exception\ObjectNotFoundException
   * @param string $psTID  TID de la metadonnées de liens à charger
   * @return \GOM\Core\Data\ObjectMetaInstance
   */
  static function getObjectMetaInstance($psTID){

    $lobj = NULL;
    try {
      $lobj = new Data\ObjectMetaInstance($psTID);
      $lobj->loadObject();
    } catch (\Exception $e) {
      throw new ObjectNotFoundException("Instance de metadonnées d'objet",$psTID);
    }
    return $lobj;
  }//end getObjectMetaInstance()

}//end class
