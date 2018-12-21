<?php

/**
 * SQLQueryGeneratorException - Définition de la classe SQLQueryGeneratorException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

/**
 * Classe SQLQueryGeneratorException
 *
 * Exception lancée
 */
class SQLQueryGeneratorException extends \GOM\Core\Internal\GOMException
{
  /**
   * Constructeur par défaut
   *
   * @param string $psExceptionCode  Code interne de l'exception
   */
  public function __construct($psExceptionMessage)
  {
    parent::__construct(4001,$psExceptionMessage);
  }//end __construct()

}//end class
