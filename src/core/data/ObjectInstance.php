<?php
namespace GOM\Core\Data;

use GOM\Core\Data\ObjectDefinition;

/**
 * Classe ObjectInstance
 *
 * Instance d'un objet métier
 */
class ObjectInstance extends Internal\GOMObject
{
  /**
   * Constructeur par défaut
   *
   * @param string $psTID TID de l'OBI.
   */
  public function __construct(string $psTID){
    parent::__construct($psTID, 'A100_OBI');
    $this->initFieldDefinition();
    $this->loadObject();
  }//end __construct()

  /**
   * Initialisation des définitions de champs de l'OBI
   *
   */
  public function initFieldDefinition()
  {
    $this->addFieldDefinition('ID', 'BID', 'string', 'Id. (BID)');
    $this->addFieldDefinition('ObjectDefTID', 'OBD_TID', 'string', 'OBD (TID)');
    $this->addFieldDefinition('DateCreation', 'CDATE', 'date', 'Date de création');
    $this->addFieldDefinition('DateMaj', 'UDATE', 'date', 'Date de dernière maj');
    $this->addFieldDefinition('UserCreation', 'CUSER', 'string', 'Compte Utilisateur du créateur');
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l\'updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
  }//end initFieldDefinition()

  /**
   * Retourne L'OBD de l'objet
   *
   * @return ObjectDefinition
   */
  public function getObjectDefinition()
  {
    $loObjDef = new ObjectDefinition($this->getFieldValueFromName('ObjectDefTID'));
    $loObjDef->loadObject();
    return $loObjDef;
  }//end getObjectDefinition()
}//end class
