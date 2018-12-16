<?php

namespace GOM\Core\Data\Internal;

/**
 * SQLQueryGenerator
 *
 * Générateur de requête SQL
 *
 * @internal Permet la génération simplifée de tableau PHP en requete SQL
 */
class SQLQueryGenerator
{
  /**
   * Construction d'une requete SQL de selection
   *
   * @static
   * @param array   $paSelectFields     Tableau des champs à sélectionner (alias => SQLSelectFieldDefinition).
   * @param string  $psFrom             Partie FROM de la requete générée
   * @param array   $paWhereCondition   Tableau des conditions SQL Where (AND)
   *
   * @return string Requete SQL de sélection générée
   */
  public static function buildSQLSelectQuery($paSelectFields, $psFrom, $paWhereCondition)
  {
    // Vars
    $lsSQLQuery = null;
    $laSQLFieldDefinitions = [];

    // ************************* SELECT PART **********************************
    $lsSQLQuery = 'SELECT ';
    foreach($paSelectFields as $lsKey => $lxValue) {
      $lsSQLDef = null;
      if (!\is_numeric($lsKey)) {
        $lsSQLDef = $lxValue." as ".$lsKey;
      } else {
        $lsSQLDef = $lxValue;
      }
      $laSQLFieldDefinitions[] = $lsSQLDef;
    }
    $lsSQLQuery .= implode(", ", $laSQLFieldDefinitions);
    // TODO Gestion des type de données pour formatage (... date, reel ...)

    // ************************* FROM PART ************************************
    $lsSQLQuery .= " FROM ";
    $lsSQLQuery .= $psFrom;

    // ************************* WHERE PART ***********************************
    // WHERE part nécessaire ?
    if (count($paWhereCondition)> 0) {
      $lsSQLQuery .= " WHERE ";
      $lsSQLQuery .= implode(", ", $paWhereCondition);
    }

    return $lsSQLQuery;
  }//end buildSQLSelectQuery()

  /**
   * Construction d'une requete SQL d'insertion
   *
   * @static
   * @param array   $paInsertFieldValue   Tableau des champs à définir dans la requete insert (clé) et leur valeur (value).
   * @param string  $psTablename          Table objet de l'insertion.
   *
   * @return string Requete SQL d'insertion générée
   */
  public static function buildSQLInsertQuery($paInsertFieldValue, $psTablename)
  {
    // Local vars
    $lsSQLQuery = null;

    // ********************** SQL INSERT QUERY GENERATION *********************
    $lsSQLQuery = sprintf(
      "INSERT INTO %s(%s) VALUES (%s)",
      $psTablename,
      implode(", ", array_keys($paInsertFieldValue)),
      implode(", ", array_values($paInsertFieldValue))
    );
    // TODO Gestion des type de données pour formatage (... date, reel ...)

    return $lsSQLQuery;
  }//end buildSQLInsertQuery()

  /**
   * Construction d'une requete SQL de mise à jour
   *
   * @static
   * @param array   $paInsertFieldValue   Tableau des champs à définir dans la requete insert (clé) et leur valeur (value).
   * @param string  $psTablename          Table objet de l'insertion.
   * @param array   $paWhereCondition     Tableau des conditions SQL Where (AND)
   *
   * @return string Requete SQL d'insertion générée
   */
  public static function buildSQLUpdateQuery($paUpdateFieldValue, $psTablename, $paWhereCondition)
  {
    // Local vars
    $lsSQLQuery = null;
    $lsSQLQuerySetPart = null;

    // ********************** SQL SET PART  GENERATION *********************
    foreach($paUpdateFieldValue as $lsKey => $lxValue) {
      $lsSQLDef = null;
      $lsSQLDef = $lsKey.' = '.$lxValue;
      $laSQLFieldDefinitions[] = $lsSQLDef;
    }
    $lsSQLQuerySetPart .= implode(", ", $laSQLFieldDefinitions);

    $lsSQLQuery = sprintf(
      "UPDATE %s SET %s",
      $psTablename,
      $lsSQLQuerySetPart
    );
    // TODO Gestion des type de données pour formatage (... date, reel ...)

    // ************************* WHERE PART ***********************************
    // WHERE part nécessaire ?
    if (count($paWhereCondition)> 0) {
      $lsSQLQuery .= " WHERE ";
      $lsSQLQuery .= implode(", ", $paWhereCondition);
    }

    return $lsSQLQuery;
  }//end buildSQLUpdateQuery()

}//end class
