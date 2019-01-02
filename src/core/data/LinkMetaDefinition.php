<?php

namespace GOM\Core\Data;

/**
 * Définition de metadonnées de lien entre Objet
 */
class LinkMetaDefinition extends Internal\GOMObject
{

  /**
   * Constructeur par défaut
   */
  public function __construct(string $psTID=NULL){
    parent::__construct($psTID, 'A000_LNKMD');
    $this->initFieldDefinition();
  }//end __construct()

  /**
   * Initialisation des définitions de champs de l'objet
   *
   */
  public function initFieldDefinition()
  {
    $this->addFieldDefinition('ID', 'BID', 'string', 'Id. (BID)');
    $this->addFieldDefinition('LinkDefTID', 'LNKD_TID', 'string', 'LNKD (TID)');
    $this->addFieldDefinition('TitreCourt', 'STITLE', 'string', 'Titre Court');
    $this->addFieldDefinition('TitreLong', 'LTITLE', 'string', 'Titre Long');
    $this->addFieldDefinition('Commentaire', 'COMMENT', 'string', 'Commentaire divers');
    $this->addFieldDefinition('DataType', 'LNKMD_DATA_TYPE', 'string', 'Type de la metadonnées');
    $this->addFieldDefinition('DataPattern', 'LNKMD_DATA_PATTERN', 'string', 'Pattern de la metadonnées');
    $this->addFieldDefinition('TIDPattern', 'LNKMI_TID_PATTERN', 'string', 'Pattern du TID');
    $this->addFieldDefinition('BIDPattern', 'LNKMI_BID_PATTERN', 'string', 'Pattern du BID');
    $this->addFieldDefinition('JSONData', 'JSON_DATA', 'string', 'Données complémentaires');
    $this->addFieldDefinition('DateCreation', 'CDATE', 'date', 'Date de création');
    $this->addFieldDefinition('DateMaj', 'UDATE', 'date', 'Date de dernière maj');
    $this->addFieldDefinition('UserCreation', 'CUSER', 'string', 'Compte Utilisateur du créateur');
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
    $this->addFieldDefinition('EstPropage', 'IS_PROPAGATED', 'INT', 'Flag de propagation');
  }//end initFieldDefinition()

  /**
   * getAllMetaDefinitionsForALinkDefinition
   *
   * Retourne la liste des TID des définition des metadonnées liées à
   * la définition de lien.
   *
   * @param string $psTIDLinkDefinition   TID de la définition de lien
   * @return array  Tableau des TID des définition des metadonnées trouvées
   */
  public static function getAllMetaDefinitionsForALinkDefinition($psTIDLinkDefinition)
  {
    return self::searchObjectFromSQLConditions(["LNKD_TID = '$psTIDObjectDefinition'"],'A000_LNKMD');
  }//end getAllMetaDefinitionsForALinkDefinition()

  /**
   * createNewMetaLinkDefinition
   *
   * Création d'une nouvelle définition de metadonnées de lien entre objets
   *
   * @param string $psLnkDefTID               TID de la définition de lien entre objet
   * @param string $psBIDCode                 Prefix utilisée dans les codes BID
   * @param string $psShortTitle              Titre Court
   * @param string $psLongTitle               Titre Long
   * @param string $psComment                 Commentaires
   * @param string $psMetaLnkDefDataType      Type d'attribut sur lien ENUM('String', 'Date', 'Datetime', 'Integer', 'Real')
   * @param string $psMetaLnkDefDataPattern   Data Pattern sur meta de lien
   * @param string $psMetaLnkInstTIDPattern   TID Pattern instance de  meta de lien
   * @param string $psMetaLnkInstBIDPattern   BID Pattern instance de  meta de lien
   * @param string $psJSONData                JSON Additional Data
   */
  public static function createNewMetaLinkDefinition($psLnkDefTID,
    $psBIDCode,
    $psShortTitle,
    $psLongTitle = NULL,
    $psComment = NULL,
    $psMetaLnkDefDataType = 'String',
    $psMetaLnkDefDataPattern,
    $psMetaLnkInstTIDPattern,
    $psMetaLnkInstBIDPattern,
    $psJSONData
  )
  {

    // Création de l'objet en mémoire!
    $objMetaLnkDefinition = new LinkMetaDefinition();

    $objMetaLnkDefinition->setFieldValueFromSQLName('LNKD_TID',$psLnkDefTID);
    $objMetaLnkDefinition->setFieldValueFromSQLName('BID',$psBIDCode);
    $objMetaLnkDefinition->setFieldValueFromSQLName('STITLE',$psShortTitle);
    $objMetaLnkDefinition->setFieldValueFromSQLName('LTITLE',$psLongTitle);
    $objMetaLnkDefinition->setFieldValueFromSQLName('LNKMD_DATA_TYPE',$psMetaLnkDefDataType);
    $objMetaLnkDefinition->setFieldValueFromSQLName('LNKMD_DATA_PATTERN',$psMetaLnkDefDataPattern);
    $objMetaLnkDefinition->setFieldValueFromSQLName('LNKMI_TID_PATTERN',$psMetaLnkInstTIDPattern);
    $objMetaLnkDefinition->setFieldValueFromSQLName('LNKMI_BID_PATTERN',$psMetaLnkInstBIDPattern);
    $objMetaLnkDefinition->setFieldValueFromSQLName('COMMENT',$psComment);
    $objMetaLnkDefinition->setFieldValueFromSQLName('JSON_DATA',$psJSONData);

    // Création en base de données!
    $objMetaLnkDefinition->saveObject();

    // Maj des statistics !
    \GOM\Core\DatabaseManager::refreshStatisticsForTable('A000_LNKMD');
    \GOM\Core\DatabaseManager::refreshStatisticsForLogsTable();

  }//end createNewMetaLinkDefinition()

}//end class
