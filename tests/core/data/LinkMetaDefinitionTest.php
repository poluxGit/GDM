<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\DataFactory;
use GOM\Core\Application;
use GOM\Core\Data\LinkDefinition;
use GOM\Core\Data\LinkMetaDefinition;

/**
 * LinkDefinitionTest
 *
 */
class LinkMetaDefinitionTest extends TestCase
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
   * testCreateNewMetaStringLinkDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur lien de type string
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewMetaStringLinkDefinitionIntoDatabase()
  {
    $lsTID = GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.LNKD-SPE-00001',
      'LNKM-DOC_CAT.ATTR-STR',
      'AttrString',
      'Attribut String test.',
      'Attribut Meta String test.' ,
      'String',
      'LNKM-DOC_CAT_TEST-STR' ,
      'LNKIT-DOC_CAT_TEST-STR',
      'LNKIB-DOC_CAT_TEST-STR',
      '{"comp_data":[]}'
    );

    $this->assertEquals('SI.LNKD-SPE-0000000001',$lsTID);
  } //end testCreateNewMetaStringLinkDefinitionIntoDatabase()

  /**
   * testCreateNewMetaDateLinkDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur lien de type date
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewMetaDateLinkDefinitionIntoDatabase()
  {
    $lsTID = GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.LNKD-SPE-00001',
      'LNKM-DOC_CAT.ATTR-DATE',
      'AttrDate',
      'Attribut Date.',
      'Attribut Meta Date test.' ,
      'Date',
      'LNKM-DOC_CAT_DATE' ,
      'LNKIT-DOC_CAT_DATE',
      'LNKIB-DOC_CAT_DATE',
      '{"comp_data":[]}'
    );

    $this->assertEquals('SI.LNKD-SPE-0000000002',$lsTID);
  } //end testCreateNewMetaDateLinkDefinitionIntoDatabase()

  /**
   * testCreateNewMetaDatetimeLinkDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur lien de type Datetime
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewMetaDatetimeLinkDefinitionIntoDatabase()
  {
    $lsTID = GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.LNKD-SPE-00001',
      'LNKM-DOC_CAT.ATTR-DATETIME',
      'AttrDatetime',
      'Attribut Datetime.',
      'Attribut Meta Datetime test.' ,
      'Datetime',
      'LNKM-DOC_CAT_DATETIME' ,
      'LNKIT-DOC_CAT_DATETIME',
      'LNKIB-DOC_CAT_DATETIME',
      '{"comp_data":[]}'
    );

    $this->assertEquals('SI.LNKD-SPE-0000000003',$lsTID);
  } //end testCreateNewMetaDatetimeLinkDefinitionIntoDatabase()

  /**
   * testCreateNewMetaIntegerLinkDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur lien de type Integer
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewMetaIntegerLinkDefinitionIntoDatabase()
  {
    $lsTID = GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.LNKD-SPE-00001',
      'LNKM-DOC_CAT.ATTR-INT',
      'AttrInteger',
      'Attribut Integer.',
      'Attribut Meta Integer test.' ,
      'Integer',
      'LNKM-DOC_CAT_INT' ,
      'LNKIT-DOC_CAT_INT',
      'LNKIB-DOC_CAT_INT',
      '{"comp_data":[]}'
    );

    $this->assertEquals('SI.LNKD-SPE-0000000004',$lsTID);
  } //end testCreateNewMetaIntegerLinkDefinitionIntoDatabase()

  /**
   * testCreateNewMetaRealLinkDefinitionIntoDatabase
   *
   * Déclaration d'une métadonnée sur lien de type Real
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewMetaRealLinkDefinitionIntoDatabase()
  {
    $lsTID = GOM\Core\Data\LinkMetaDefinition::createNewMetaLinkDefinition(
      'SI.LNKD-SPE-00001',
      'LNKM-DOC_CAT.ATTR-REAL',
      'AttrReal',
      'Attribut Real.',
      'Attribut Meta Real test.' ,
      'Real',
      'LNKM-DOC_CAT_REAL' ,
      'LNKIT-DOC_CAT_REAL',
      'LNKIB-DOC_CAT_REAL',
      '{"comp_data":[]}'
    );

    $this->assertEquals('SI.LNKD-SPE-0000000005',$lsTID);
  } //end testCreateNewMetaRealLinkDefinitionIntoDatabase()

  /**
   * testGetAllMetaDefinitionsForALinkDefinition
   *
   * Vérification du nombre de meta enregistrées (5 attendu)
   *
   * @depends testApplicationLoadSettingsFromFile
   * @depends testCreateNewMetaRealLinkDefinitionIntoDatabase
   */
  public function testGetAllMetaDefinitionsForALinkDefinition()
  {
    $aResults = GOM\Core\Data\LinkMetaDefinition::getAllMetaDefinitionsForALinkDefinition('SI.LNKD-SPE-00001');
    $this->assertCount(5,$aResults);
  } //end testGetAllMetaDefinitionsForALinkDefinition()

}//end class
 ?>
