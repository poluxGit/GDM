<?php

namespace GOM\Core\Data;

/**
 * Définition d'Objet
 */
class ObjectDefinition extends Internal\GOMObject
{
  /**
   * Constructeur par défaut
   */
  public function __construct(string $p_sTID){
    parent::__construct($p_sTID,'A000_OBD');

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
    $this->addFieldDefinition('DBTable', 'OBI_DB_TABLENAME', 'string', 'Table de données');
    $this->addFieldDefinition('Type', 'OBD_TYPE', 'string', 'Type d\'objet');
    $this->addFieldDefinition('TIDLen', 'OBD_TID_NUMLEN', 'string', 'Nb caractère du cpt numérique');
    $this->addFieldDefinition('TIDPrefix', 'OBD_TID_SPREFIX', 'string', 'Prefix appliqué au TID');
    $this->addFieldDefinition('TIDPattern', 'OBI_TID_PATTERN', 'string', 'Pattern du TID');
    $this->addFieldDefinition('BIDPrefix', 'OBD_BID_PREFIX', 'string', 'Prefix du BID');
    $this->addFieldDefinition('BIDPattern', 'OBI_BID_PATTERN', 'string', 'Pattern du BID');
    $this->addFieldDefinition('DateCreation', 'CDATE', 'date', 'Date de création');
    $this->addFieldDefinition('DateMaj', 'UDATE', 'date', 'Date de dernière maj');
    $this->addFieldDefinition('UserCreation', 'CUSER', 'string', 'Compte Utilisateur du créateur');
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l\'updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
    $this->addFieldDefinition('EstSystem', 'IS_SYSTEM', 'INT', 'Flag system');
  }//end initFieldDefinition()
}//end class
