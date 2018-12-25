<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\DataFactory;
use GOM\Core\Application;

/**
 * DataFactory - Classe statique de gestion des données
 *
 */
class DataFactoryTest extends TestCase
{
  /**
   * testDeployApplicationCoreDatabaseStructure
   *
   * Chargement des paramètres depuis un fichier invalide.
   * @expectedException GOM\Core\Internal\Exception\ApplicationSettingsFileNotFoundException
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

  public function testGettingModelFromDatabaseByTid()
  {
    $lObj = DataFactory::getModel('MDL-SI-0001');
    $this->assertNotNull($lObj);
    $this->assertInstanceOf(GOM\Core\Data\Model::class,$lObj);
  }
}//end class
 ?>
