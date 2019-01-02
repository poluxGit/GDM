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
    Application::loadDBSettings('./tests/datasets/app-settings_02-valid.json');
    Application::deploySchemaToDefaultAppliDB(
      'root',
      'dev'
    );
    $this->assertTrue(true);
  }//end testDeployApplicationCoreDatabaseStructure()

  /**
   * testGetModelObjectFromDatabaseByTidOk
   *
   * @depends testDeployApplicationCoreDatabaseStructure
   */
  public function testGetModelObjectFromDatabaseByTidOk()
  {
    $lObj = DataFactory::getModel('MDL-SI-0001');
    $this->assertNotNull($lObj);
    $this->assertInstanceOf(GOM\Core\Data\Model::class,$lObj);
    $lObj->loadObject();
    
  } //end testGetModelObjectFromDatabaseByTidOk()

  /**
   * testGetModelObjectFromDatabaseByTidNotOk
   *
   * @depends testDeployApplicationCoreDatabaseStructure
   * @expectedException GOM\Core\Internal\Exception\ObjectNotFoundException
   */
  public function testGetModelObjectFromDatabaseByTidNotOk()
  {
    $lObj = DataFactory::getModel('MDL-SI-0003');

  } //end testGetModelObjectFromDatabaseByTidNotOk()

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

}//end class
 ?>
