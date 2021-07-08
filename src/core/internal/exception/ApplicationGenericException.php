<?php

/**
 * ApplicationGenericException Class definition
 *
 * Définition de la classe ApplicationGenericException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

use \GOM\Core\Internal\GOMException as GOMEx;

/**
 * Classe ApplicationGenericException
 *
 *
 */
class ApplicationGenericException extends GOMEx
{
  /**
   * Constructeur par défaut
   *
   * @param string $psMessage  Message de l'exception
   */
  public function __construct($psMessage)
  {
    parent::__construct(4510,"%s",[$psMessage]);
  }//end __construct()

}//end class
