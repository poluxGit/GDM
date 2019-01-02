<?php
namespace GOM\Core;

use GOM\Core\Internal\Exception as Exceptions;

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
        if (self::$_oDbPDOHandler === NULL) {
          $lsMsgException = sprintf("Database connection not defined.");
          throw new Exceptions\DatabaseException($lsMsgException);
        } else {

          $loPDOStat = self::$_oDbPDOHandler->prepare($psSQLSelectQuery);

          // Execution de la requete
          $loPDOStat->execute();
          $liFetchMode = \PDO::FETCH_ASSOC;
          if ($piPDOFetchMode !== NULL) {
            $liFetchMode = $piPDOFetchMode;
          }
          $laResults = $loPDOStat->fetchAll($liFetchMode);
        }
    } catch (\Exception $e) {
      $lsMsgException = sprintf(
          "An error occured during database querying (getAllRows) : %s.",
          $e->getMessage()
        );
      throw new Exceptions\DatabaseException($lsMsgException);
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
        if (self::$_oDbPDOHandler === NULL) {
          $lsMsgException = sprintf("Database connection not defined.");
          throw new Exceptions\DatabaseException($lsMsgException);
        } else {

          $loPDOStat = self::$_oDbPDOHandler->prepare($psSQLSelectQuery);

          // Execution de la requete
          $loPDOStat->execute();
          $liFetchMode = \PDO::FETCH_ASSOC;
          if ($piPDOFetchMode !== NULL) {
            $liFetchMode = $piPDOFetchMode;
          }
          $laResults = $loPDOStat->fetchAll($liFetchMode);
        }
    } catch (\Exception $e) {
      $lsMsgException = sprintf(
          "An error occured during database querying (getFirstRowOnly) : %s.",
          $e->getMessage()
        );
      throw new Exceptions\DatabaseException($lsMsgException);
    } finally {
      // TODO To implement
    }

    if (count($laResults) > 0) {
      $laFinalRow = array_shift($laResults);
    }

    return $laFinalRow;
  }//end getFirstRowOnly()

  /**
   * refreshStatisticsForTable
   *
   * Execution du rafraichissement des statictics, nécessaire pour le calcul de sidentifiants internes.
   *
   * @static
   * @param string  $tableName   Nom de la table
   */
  static function refreshStatisticsForTable($tableName) : void
  {
    $laResults = [];
    $laFinalRow = null;

    $SQLcommand = "ANALYZE TABLE ".$tableName.";";

    try {
        // DB connection active ?
        if (self::$_oDbPDOHandler === NULL) {
          $lsMsgException = sprintf("Database connection not defined.");
          throw new Exceptions\DatabaseException($lsMsgException);
        } else {
          $loPDOStat = self::$_oDbPDOHandler->prepare($SQLcommand);
          // Execution de la requete
          $loPDOStat->execute();
        }
    } catch (\Exception $e) {
      $lsMsgException = sprintf(
          "An error occured during database querying (refreshStatistics) : %s.",
          $e->getMessage()
        );
      throw new Exceptions\DatabaseException($lsMsgException);
    } finally {
      // TODO To implement
    }
  }//end refreshStatisticsForTable()

  /**
   * refreshStatisticsForLogsTable
   *
   * Rafraichissement des stats sur les tables de logs
   *
   * @static
   */
  static function refreshStatisticsForLogsTable() : void
  {
    self::refreshStatisticsForTable('Z000_LOGS');
  }//end refreshStatisticsForLogsTable()

  /**
   * execMySQLScriptByShell
   *
   * Réalise l'execution du script SQL via command Shell basée sur le client
   * mysql
   *
   * @static
   * @param string  $psSQLScriptFilepath    Chemin du script à executer.
   * @param string  $psDbUser               DB Login Utilisateur.
   * @param string  $psDbPass               DB Password Utilisateur.
   * @param string  $psDbHost               DB Hote cible.
   * @param string  $piDbPort               (Optionel) DB Port (default:3306)
   * @return array   Retour de l'execution.
   */
  static function execMySQLScriptByShell($psSQLScriptFilepath,$psDbUser,$psDbPass,$psDbHost,$piDBPort=3306)
  {
    $output = null;
    try {
      // fichier du script existant ?
      if (!file_exists($psSQLScriptFilepath)) {
        throw new Exceptions\ApplicationFileNotFoundException($psSQLScriptFilepath);
      }

      $command  = "mysql -u {$psDbUser} -p'{$psDbPass}' ".
        "-h {$psDbHost} -P {$piDBPort} -D sys < {$psSQLScriptFilepath}";
      $output   = @shell_exec($command);

      print_r($output);
    } catch (\Exception $e) {
      $lsMsgException = sprintf(
          "An error occured during executing script file by shell command to MySQL database (execMySQLScriptByShell) : %s.",
          $e->getMessage()
        );
      throw new Exceptions\DatabaseException($lsMsgException);
    } finally {
      // TODO To implement
    }
    return $output;
  }//end execMySQLScriptByShell()

  /**
   * Retourne une chaine au format DSN
   *
   * @static
   * @example "mysql:dbname=GOM;host=localhost;port=3336"
   *
   * @param string $psDBType    Type de la connection du DSN.
   * @param string $psDBSchema  Schéma cible.
   * @param string $psDBHost    Hote cible.
   * @param string $piDBPort    Port cible.
   */
  static function buildDatabaseDSN($psDBType,$psDBSchema,$psDBHost,$piDBPort)
  {
    $lsDSN = sprintf(
        "%s:dbname=%s;host=%s;port=%s",
        $psDBType,
        $psDBSchema,
        $psDBHost,
        $piDBPort
      );
    return $lsDSN;
  }//end buildDatabaseDSN()

  /**
   * execQuery
   *
   * Execute une requete (INSERT/UPDATE/DELETE)
   *
   * @static
   * @param string  $psSQLQuery   Requete SQL à executer
   * @return bool  Statut d'execution
   */
  static function execQuery($psSQLQuery) : bool
  {
    try {
        // DB connection active ?
        if (self::$_oDbPDOHandler === NULL) {
          $lsMsgException = sprintf("Database connection not defined.");
          throw new Exceptions\DatabaseException($lsMsgException);
        } else {

          $loPDOStat = self::$_oDbPDOHandler->prepare($psSQLQuery);

          // Execution de la requete
          return $loPDOStat->execute();
        }
    } catch (\Exception $e) {
      $lsMsgException = sprintf(
          "An error occured during database querying (execQuery) : %s.",
          $e->getMessage()
        );
      throw new Exceptions\DatabaseException($lsMsgException);
    } finally {
      // TODO To implement
    }
    return $laResults;
  }//end execQuery()

}//end class
