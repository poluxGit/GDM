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

  /**
   * @var string
   */
  protected $_sExceptionCode = null;
  /**
   * @var string
   */
  protected $_sExceptionMessagePattern = null;
  /**
   * @var string
   */
  protected $_aExceptionParamValues = null;

  /**
   * Constructeur par défaut
   *
   * @param string $psExceptionCode             Code interne de l'exception.
   * @param string $psExceptionMessagePattern   Message de l'exception.
   * @param array $paExceptionParamValues       Tableau des valeurs à injecter dans le pattern du message.
   */
  public function __construct($piExceptionCode, $psExceptionMessagePattern=null, $paExceptionParamValues=null)
  {
    $liExceptionCode = null;

    // Le code est obligatoire !
    if (is_null($piExceptionCode)) {
      die("Le code de l'exception est obligatoire !");
    } else {
      $liExceptionCode = $piExceptionCode;
    }

    // Message non spécifié ?
    if (is_null($psExceptionMessagePattern)) {
      // L'on recherche un message  liée au code de l'exception !
      $lsMessage = self::getMessagePatternFromExceptionCode($liExceptionCode);
      // Aucun message trouvé ?
      if (is_null($lsMessage)) {
        $lsMessage = "Message de l'exception non défini.";
      }
    } else {
      $lsMessage = $psExceptionMessagePattern;
    }

    // Génération du message final!
    $lStrMessageException = null;
    if (!is_null($paExceptionParamValues)) {
      $lStrMessageException = sprintf(
        $lsMessage,
        $paExceptionParamValues
      );
    }
    else {
      $lStrMessageException = $lsMessage;
    }

    parent::__construct($lStrMessageException,6589);
    $this->_sExceptionCode = $liExceptionCode;
    $this->_sExceptionMessagePattern = $lsMessage;
    $this->_aExceptionParamValues = $paExceptionParamValues;
  }//end __construct()

  /**
   * Returns Exception message pattern
   *
   * @return string
   */
  protected static function getMessagePatternFromExceptionCode($psExceptionCode)
  {
    $lsResult = "";
    if (array_key_exists($psExceptionCode,self::$_aExceptionsDefinition)) {
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
    self::$_aExceptionsDefinition[$psExceptionCode] = $psExceptionMessagePattern;
  }//end addExceptionDefinition()
}//end class
