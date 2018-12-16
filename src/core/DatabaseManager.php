<?php
namespace GOM\Core;

use GOM\Core\Internal\Exception;

/**
 * Classe statique DatabaseManager
 *
 * Gestion des données dans la base de données
 *
 */
class DatabaseManager
{
  /**
   * Database Handler
   *
   * @var \PDO
   */
  static $_oDbPDOHandler = null;

  /**
   * Définie l'objet PDO de connexion à la base de données
   *
   * @static
   */
  static function setPDOConnection($poPDO)
  {
    self::$_oDbPDOHandler = $poPDO;
  }//end setPDOConnection()

  /**
   * getAllRows
   *
   * Retourne les enregistrements remontés par la requête SQL.
   *
   * @static
   * @param string  $psSQLSelectQuery   Requete SQL à executer & fetcher
   * @param int     $piPDOFetchMode     (Optionel) Mode de FETCH PDO (defaut: FETCH_ASSOC)
   * @return array  Tableau des résultats
   */
  static function getAllRows($psSQLSelectQuery, $piPDOFetchMode=null) : array
  {
    $laResults = [];

    try {
        // DB connection active ?
        if (self::$_oPDODBConnection === NULL) {
          $lsMsgException = sprintf("La connexion à la base de données n'est pas définie.");
          throw new \DatabaseException('DB-PDO_CONNECTION-KO',$lsMsgException);
        } else {

          $loPDOStat = $this->_oPDODBConnection->prepare($psSQLSelectQuery);

          // Execution de la requete
          $loPDOStat->execute();
          $liFetchMode = \PDO::FETCH_ASSOC;
          if ($piPDOFetchMode !== NULL) {
            $liFetchMode = $piPDOFetchMode;
          }
          $laResults = $loPDOStat->fetchAll($liFetchMode);
        }
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } finally {
      // TODO To implement
    }
    return $laResults;
  }//end getAllRows()

  /**
   * getFirstRowOnly
   *
   * Retourne le premier enregsitrement renvoyé par la requete SQL
   *
   * @static
   * @param string  $psSQLSelectQuery   Requete SQL à executer & fetcher
   * @param int     $piPDOFetchMode     (Optionel) Mode de FETCH PDO (defaut: FETCH_ASSOC)
   * @return array  Tableau du résultat
   */
  static function getFirstRowOnly($psSQLSelectQuery, $piPDOFetchMode=null) : array
  {
    $laResults = [];
    $laFinalRow = null;

    try {
        // DB connection active ?
        if (self::$_oPDODBConnection === NULL) {
          $lsMsgException = sprintf("La connexion à la base de données n'est pas définie.");
          throw new \DatabaseException('DB-PDO_CONNECTION-KO',$lsMsgException);
        } else {

          $loPDOStat = $this->_oPDODBConnection->prepare($psSQLSelectQuery);

          // Execution de la requete
          $loPDOStat->execute();
          $liFetchMode = \PDO::FETCH_ASSOC;
          if ($piPDOFetchMode !== NULL) {
            $liFetchMode = $piPDOFetchMode;
          }
          $laResults = $loPDOStat->fetchAll($liFetchMode);
        }
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } finally {
      // TODO To implement
    }

    if (count($laResults) > 0) {
      $laFinalRow = array_shift($laResults);
    }

    return $laFinalRow;
  }//end getAllRows()


}//end class
