<?php

namespace GOM\Core\Data;

/**
 * Définition de metadonnées d'Objet
 */
class ObjectMetaDefinition extends Internal\GOMObject
{
  /**
   * Constructeur par défaut
   */
  public function __construct(string $psTID){
    parent::__construct($psTID, 'A000_OBMD');
    $this->initFieldDefinition();
  }//end __construct()

  /**
   * Initialisation des définitions de champs de l'objet
   *
   */
  public function initFieldDefinition()
  {
    $this->addFieldDefinition('ID', 'BID', 'string', 'Id. (BID)');
    $this->addFieldDefinition('ObjectDefTID', 'OBD_TID', 'string', 'OBD (TID)');
    $this->addFieldDefinition('TitreCourt', 'STITLE', 'string', 'Titre Court');
    $this->addFieldDefinition('TitreLong', 'LTITLE', 'string', 'Titre Long');
    $this->addFieldDefinition('Commentaire', 'COMMENT', 'string', 'Commentaire divers');
    $this->addFieldDefinition('DataType', 'OBMD_DATA_TYPE', 'string', 'Type de la metadonnées');
    $this->addFieldDefinition('DataPattern', 'OBMD_DATA_PATTERN', 'string', 'Pattern de la metadonnées');
    $this->addFieldDefinition('TIDPattern', 'OBMI_TID_PATTERN', 'string', 'Pattern du TID');
    $this->addFieldDefinition('BIDPattern', 'OBMI_BID_PATTERN', 'string', 'Pattern du BID');
    $this->addFieldDefinition('JSONData', 'JSON_DATA', 'string', 'Données complémentaires');
    $this->addFieldDefinition('DateCreation', 'CDATE', 'date', 'Date de création');
    $this->addFieldDefinition('DateMaj', 'UDATE', 'date', 'Date de dernière maj');
    $this->addFieldDefinition('UserCreation', 'CUSER', 'string', 'Compte Utilisateur du créateur');
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l\'updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
    $this->addFieldDefinition('EstSystem', 'IS_SYSTEM', 'INT', 'Flag system');
    $this->addFieldDefinition('EstMulitple', 'IS_MULTIPLE', 'INT', 'Flag Multiple');
  }//end initFieldDefinition()

  /**
   * getAllMetaDefinitionsForAnObjectDefinition
   *
   * Retourne la liste des TID des définition des metadonnées liées à
   * la définition d'objet.
   *
   * @param string $psTIDObjectDefinition   TID de la définition de l'objet.
   * @return array  Tableau des TID des définition des metadonnées trouvées
   */
  public static function getAllMetaDefinitionsForAnObjectDefinition($psTIDObjectDefinition)
  {
    return self::searchObjectFromSQLConditions(["OBD_TID = '$psTIDObjectDefinition'"],'A000_OBMD');
  }//end getAllMetaDefinitionsForAnObjectDefinition()
}//end class
