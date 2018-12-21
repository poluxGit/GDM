<?php

use PHPUnit\Framework\TestCase;
use GOM\Core\Data\Internal\SQLQueryGenerator;

/**
 * SQLQueryGeneratorTest
 *
 * Tests de la classe GOM\Core\Data\Internal\SQLQueryGenerator
 */
final class SQLQueryGeneratorTest extends TestCase
{
		/**
		 * testSelectSQLQueryGeneration_WithoutSelectFields
		 *
		 * Test de la génération d'une requete SQL sans définition de champs SQL
		 */
  	public function testSelectSQLQueryGeneration_WithoutSelectFields()
  	{
      $lsSQLQueryExpected = "SELECT * FROM table";
		  $lsSQLQuery = SQLQueryGenerator::buildSQLSelectQuery(NULL,'table',NULL);
      $this->assertEquals($lsSQLQueryExpected,$lsSQLQuery);
    }//end testSelectSQLQueryGeneration_WithoutSelectFields()

    /**
		 * testSelectSQLQueryGeneration_WithSelectFields
		 *
		 * Test de la génération d'une requete SQL avec définition de champs SQL
		 */
  	public function testSelectSQLQueryGeneration_WithSelectFields()
  	{
      $lasSelectField = ['COL1','COL2','COL3'];
      $lsSQLQueryExpected = "SELECT COL1, COL2, COL3 FROM table";
		  $lsSQLQuery = SQLQueryGenerator::buildSQLSelectQuery($lasSelectField,'table',NULL);
      $this->assertEquals($lsSQLQueryExpected,$lsSQLQuery);
    }//end testSelectSQLQueryGeneration_WithSelectFields()

    /**
		 * testSelectSQLQueryGeneration_WithSelectFieldsAndWhereConditions
		 *
		 * Test de la génération d'une requete SQL avec condition WHERE
		 */
  	public function testSelectSQLQueryGeneration_WithSelectFieldsAndWhereConditions()
  	{
      $lasCondition = ['COL1=COL2','COL2=COL3'];
      $lsSQLQueryExpected = "SELECT * FROM table WHERE COL1=COL2 AND COL2=COL3";
		  $lsSQLQuery = SQLQueryGenerator::buildSQLSelectQuery(NULL,'table',$lasCondition);
      $this->assertEquals($lsSQLQueryExpected,$lsSQLQuery);
    }//end testSelectSQLQueryGeneration_WithSelectFieldsAndWhereConditions()

    /**
     * testSelectSQLQueryGeneration_WithoutTable_Exception
     *
     * Test  de l'exception en cas de table non définie
     * @expectedException GOM\Core\Internal\Exception\SQLQueryGeneratorException
     */
    public function testSelectSQLQueryGeneration_WithoutTable_Exception(): void
    {
      $lsSQLQuery = SQLQueryGenerator::buildSQLSelectQuery(NULL,'',NULL);
      $this->assertTrue(true);
    }//end testSelectSQLQueryGeneration_WithoutTable_Exception()

    // public function testSomething()
    // {
    //     // Optional: Test anything here, if you want.
    //     $this->assertTrue(true, 'This should already work.');
    //
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //       'This test has not been implemented yet.'
    //     );
    // }

}//end class
