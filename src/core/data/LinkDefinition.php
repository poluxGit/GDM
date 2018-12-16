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
  public function __construct(string $psTID){
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
    $this->addFieldDefinition('Type', 'LNKD_TYPE', 'string', 'Type d\'objet');
    $this->addFieldDefinition('ObjectDefSrcTID', 'OBD_TID_SOURCE', 'string', 'TID de l\'OBD Source');
    $this->addFieldDefinition('ObjectDefTrgTID', 'OBD_TID_TARGET', 'string', 'Nb caractère du cpt numérique');
    $this->addFieldDefinition('JSONData', 'JSON_DATA', 'string', 'Données complémentaires');
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
}//end class
