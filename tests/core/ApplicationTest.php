<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\Application;

/**
 * ApplicationTest
 *
 * Tests de la classe GOM\Core\Application
 */
final class ApplicationTest extends TestCase
{
		/**
		 * testApplicationLoadInvalidSettingsFile
		 *
		 * Chargement des paramètres depuis un fichier invalide.
		 * @expectedException GOM\Core\Internal\Exception\ApplicationSettingsFileNotFoundException
		 */
  	public function testApplicationLoadSettingsFromNonExistingFile(): void
  	{
			Application::loadDBSettings('./toto');
	  }//end testApplicationLoadSettingsFromNonExistingFile()

		/**
		 * testApplicationLoadInvalidFormatSettingsFile
		 *
		 * Chargement des paramètres depuis un fichier au format JSON invalide.
		 * @expectedException GOM\Core\Internal\Exception\ApplicationSettingsFileInvalidFormatException
		 */
  	public function testApplicationLoadSettingsFromInvalidFormatedFile(): void
  	{
		 //	$this->expectException(\Exception::class);
			Application::loadDBSettings('./../tests/datasets/AppSettingsFile_01-invalidformat.json');
			$this->assertEquals($laObj,NULL);

	  }//end testApplicationLoadSettingsFromInvalidFormatedFile()

    /**
     * testApplicationLoadSettingsFileMandaotryParamMissing
     *
     * Chargement des paramètres depuis un fichier au format JSON invalide.
     * @expectedException GOM\Core\Internal\Exception\ApplicationSettingsMandatorySettingNotDefinedException
     */
    public function testApplicationLoadSettingsFileMandatoryParamMissing(): void
    {
     //	$this->expectException(\Exception::class);
      Application::loadDBSettings('./../tests/datasets/AppSettingsFile_02-invalidNoMand.json');
    }//end testApplicationLoadSettingsFileMandatoryParamMissing()

    /**
     * testApplicationInvalidDatabaseConnection
     *
     * Chargement des paramètres depuis un fichier au format JSON invalide.
     * @expectedException \PDOException
     */
    public function testApplicationInvalidDatabaseConnection(): void
    {
       Application::loadDBSettings('./../tests/datasets/AppSettingsFile_03-invalidconnec.json');
    }//end testApplicationInvalidDatabaseConnection()

		/**
		 * testApplicationLoadValideSettingsFile
		 *
		 * Chargement des paramètres depuis un fichier au format JSON valide.
     * @depends testApplicationDeployingDB
		 */
  	public function testApplicationLoadSettingsFromValidFile(): void
  	{
		 	Application::loadDBSettings('./../tests/datasets/AppSettingsFile_02-valid.json');
		 	$this->assertTrue(true);
		}//end testApplicationLoadSettingsFromValidFile()

    /**
     * testApplicationDeployingIntoTargetDatabase
     *
     * Chargement des paramètres depuis un fichier au format JSON valide.
     */
    public function testApplicationDeployingIntoTargetDatabase(): void
    {
      Application::deploySchemaToTargetDB(
        'GDM_TEST',
        'root',
        'dev',
        '172.17.0.2',
        '3306'
      );
      $this->assertTrue(true);
    }//end testApplicationDeployingIntoTargetDatabase()


    /**
     * testApplicationDeployingDefaultApplicationDatabase
     *
     * Chargement des paramètres depuis un fichier au format JSON valide.
     */
    public function testApplicationDeployingDefaultApplicationDatabase(): void
    {
      Application::loadDBSettings('./../tests/datasets/AppSettingsFile_02-valid.json');
      Application::deploySchemaToDefaultAppliDB(
        'root',
        'dev'
      );
      $this->assertTrue(true);
    }//end testApplicationDeployingDefaultApplicationDatabase()

}//end class
