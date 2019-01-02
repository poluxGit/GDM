<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\DataFactory;
use GOM\Core\Application;
use GOM\Core\Data\LinkDefinition;

/**
 * ObjectDefinitionTest
 *
 */
class ObjectDefinitionTest extends TestCase
{

  /**
   * testApplicationLoadSettingsFromFile
   *
   * Chargement des paramètres depuis un fichier invalide.
   * @expectedException GOM\Core\Internal\Exception\ApplicationSettingsFileNotFoundException
   */
  public function testApplicationLoadSettingsFromFile(): void
  {
    Application::loadDBSettings('./tests/datasets/app-settings_02-valid.json');
  }//end testApplicationLoadSettingsFromFile()


  /**
   * testCreateNewObjectDefinitionIntoDatabase
   *
   */
  public function testCreateNewObjectDefinitionIntoDatabase()
  {
    GOM\Core\Data\ObjectDefinition::createNewObjectDefinitionModel(
      'SI.MDL-SPE-0002',
      'DOC',
      'Document',
      "Document Simple",
      "com" ,
      'Simple',
      'DOC',
      20,
      '',
      'DOCB',
      '',
      'E100_DOCUMENT'
    );

    $this->assertTrue(true);
  } //end testCreateNewModelIntoDatabase()


}//end class
 ?>
