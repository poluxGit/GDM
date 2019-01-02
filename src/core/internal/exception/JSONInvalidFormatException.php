<?php

/**
 * ApplicationSettingsFileInvalidFormatException - Définition de la classe ApplicationSettingsFileInvalidFormatException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

use \GOM\Core\Internal\GOMException as GOMEx;

/**
 * Classe ApplicationSettingsFileInvalidFormatException
 *
 * Format JSON du fichier de settings applicatif non valide.
 */
class JSONInvalidFormatException extends GOMEx
{
  /**
   * Constructeur par défaut
   *
   * @param string $psAppSettingsFilePath  Chemin du fichier en erreur
   */
  public function __construct($psErrorMessage)
  {
    parent::__construct(4100,"Invalid JSON format - details : %s.",[$psErrorMessage]);
  }//end __construct()

}//end class
