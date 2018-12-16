<?php

/**
 * GOMException.php - Définition de la classe GOMException
 *
 * @category
 */
namespace GOM\Core\Internal;


/**
 * Classe GOMException
 */
class GOMException extends \Exception
{
  /**
   * Exception Definitions
   *
   * @var array(code => GOMExceptionObject)
   */
  protected static $_aExceptionsDefinition = [];

  protected $_sExceptionCode = null;
  protected $_sExceptionMessagePattern = null;
  protected $_aExceptionParamValues = null;

  /**
   * Constructeur par défaut
   *
   * @param string $psExceptionCode             Code interne de l'exception.
   * @param string $psExceptionMessagePattern   Message de l'exception.
   * @param array $paExceptionParamValues       Tableau des valeurs à injecter dans le pattern du message.
   */
  public function __construct($psExceptionCode, $psExceptionMessagePattern=null, $paExceptionParamValues=null)
  {
    $this->_sExceptionCode = $psExceptionCode;

    // Gestion du message à définir
    if (is_null($psExceptionMessagePattern)) {
      $lsMessage = self::getMessagePatternFromExceptionCode($psExceptionCode);
      if (is_null($lsMessage)) {
        $this->_sExceptionMessagePattern = "Message de l'exception non défini.";
      } else {
        $this->_sExceptionMessagePattern = $lsMessage;
      }
    } else {
      $this->_sExceptionMessagePattern = $psExceptionMessagePattern;
    }
    // Génération du message final!
    $lStrMessageException = null;
    if (!is_null($paExceptionParamValues)) {
      $lStrMessageException = sprintf( $this->_sExceptionMessagePattern,$paExceptionParamValues);
    }
    else {
      $lStrMessageException = $this->_sExceptionMessagePattern;
    }
    parent::__construct($this->_sExceptionCode,$lStrMessageException);
  }//end __construct()

  /**
   * Returns Exception message pattern
   *
   * @return string
   */
  protected static function getMessagePatternFromExceptionCode($psExceptionCode)
  {
    $lsResult = "";
    if (array_key_exists(strtoupper($psExceptionCode),self::$_aExceptionsDefinition)) {
      $lsResult = self::$_aExceptionsDefinition[$psExceptionCode];
    }
    return $lsResult;
  }//end getMessagePatternFromExceptionCode()

  /**
   * Add Exception Pattern definition
   *
   * @static
   */
  protected static function addExceptionDefinition($psExceptionCode,$psExceptionMessagePattern)
  {
    self::$_aExceptionsDefinition[strtoupper($psExceptionCode)] = $psExceptionMessagePattern;
  }//end addExceptionDefinition()
}//end class
