<?php
namespace GOM\Core\Data;

use GOM\Core\Data\ObjectDefinition;
use GOM\Core\Data\ObjectInstance;

/**
 * Classe abstraite GOMBusinessObject
 *
 * Instance d'un objet métier
 */
class GOMBusinessObject extends Internal\GOMObject
{
  /**
   * Constructeur par défaut
   *
   * @param string $psTID TID de l'OBI.
   */
  public function __construct(string $psTID=NULL,string $psTablename){
    parent::__construct($psTID, $psTablename);
  }//end __construct()

  /**
   * Surcharge de l'enregistrement par défaut
   *
   * @internal  Ajout du rafraissement du réferentiel interne A000_OBI
   */
  public function saveObject()
  {
    parent::saveObject();
    \GOM\Core\DatabaseManager::refreshStatisticsForTable('A000_OBI');
  }//end saveObject()

  /**
   * Retourne L'OBD de l'objet
   *
   * @return \GOM\Core\Data\ObjectDefinition
   */
  public function getObjectDefinition()
  {
    $objInst = \GOM\Core\DataFactory::getObjectInstance($this->getTID());
    return \GOM\Core\DataFactory::getObjectDefinition($objInst->getFieldValueFromName('ObjectDefTID'));;
  }//end getObjectDefinition()

  /**
   * Retourne le BID de l'objet
   *
   * @return string
   */
  public abstract function getBID() : string;

}//end class
