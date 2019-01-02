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
  public function __construct(string $psTID){
    parent::__construct($psTID, 'A000_OBD');

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

  /**
   * Retourne le tableau des TID des définitions de metadonnées de l'OBD
   *
   * @return array(ObjectMetaDefinition)
   */
  public function getObjectMetaDefinitions()
  {
    return ObjectMetaDefinition::getAllMetaDefinitionsForAnObjectDefinition($this->getTID());
  }//end getObjectMetaDefinitions()

  /**
   * Création d'une nouvelle définition d'objet
   *
   * @param string $psModelTID          TID du model
   * @param string $psBIDCode           Prefix utilisée dans les codes BID
   * @param string $psShortTitle        Titre Court
   * @param string $psLongTitle         Titre Long
   * @param string $psComment           Commentaires
   * @param string $psObjetType         Type Objet (Simple,Complex,Specific)
   * @param string $psShortTIDPrefix    Prefix TID
   * @param int    $piShortTIDPrefixLen Lg Prefix TID
   * @param string $psShortTIDPattern   Pattern TID
   * @param string $psShortBIDPrefix    Prefix BID
   * @param string $psShortBIDPattern   Pattern BID
   * @param string $psObjetTablename    Table
   */
  public static function createNewObjectDefinitionModel($psModelTID,
    $psBIDCode,
    $psShortTitle,
    $psLongTitle = NULL,
    $psComment = NULL,
    $psObjetType = 'Simple',
    $psShortTIDPrefix,
    $piShortTIDPrefixLen,
    $psShortTIDPattern,
    $psShortBIDPrefix,
    $psShortBIDPattern,
    $psObjetTablename
  )
  {
    //SELECT DMA_createNewModel('E1', 'ECM', 'beta', 'Personal ECM', 'Gestion de documents personnel', NULL, NULL);
    try {
        // DB connection active ?
        if(self::$_oPDOCommonDBConnection === NULL)
        {
          // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
          $lsMsgException = sprintf("La connexion à la base de données n'est pas définie.");
          throw new \Exception($lsMsgException);
        } else {
          $lsSQLQuery = sprintf(
            "SELECT DMA_createNewObjectDefinition(
              :MDL_TID ,
              :ODB_BIDCODE,
              :ODB_STITLE,
              :ODB_LTITLE,
              :ODB_COMMENT,
              :ODB_TYPE,
              :ODB_STIDPREFIX,
              :ODB_STIDPREFIXLEN,
              :ODB_STIDPATTERN,
              :ODB_SBIDPREFIX,
              :ODB_SBIDPATTERN,:ODB_TABLENAME);"
          );
          $loPDOStat  = self::$_oPDOCommonDBConnection->prepare($lsSQLQuery);

          $loPDOStat->bindValue(':MDL_TID', $psModelTID, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_BIDCODE', $psBIDCode, \PDO::PARAM_STR);
//          $loPDOStat->bindValue(':MDL_VERSION', $psVersion, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_STITLE', $psShortTitle, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_LTITLE', $psLongTitle, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_COMMENT', $psComment, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_TYPE', $psObjetType, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_STIDPREFIX', $psShortTIDPrefix, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_STIDPREFIXLEN', $piShortTIDPrefixLen, \PDO::PARAM_INT);
          $loPDOStat->bindValue(':ODB_STIDPATTERN', $psShortTIDPattern, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_SBIDPREFIX', $psShortBIDPrefix, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_SBIDPATTERN', $psShortBIDPattern, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':ODB_TABLENAME', $psObjetTablename, \PDO::PARAM_STR);

          // Execution de la requete
          $loPDOStat->execute();

          // Aucun résultat ?
          if ($loPDOStat->rowCount()==0) {
            $lsMsgException = sprintf("La création de la définition d'objet '%s' a rencontré une erreur technique.", $psBIDCode);
            throw new DatabaseSQLException($lsMsgException,$loPDOStat);
          }
          $laResultat = $loPDOStat->fetchAll();
          return array_shift($laResultat);
        }
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } finally {
      // TODO To implement
    }
  }//end createNewObjectDefinitionModel()

}//end class
