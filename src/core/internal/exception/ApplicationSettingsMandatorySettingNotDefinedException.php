<?php

/**
 * ApplicationSettingsMandatorySettingNotDefinedException Class definition
 *
 * Définition de la classe ApplicationSettingsMandatorySettingNotDefinedException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

use \GOM\Core\Internal\GOMException as GOMEx;

/**
 * Classe ApplicationSettingsMandatorySettingNotDefinedException
 *
 * Paramètre applicatif obligatoire manquant
 */
class ApplicationSettingsMandatorySettingNotDefinedException extends GOMEx
{
  /**
   * Constructeur par défaut
   *
   * @param string $psAppSettingsFilePath  Chemin du fichier en erreur
   */
  public function __construct($psAppParam)
  {
    parent::__construct(4102,"Application Settings - Mandatory field '%s' not founded!",[$psAppParam]);
  }//end __construct()

}//end class
