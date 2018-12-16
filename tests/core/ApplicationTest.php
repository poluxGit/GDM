<?php
require_once './vendor/autoload.php';
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
}//end class
