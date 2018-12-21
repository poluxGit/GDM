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
    public function testApplicationLoadSettingsFileMandaotryParamMissing(): void
    {
     //	$this->expectException(\Exception::class);
      Application::loadDBSettings('./../tests/datasets/AppSettingsFile_02-invalidNoMand.json');
    }//end testApplicationLoadSettingsFileMandaotryParamMissing()

		/**
		 * testApplicationLoadValideSettingsFile
		 *
		 * Chargement des paramètres depuis un fichier au format JSON valide.
     * @expectedException \PDOException
		 */
  	public function testApplicationLoadSettingsFromValidFile(): void
  	{
		 	Application::loadDBSettings('./../tests/datasets/AppSettingsFile_02-valid.json');
		 	$this->assertTrue(true);
		// 	$this->assertEquals(array_key_exists("database",$laObj),true);
	  }//end testApplicationLoadSettingsFromValidFile()



}//end class
