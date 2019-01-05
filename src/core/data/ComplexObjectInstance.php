<?php
namespace GOM\Core\Data;

use GOM\Core\Data\ObjectInstance;
use GOM\Core\Data\ObjectDefinition;

/**
 * Classe ComplexObjectInstance
 *
 * Instance d'un objet métier complex
 */
class ComplexObjectInstance extends Internal\GOMObject
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
      $oOBI = new ObjectInstance($psTID);
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
   * Creation en mémoire d'une instance d'un objet complex (non enregistrées en base)
   *
   */
  public static function createNewComplexObjectInstance($objDefTID)
  {
    $loObjDef = new ObjectDefinition($objDefTID);
    $loObjDef->loadObject();
    $oObjResult = new ComplexObjectInstance(NULL,$loObjDef->getFieldValueFromName('DBTable')) ;

    return $oObjResult;
  }//end createNewComplexObjectInstance()


  public function createNextVersion()
  {
    //TODO createNextVersion
  }

  public function createNextRevision()
  {
    //TODO createNextRevision
  }
}//end class
