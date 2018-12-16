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
		 * @expectedException Exception
		 */
  	public function testApplicationLoadSettingsFromNonExistingFile(): void
  	{
			Application::loadDBSettings('./toto');
	  }//end testApplicationLoadSettingsFromNonExistingFile()

		/**
		 * testApplicationLoadInvalidFormatSettingsFile
		 *
		 * Chargement des paramètres depuis un fichier au format JSON invalide.
		 * @expectedException Exception
		 */
  	public function testApplicationLoadSettingsFromInvalidFormatedFile(): void
  	{
		 //	$this->expectException(\Exception::class);
			$laObj = Application::loadDBSettings('./../tests/datasets/AppSettingsFile_01-invalidformat.json');
			$this->assertEquals($laObj,NULL);

	  }//end testApplicationLoadSettingsFromInvalidFormatedFile()

		// /**
		//  * testApplicationLoadValideSettingsFile
		//  *
		//  * Chargement des paramètres depuis un fichier au format JSON invalide.
		//  */
  	// public function testApplicationLoadSettingsFromValidFile(): void
  	// {
		// 	$laObj = Application::loadDBSettings('./../tests/datasets/AppSettingsFile_02-valid.json');
		// 	$this->assertNotEquals($laObj,NULL);
		// 	$this->assertEquals(array_key_exists("database",$laObj),true);
	  // }//end testApplicationLoadSettingsFromValidFile()

}//end class
