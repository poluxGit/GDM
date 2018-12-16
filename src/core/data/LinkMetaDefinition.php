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
  public function __construct(string $psTID){
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
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l\'updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
    $this->addFieldDefinition('EstPropage', 'IS_PROPAGATED', 'INT', 'Flag de propagation');
  }//end initFieldDefinition()
}//end class
