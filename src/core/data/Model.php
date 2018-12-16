<?php

namespace GOM\Core\Data;

/**
 * Classe GOMModel - Modele de données interne
 *
 */
class Model extends Internal\GOMObject
{
  /**
   * Constructeur par défaut
   */
  public function __construct(string $p_sTID){
    parent::__construct($p_sTID,'A000_MDL');
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
    $this->addFieldDefinition('CodeBID', 'BIDCODE', 'string', 'Code appliqué au BID');
    $this->addFieldDefinition('Version', 'VERSION', 'string', 'Version du model');
    $this->addFieldDefinition('TitreCourt', 'STITLE', 'string', 'Titre Court');
    $this->addFieldDefinition('TitreLong', 'LTITLE', 'string', 'Titre Long');
    $this->addFieldDefinition('Commentaire', 'COMMENT', 'string', 'Commentaire divers');
    $this->addFieldDefinition('JSONData', 'JSON_DATA', 'string', 'Données complémentaires');
    $this->addFieldDefinition('DateCreation', 'CDATE', 'date', 'Date de création');
    $this->addFieldDefinition('DateMaj', 'UDATE', 'date', 'Date de dernière maj');
    $this->addFieldDefinition('UserCreation', 'CUSER', 'string', 'Compte Utilisateur du créateur');
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l\'updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
  }//end initFieldDefinition()

  // ************************************************************************ //
  // METHODES STATIQUES
  // ************************************************************************ //

  /**
   * Création d'un nouveau modele en base de données
   *
   * @param string $p_sShortCode  Code court du model (2 car)
   * @param string $p_sBIDCode    Prefix utilisée dans les codes BID
   * @param string $p_sVersion    Version du modele
   * @param string $p_sShortTitle Titre Court
   * @param string $p_sLongTitle  (Optionnel) Titre Long
   * @param string $p_sComment    (Optionnel) Commentaires
   * @param string $p_sJSONData   (Optionnel) JSON Data
   */
  public static function createNewModel($p_sShortCode,$p_sBIDCode,$p_sVersion, $p_sShortTitle,$p_sLongTitle = NULL,$p_sComment = NULL,$p_sJSONData = NULL)
  {
    //SELECT DMA_createNewModel('E1','ECM', 'beta', 'Personal ECM', 'Gestion de documents personnel', NULL,NULL);
    try {
        // DB connection active ?
        if(self::$_oPDOCommonDBConnection === NULL)
        {
          // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
          $l_sMsgException = sprintf("La connexion à la base de données n'est pas définie.");
          throw new \Exception($l_sMsgException);
        } else {
          $l_sSQLQuery = sprintf("SELECT DMA_createNewModel(:MDL_SCODE, :MDL_BIDCODE, :MDL_VERSION, :MDL_STITLE, :MDL_LTITLE, :MDL_COMMENT, :MDL_JSONDATA);");
          $l_oPDOStat         = $this->_oPDODBConnection->prepare($l_sSQLQuery);

          $l_oPDOStat->bindValue(':MDL_SCODE',    $p_sShortCode,  \PDO::PARAM_STR);
          $l_oPDOStat->bindValue(':MDL_BIDCODE',  $p_sBIDCode,    \PDO::PARAM_STR);
          $l_oPDOStat->bindValue(':MDL_VERSION',  $p_sVersion,    \PDO::PARAM_STR);
          $l_oPDOStat->bindValue(':MDL_STITLE',   $p_sShortTitle, \PDO::PARAM_STR);
          $l_oPDOStat->bindValue(':MDL_LTITLE',   $p_sLongTitle,  \PDO::PARAM_STR);
          $l_oPDOStat->bindValue(':MDL_COMMENT',  $p_sComment,    \PDO::PARAM_STR);
          $l_oPDOStat->bindValue(':MDL_JSONDATA', $p_sJSONData,   \PDO::PARAM_STR);

          // Execution de la requete
          $l_oPDOStat->execute();
          $l_aResultat = $l_oPDOStat->fetchAll();

          // Aucun résultat ?
          if (count($l_aResultat)==0){
            // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
            $l_sMsgException = sprintf("La création du model a rencontré une erreur (Code : '%s').",$p_sShortCode);
            throw new \Exception($l_sMsgException);
          }

          return array_shift($l_aResultat);
        }
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } finally {
      // TODO To implement
    }
  }//end

}//end class
