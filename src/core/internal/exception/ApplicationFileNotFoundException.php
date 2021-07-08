<?php

/**
 * ApplicationFileNotFoundException Class definition
 *
 * Définition de la classe ApplicationFileNotFoundException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

use \GOM\Core\Internal\GOMException as GOMEx;

/**
 * Classe ApplicationFileNotFoundException
 *
 * Lancée quand fichier inexistant
 */
class ApplicationFileNotFoundException extends GOMEx
{
  /**
   * Constructeur par défaut
   *
   * @param string $psFilepath  Chemin du fichier en erreur
   */
  public function __construct($psFilepath)
  {
    parent::__construct(4501,"File '%s' not founded!",[$psFilepath]);
  }//end __construct()

}//end class
