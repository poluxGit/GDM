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
   * testDeployApplicationCoreDatabaseStructure
   */
  public function testDeployApplicationCoreDatabaseStructure(): void
  {
    Application::loadDBSettings('./../tests/datasets/AppSettingsFile_02-valid.json');
    Application::deploySchemaToDefaultAppliDB(
      'root',
      'dev'
    );
    $this->assertTrue(true);
  }//end testDeployApplicationCoreDatabaseStructure()

  /**
   * testCreateNewModelIntoDatabase
   *
   * @depends testDeployApplicationCoreDatabaseStructure
   */
  public function testCreateNewModelIntoDatabase()
  {
    GOM\Core\Data\Model::createNewModel(
      'E1',
      'ECM',
      'dev',
      'ECM Perso',
      'ECM Personnel.',
      'desc',
      '{"toto":1}'
    );

    $lObj = DataFactory::getModel('SI.MDL-SPE-0002');
    $this->assertNotNull($lObj);
    $this->assertInstanceOf(GOM\Core\Data\Model::class,$lObj);
    $lObj->loadObject();
    $this->assertEquals('MDL-ECM-dev',$lObj->getFieldValueFromName('ID'));

  } //end testCreateNewModelIntoDatabase()

  /**
   * testCreateNewModelIntoDatabase
   *
   * @depends testCreateNewModelIntoDatabase
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
    // $lObj = DataFactory::getModel('SI.MDL-SPE-0002');
    // $this->assertNotNull($lObj);
    // $this->assertInstanceOf(GOM\Core\Data\Model::class,$lObj);
    // $lObj->loadObject();
    // $this->assertEquals('MDL-ECM-dev',$lObj->getFieldValueFromName('ID'));

  } //end testCreateNewModelIntoDatabase()


}//end class
 ?>
