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

    echo "Coucou :".$lsTIDDocObj;

    GOM\Core\Data\ObjectDefinition::createNewObjectDefinitionModel(
      'SI.MDL-SPE-0002',
      'CAT',
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

    $this->assertTrue(true);
  } //end testCreateNewModelIntoDatabase()


}//end class
 ?>
