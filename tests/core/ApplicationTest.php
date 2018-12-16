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
		 */
  	public function testApplicationLoadInvalidSettingsFile(): void
  	{
			$this->expectException(\Exception::class);
			Application::loadDBSettings('./toto');
	  }//end testApplicationLoadInvalidSettingsFile()

		/**
		 * testApplicationLoadInvalidFormatSettingsFile
		 *
		 * Chargement des paramètres depuis un fichier au format JSON invalide.
		 */
  	public function testApplicationLoadInvalidFormatSettingsFile(): void
  	{
			$this->expectException(\Exception::class);
			$laObj =Application::loadDBSettings('./../datasets/AppSettingsFile_01-invalidformat.json');
			$this->assertEquals($laObj,NULL);

	  }//end testApplicationLoadInvalidFormatSettingsFile()

		/**
		 * testApplicationLoadValideSettingsFile
		 *
		 * Chargement des paramètres depuis un fichier au format JSON invalide.
		 */
  	public function testApplicationLoadValideSettingsFile(): void
  	{
			$laObj = Application::loadDBSettings('./../tests/core/AppSettingsFile_02-valid.json');
			$this->assertNotEquals($laObj,NULL);

			$this->assertEquals(array_key_exists("database",$laObj),true);
	  }//end testApplicationLoadValideSettingsFile()

}//end class
