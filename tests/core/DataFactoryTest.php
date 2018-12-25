<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\DataFactory;
use GOM\Core\Application;
use GOM\Core\Data\LinkDefinition;

/**
 * DataFactory - Classe statique de gestion des données
 *
 */
class DataFactoryTest extends TestCase
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
   * testGetModelObjectFromDatabaseByTid
   *
   * @depends testDeployApplicationCoreDatabaseStructure
   */
  public function testGetModelObjectFromDatabaseByTid()
  {
    $lObj = DataFactory::getModel('MDL-SI-0001');
    $this->assertNotNull($lObj);
    $this->assertInstanceOf(GOM\Core\Data\Model::class,$lObj);
    $lObj->loadObject();
    
  } //end testGetModelObjectFromDatabaseByTid()

  /**
   * testGetObjectDefinitionFromDatabaseByTid
   *
   * @depends testDeployApplicationCoreDatabaseStructure
   */
  public function testGetObjectDefinitionFromDatabaseByTid()
  {
    $lObj = DataFactory::getObjectDefinition('SI.OBD-SPE-00003');
    $this->assertNotNull($lObj);
    $this->assertInstanceOf(GOM\Core\Data\ObjectDefinition::class,$lObj);
    $lObj->loadObject();
    $this->assertEquals('ODB.SYS-LNKD',$lObj->getFieldValueFromName('ID'));
  } //end testGetObjectDefinitionFromDatabaseByTid()


  /**
   * testCreateNewModelIntoDatabase
   *
   * @depends testDeployApplicationCoreDatabaseStructure
   */
  public function testCreateNewModelIntoDatabase()
  {
    $lsId = GOM\Core\Data\Model::createNewModel(
      'E1',
        'ECM',
        'dev',
        'ECM Perso',
        'ECM Personnel.',
        'desc',
        '{"toto":1}'
    );

    $lObj = DataFactory::getModel($lsId);
    $this->assertNotNull($lObj);
    $this->assertInstanceOf(GOM\Core\Data\Model::class,$lObj);
    $lObj->loadObject();
    $this->assertEquals('MDL-ECM-dev',$lObj->getFieldValueFromName('ID'));

  } //end testCreateNewModelIntoDatabase()


}//end class
 ?>
