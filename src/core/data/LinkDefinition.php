<?php

namespace GOM\Core\Data;

/**
 * Définition de lien entre Objet
 */
class LinkDefinition extends Internal\GOMObject
{
  /**
   * Constructeur par défaut
   */
  public function __construct(string $psTID=NULL){
    parent::__construct($psTID, 'A000_LNKD');
    $this->initFieldDefinition();
  }//end __construct()

  /**
   * Initialisation des définitions de champs de l'objet
   *
   */
  public function initFieldDefinition()
  {
    $this->addFieldDefinition('ID', 'BID', 'string', 'Id. (BID)');
    $this->addFieldDefinition('MODEL', 'MDL_TID', 'string', 'Modèle (TID)');
    $this->addFieldDefinition('TitreCourt', 'STITLE', 'string', 'Titre Court');
    $this->addFieldDefinition('TitreLong', 'LTITLE', 'string', 'Titre Long');
    $this->addFieldDefinition('Commentaire', 'COMMENT', 'string', 'Commentaire divers');
    $this->addFieldDefinition('Type', 'LNKD_TYPE', 'string', 'Type de lien ENUM(\'OneToOne\', \'OneToAny\', \'AnyToAny\')');
    $this->addFieldDefinition('ObjectDefSrcTID', 'OBD_TID_SOURCE', 'string', 'TID de l\'OBD Source');
    $this->addFieldDefinition('ObjectDefTrgTID', 'OBD_TID_TARGET', 'string', 'TID de l\'OBD Cible');
    $this->addFieldDefinition('JSONData', 'JSON_DATA', 'string', 'Données complémentaires JSON');
    $this->addFieldDefinition('DateCreation', 'CDATE', 'date', 'Date de création');
    $this->addFieldDefinition('DateMaj', 'UDATE', 'date', 'Date de dernière maj');
    $this->addFieldDefinition('UserCreation', 'CUSER', 'string', 'Compte Utilisateur du créateur');
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l\'updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
    $this->addFieldDefinition('EstPropage', 'IS_PROPAGATED', 'INT', 'Flag de propagation');
  }//end initFieldDefinition()

  /**
   * Retourne le tableau des TID des définitions de metadonnées du LNKD
   *
   * @return array(LinkMetaDefinition)
   */
  public function getLinkMetaDefinitions()
  {
    return LinkMetaDefinition::getAllMetaDefinitionsForALinkDefinition($this->getTID());
  }//end getLinkMetaDefinitions()

  /**
   * createNewLinkDefinitionModel
   *
   * Création d'une nouvelle définition de lien entre objet
   *
   * @param string $psModelTID          TID du model cible
   * @param string $psBIDCode           Prefix utilisée dans les codes BID
   * @param string $psShortTitle        Titre Court
   * @param string $psLongTitle         Titre Long
   * @param string $psComment           Commentaires
   * @param string $psLinkType          Type Objet ENUM('OneToOne', 'OneToAny', 'AnyToAny')
   * @param string $psTIDSourceOBD      TID Objet Source du lien
   * @param string $psTIDTargetOBD      TID Objet Cible du lien
   * @param string $psJSONData          JSON Additional Data
   */
  public static function createNewLinkDefinitionModel($psModelTID,
    $psBIDCode,
    $psShortTitle,
    $psLongTitle = NULL,
    $psComment = NULL,
    $psLinkType = 'OneToOne',
    $psTIDSourceOBD,
    $psTIDTargetOBD,
    $psJSONData
  )
  {
    // Création de l'objet en mémoire!
    $objLnkDefinition = new LinkDefinition();

    $objLnkDefinition->setFieldValueFromSQLName('MDL_TID',$psModelTID);
    $objLnkDefinition->setFieldValueFromSQLName('BID',$psBIDCode);
    $objLnkDefinition->setFieldValueFromSQLName('STITLE',$psShortTitle);
    $objLnkDefinition->setFieldValueFromSQLName('LTITLE',$psLongTitle);
    $objLnkDefinition->setFieldValueFromSQLName('COMMENT',$psComment);
    $objLnkDefinition->setFieldValueFromSQLName('LNKD_TYPE',$psLinkType);
    $objLnkDefinition->setFieldValueFromSQLName('OBD_TID_SOURCE',$psTIDSourceOBD);
    $objLnkDefinition->setFieldValueFromSQLName('OBD_TID_TARGET',$psTIDTargetOBD);
    $objLnkDefinition->setFieldValueFromSQLName('JSON_DATA',$psJSONData);

    // Création en base de données!
    $objLnkDefinition->saveObject();

    // Maj des statistics !
    \GOM\Core\DatabaseManager::refreshStatisticsForTable('A000_LNKD');
    \GOM\Core\DatabaseManager::refreshStatisticsForLogsTable();

    return $objLnkDefinition->getTID();

  }//end createNewLinkDefinitionModel()


}//end class
