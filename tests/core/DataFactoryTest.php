<?php

use PHPUnit\Framework\TestCase;
use GOM\Data\Internal;

/**
 * DataFactory - Classe statique de gestion des données
 *
 */
class DataFactoryTest extends TestCase
{
  /**
   * testApplicationLoadInvalidSettingsFile
   *
   * Chargement des paramètres depuis un fichier invalide.
   * @expectedException GOM\Core\Internal\Exception\ApplicationSettingsFileNotFoundException
   */
  public function testApplicationLoadSettingsFromNonExistingFile(): void
  {
    $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
  }//end testApplicationLoadSettingsFromNonExistingFile()
}//end class
 ?>
