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
   * Chargement des paramètres depuis un fichier valide.
   */
  public function testApplicationLoadSettingsFromFile()
  {
    Application::loadDBSettings('./tests/datasets/app-settings_02-valid.json');
    $this->assertTrue(true);
  }//end testApplicationLoadSettingsFromFile()


  /**
   * testCreateNewObjectDefinitionIntoDatabase
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewObjectDefinitionIntoDatabase()
  {
    $lsTIDDocObj = GOM\Core\Data\ObjectDefinition::createNewObjectDefinitionModel(
      'SI.MDL-SPE-0002',
      'T1',
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

    $this->assertEquals('SI.OBD-SPE-00010',$lsTIDDocObj);

    $lsTIDDocObj = GOM\Core\Data\ObjectDefinition::createNewObjectDefinitionModel(
      'SI.MDL-SPE-0002',
      'T1',
      'Catégorie',
      "Catégorie Simple",
      "com Catégorie" ,
      'Simple',
      'CAT',
      10,
      '',
      'CATB',
      '',
      'E100_CATEGORIE'
    );

    $this->assertEquals('SI.OBD-SPE-00011',$lsTIDDocObj);

  } //end testCreateNewModelIntoDatabase()


}//end class
 ?>
