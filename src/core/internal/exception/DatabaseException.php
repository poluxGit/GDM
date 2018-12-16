<?php

/**
 * GOMException.php - Définition de la classe GOMException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

use GOM\Core\Internal;
/**
 * Classe GOMException
 */
class DatabaseException extends GOMException
{
  /**
   * Array of Exception Definition
   *
   * @var array(code => GOMExceptionObject)
   */
  protected static $_aExceptionsDefinition = [];

  /**
   * Returns Exception message pattern
   *
   * @return string
   */
  protected static function getMessagePatternFromExceptionCode($psExceptionCode)
  {
    return "";
  }//end getMessagePatternFromExceptionCode()

  /**
   * Add Exception Pattern definition
   *
   * @static
   */
  protected static function addExceptionDefinition($psExceptionCode)
  {
    return "";
  }//end getMessagePatternFromExceptionCode()


}//end class
