<?php

/**
 * DatabaseSQLException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

use \GOM\Core\Internal\GOMException as GOMEx;
/**
 * Classe DatabaseSQLException
 */
class DatabaseSQLException extends GOMEx
{
  /**
   * Constructeur par défaut
   *
   * @param string        $psMessage    Message d'erreur
   * @param \PDOStatement $pdoStatment  (Optionnel) Objet PDOStatement utilisée pour l'exception.
   */
  public function __construct($psMessage,\PDOStatement $pdoStatment = null)
  {
    $compMessage = null;
    $aParam = [];
    // Erreur SQL ? => On adapte le message !
    if (!is_null($pdoStatment)) {
        $arrayErrorInfo = $pdoStatment->errorInfo();
        if (count($arrayErrorInfo) >= 3) {
          \array_push($aParam,$arrayErrorInfo[0]);
          \array_push($aParam,$arrayErrorInfo[2]);
          $compMessage = " (Code SQL : %s - Message : %s)";
        }
    }
    parent::__construct(4600,$psMessage.$compMessage,$aParam);
  }//end __construct()
}//end class
