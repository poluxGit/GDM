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
   * Constructeur par défaut
   *
   * @param string $psAppSettingsFilePath  Chemin du fichier en erreur
   */
  public function __construct($psDBErrorMessage)
  {
    parent::__construct(4200,"Database Exception - '%s' ",[$psDBErrorMessage]);
  }//end __construct()


}//end class
