<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\DataFactory;
use GOM\Core\Application;
use GOM\Core\Data\LinkDefinition;

/**
 * LinkDefinitionTest
 *
 */
class LinkDefinitionTest extends TestCase
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
   * testCreateNewLinkDefinitionIntoDatabase
   *
   * @depends testApplicationLoadSettingsFromFile
   */
  public function testCreateNewLinkDefinitionIntoDatabase()
  {
    $lsLNKDTID = GOM\Core\Data\LinkDefinition::createNewLinkDefinitionModel(
      'SI.MDL-SPE-0002',
      'LNK-DOC_CAT',
      'Lien Doc vers Catégorie',
      'Lien Doc vers Catégorie.',
      'Lien de ODB Doc vers ODB Catégorie' ,
      'OneToOne',
      'SI.OBD-SPE-00010' ,
      'SI.OBD-SPE-00011',
      '{"comp_data":[]}'
    );

    echo "TID :".$lsLNKDTID;

    $this->assertTrue(true);
  } //end testCreateNewLinkDefinitionIntoDatabase()

}//end class
 ?>
