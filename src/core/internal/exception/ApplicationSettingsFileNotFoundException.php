<?php

/**
 * ApplicationSettingsFileNotFoundException Class definition
 *
 * Définition de la classe ApplicationSettingsFileNotFoundException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

use \GOM\Core\Internal\GOMException as GOMEx;

/**
 * Classe ApplicationSettingsFileNotFoundException
 *
 * Format JSON du fichier de settings applicatif non valide.
 */
class ApplicationSettingsFileNotFoundException extends GOMEx
{
  /**
   * Constructeur par défaut
   *
   * @param string $psAppSettingsFilePath  Chemin du fichier en erreur
   */
  public function __construct($psAppSettingsFilePath)
  {
    parent::__construct(4101,"Application Settings - File '%s' not founded!",[$psAppSettingsFilePath]);
  }//end __construct()

}//end class
