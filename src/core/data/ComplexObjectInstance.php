<?php
namespace GOM\Core\Data;

use GOM\Core\Data\ObjectInstance;
use GOM\Core\Data\ObjectDefinition;

/**
 * Classe ComplexObjectInstance
 *
 * Instance d'un objet métier complex
 */
class ComplexObjectInstance extends Internal\GOMBusinessObject
{

  private $OBI = NULL;

  /**
   * Constructeur par défaut
   *
   * @param string $psTID TID de l'OBI.
   */
  public function __construct($psTID=NULL,$psTablename=NULL)
  {
    if (is_null($psTID) && is_null($psTablename)) {
      throw new \Exception('Le TID et la table ne peuvent êre NULL en même temps.');
    }
    $oOBI = NULL;
    $lsTablename = $psTablename;
    if(!is_null($psTID))
    {
      $oOBI = \GOM\Core\DataFactory::getObjectInstance($psTID);
      $oOBD = $oOBI->getObjectDefinition();
      $lsTablename = $oOBD->getFieldValueFromName('DBTable');
    }

    parent::__construct($psTID, $lsTablename);
    $this->initFieldDefinition();

    if(!is_null($oOBI))
    {
      $this->OBI = $oOBI;
    }
  }//end __construct()

  /**
   * Initialisation des définitions de champs de l'OBI
   *
   */
  public function initFieldDefinition()
  {
    $this->addFieldDefinition('ID', 'BID', 'string', 'Id. (BID)');
    $this->addFieldDefinition('Version', 'VERS', 'integer', 'Version');
    $this->addFieldDefinition('Revision', 'REV', 'integer', 'Revision');
    $this->addFieldDefinition('TitreCourt', 'STITLE', 'string', 'Titre Court');
    $this->addFieldDefinition('TitreLong', 'LTITLE', 'string', 'Titre Long');
    $this->addFieldDefinition('Commentaire', 'COMMENT', 'string', 'Commentaire divers');
    $this->addFieldDefinition('JSONData', 'JSON_DATA', 'json', 'Données complémentaires');
    $this->addFieldDefinition('DateCreation', 'CDATE', 'date', 'Date de création');
    $this->addFieldDefinition('DateMaj', 'UDATE', 'date', 'Date de dernière maj');
    $this->addFieldDefinition('UserCreation', 'CUSER', 'string', 'Compte Utilisateur du créateur');
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
  }//end initFieldDefinition()

  /**
   * createNewObjectInstance - Retourne une nouvelle instance d'un objet
   *
   * @static
   * @internal Objet logique - non enregistrés en base de données   *
   * @param string $objDefTID TID du type de l'objet (OBD)
   *
   * @return GOM\Core\Data\ComplexObjectInstance
   */
  public static function createNewObjectInstance($objDefTID)
  {
    $loObjDef = \GOM\Core\DataFactory::getObjectDefinition($objDefTID);
    $oObjResult = new ComplexObjectInstance(NULL,$loObjDef->getFieldValueFromName('DBTable')) ;

    return $oObjResult;
  }//end createNewObjectInstance()

  /**
   * Retourne la nouvelle version de l'objet courant
   *
   * @return GOM\Core\Data\ComplexObjectInstance
   */
  public function createNextVersion()
  {
    // Variable de retour de la méthode
    $loNewObjVersion = null;
    //TODO createNextVersion
    //$lNewObj = new ComplexObjectInstance

    return $loNewObjVersion;
  }

  /**
   * Retourne la nouvelle version de l'objet courant
   *
   * @return GOM\Core\Data\ComplexObjectInstance
   */
  public function createNextRevision()
  {
    //TODO createNextRevision
  }

  /**
   * Retourne le BID complet de l'objet complex
   *
   * @example BID : CAT-TEST, VERS = 1 , REV = 9 => CAT-TEST_01.09
   * @return string
   */
  public function getBID() : string
  {
    return $this->getFieldValueFromName('ID').'_'.sprintf('%02d',$this->getFieldValueFromName('Version').'.'.sprintf('%02d',$this->getFieldValueFromName('Revision'));
  }//end getBID()
}//end class
