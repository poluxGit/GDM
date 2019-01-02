<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\DataFactory;
use GOM\Core\Application;
use GOM\Core\Data\LinkDefinition;

/**
 * ModelTest - Classe statique de gestion des données
 *
 */
class ModelTest extends TestCase
{
  /**
   * testCreateNewModelIntoDatabase
   *
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
}//end class
 ?>
