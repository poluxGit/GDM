<?php

namespace GOM\Core\Data;

use GOM\Core\Internal\Exception\DatabaseSQLException;

/**
 * Classe GOMModel - Modele de données interne
 *
 */
class Model extends Internal\GOMObject
{
  /**
   * Constructeur par défaut
   */
  public function __construct(string $psTID)
  {
    parent::__construct($psTID, 'A000_MDL');
    $this->initFieldDefinition();
  }//end __construct()

  /**
   * Initialisation des définitions de champs de l'objet
   *
   */
  public function initFieldDefinition()
  {
    $this->addFieldDefinition('ID', 'BID', 'string', 'Id. (BID)');
    $this->addFieldDefinition('CODE', 'CODE', 'string', 'Code du model');
    $this->addFieldDefinition(
        'CodeBID',
        'BIDCODE',
        'string',
        'Code appliqué au BID'
    );
    $this->addFieldDefinition(
        'Version',
        'VERSION',
        'string',
        'Version du model'
    );
    $this->addFieldDefinition(
        'TitreCourt',
        'STITLE',
        'string',
        'Titre Court'
    );
    $this->addFieldDefinition('TitreLong', 'LTITLE', 'string', 'Titre Long');
    $this->addFieldDefinition(
        'Commentaire',
        'COMMENT',
        'string',
        'Commentaire divers'
    );
    $this->addFieldDefinition(
        'JSONData',
        'JSON_DATA',
        'string',
        'Données complémentaires'
    );
    $this->addFieldDefinition(
        'DateCreation',
        'CDATE',
        'date',
        'Date de création'
    );
    $this->addFieldDefinition(
        'DateMaj',
        'UDATE',
        'date',
        'Date de dernière maj'
    );
    $this->addFieldDefinition(
        'UserCreation',
        'CUSER',
        'string',
        'Compte Utilisateur du créateur'
    );
    $this->addFieldDefinition(
        'UserMaj',
        'UUSER',
        'string',
        'Compte Utilisateur de l\'updateur'
    );
    $this->addFieldDefinition(
        'EstSupprime',
        'IS_DELETED',
        'INT',
        'Flag de suppression'
    );
  }//end initFieldDefinition()

  // ************************************************************************ //
  // METHODES STATIQUES
  // ************************************************************************ //

  /**
   * Création d'un nouveau modele en base de données
   *
   * @param string $psShortCode  Code court du model (2 car)
   * @param string $psBIDCode    Prefix utilisée dans les codes BID
   * @param string $psVersion    Version du modele
   * @param string $psShortTitle Titre Court
   * @param string $psLongTitle  (Optionnel) Titre Long
   * @param string $psComment    (Optionnel) Commentaires
   * @param string $psJSONData   (Optionnel) JSON Data
   */
  public static function createNewModel($psShortCode,
    $psBIDCode,
    $psVersion,
    $psShortTitle,
    $psLongTitle = NULL,
    $psComment = NULL,
    $psJSONData = NULL
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
            "SELECT DMA_createNewModel(:MDL_SCODE, :MDL_BIDCODE, :MDL_VERSION, :MDL_STITLE, :MDL_LTITLE, :MDL_COMMENT, :MDL_JSONDATA);"
          );
          $loPDOStat  = self::$_oPDOCommonDBConnection->prepare($lsSQLQuery);

          $loPDOStat->bindValue(':MDL_SCODE', $psShortCode, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':MDL_BIDCODE', $psBIDCode, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':MDL_VERSION', $psVersion, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':MDL_STITLE', $psShortTitle, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':MDL_LTITLE', $psLongTitle, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':MDL_COMMENT', $psComment, \PDO::PARAM_STR);
          $loPDOStat->bindValue(':MDL_JSONDATA', $psJSONData, \PDO::PARAM_STR);

          // Execution de la requete
          $loPDOStat->execute();
          $laResultat = $loPDOStat->fetchAll();

          // Aucun résultat ?
          if (count($laResultat)==0) {
            $lsMsgException = sprintf("La création du model '%s' a rencontré une erreur technique.", $psShortCode);
            throw new DatabaseSQLException($lsMsgException,$loPDOStat);
          }
          return array_shift($laResultat);
        }
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } finally {
      // TODO To implement
    }
  }//end

}//end class
