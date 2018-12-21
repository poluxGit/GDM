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
class ApplicationSettingsFileInvalidFormatException extends GOMEx
{
  /**
   * Constructeur par défaut
   *
   * @param string $psAppSettingsFilePath  Chemin du fichier en erreur
   */
  public function __construct($psAppSettingsFilePath,$psErrorMessage)
  {
    parent::__construct(4100,"Invalid file format - details : %s (file:%s).",[$psErrorMessage,$psAppSettingsFilePath]);
  }//end __construct()

}//end class
