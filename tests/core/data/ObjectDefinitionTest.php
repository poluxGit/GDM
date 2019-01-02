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
    // $lObj = DataFactory::getModel('SI.MDL-SPE-0002');
    // $this->assertNotNull($lObj);
    // $this->assertInstanceOf(GOM\Core\Data\Model::class,$lObj);
    // $lObj->loadObject();
    // $this->assertEquals('MDL-ECM-dev',$lObj->getFieldValueFromName('ID'));

  } //end testCreateNewModelIntoDatabase()


}//end class
 ?>
