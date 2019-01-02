<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\DataFactory;
use GOM\Core\Application;
use GOM\Core\Data\LinkDefinition;
use GOM\Core\Data\LinkMetaDefinition;

/**
 * ObjectMetaDefinitionTest
 *
 */
class ObjectMetaDefinitionTest extends TestCase
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
   * testCreateNewStringMetaObjectDefinition
   *
   * Déclaration d'une métadonnée sur objet de type string
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewStringMetaObjectDefinition()
  {
    GOM\Core\Data\ObjectMetaDefinition::createNewMetaObjectDefinition(
      'SI.OBD-SPE-00010',
      'OBMD-DOC.ATTR-STR',
      'AttrDocString',
      'AttributDoc String test.',
      'AttributDoc Meta String test.' ,
      'String',
      'OBMD-DOC_TEST-STR' ,
      'OBMIT-DOC_TEST-STR',
      'OBMIB-DOC_TEST-STR',
      '{"comp_data":[]}'
    );

    $this->assertTrue(true);
  } //end testCreateNewStringMetaObjectDefinition()

  /**
   * testCreateNewDateMetaObjectDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur objet de type date
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewDateMetaObjectDefinitionIntoDatabase()
  {
    GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.OBD-SPE-00010',
      'OBMD-DOC.ATTR-DATE',
      'AttrDocDate',
      'Attribut Date.',
      'Attribut Meta Date test.' ,
      'Date',
      'OBMD-DOC_DATE' ,
      'OBMIT-DOC_DATE',
      'OBMIB-DOC_DATE',
      '{"comp_data":[]}'
    );

    $this->assertTrue(true);
  } //end testCreateNewDateMetaObjectDefinitionIntoDatabase()

  /**
   * testCreateNewDatetimeMetaObjectDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur objet de type Datetime
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewDatetimeMetaObjectDefinitionIntoDatabase()
  {
    GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.OBD-SPE-00010',
      'OBMD-DOC.ATTR-DATETIME',
      'AttrDocDatetime',
      'Attribut Datetime.',
      'Attribut Meta Datetime test.' ,
      'Datetime',
      'OBMD-DOC_DATETIME' ,
      'OBMIT-DOC_DATETIME',
      'OBMIB-DOC_DATETIME',
      '{"comp_data":[]}'
    );

    $this->assertTrue(true);
  } //end testCreateNewDatetimeMetaObjectDefinitionIntoDatabase()

  /**
   * testCreateNewIntegerMetaObjectDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur objet de type Integer
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewIntegerMetaObjectDefinitionIntoDatabase()
  {
    GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.OBD-SPE-00010',
      'OBMD-DOC.ATTR-INT',
      'AttrDocInteger',
      'Attribut Integer.',
      'Attribut Meta Integer test.' ,
      'Integer',
      'OBMD-DOC_INT' ,
      'OBMIT-DOC_INT',
      'OBMIB-DOC_INT',
      '{"comp_data":[]}'
    );

    $this->assertTrue(true);
  } //end testCreateNewIntegerMetaObjectDefinitionIntoDatabase()

  /**
   * testCreateNewMetaRealLinkDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur objet de type Real
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewRealMetaObjectDefinitionIntoDatabase()
  {
    GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.OBD-SPE-00010',
      'OBMD-DOC.ATTR-REAL',
      'AttrDocReal',
      'Attribut Real.',
      'Attribut Meta Real test.' ,
      'Real',
      'OBMD-DOC_REAL' ,
      'OBMIT-DOC_REAL',
      'OBMIB-DOC_REAL',
      '{"comp_data":[]}'
    );

    $this->assertTrue(true);
  } //end testCreateNewRealMetaObjectDefinitionIntoDatabase()

  /**
   * testGetAllMetaDefinitionsForAnObjetDefinition
   *
   * Vérification du nombre de meta enregistrées (5 attendu)
   *
   * @depends testApplicationLoadSettingsFromFile
   * @depends testCreateNewRealMetaObjectDefinitionIntoDatabase
   */
  public function testGetAllMetaDefinitionsForAnObjetDefinition()
  {
    $aResults = GOM\Core\Data\LinkMetaDefinition::getAllMetaDefinitionsForAnObjectDefinition('SI.OBD-SPE-00010');
    $this->assertCount(5,$aResults);
  } //end testGetAllMetaDefinitionsForAnObjetDefinition()

}//end class
 ?>
