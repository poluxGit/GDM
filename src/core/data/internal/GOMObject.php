<?php

namespace GOM\Core\Data\Internal;

use GOM\Core;
use GOM\Core\Internal\Exception\DatabaseSQLException;
use GOM\Core\DatabaseManager;

/**
 * Représentation générique d'objet en base de données
 */

/**
 * Classe GOMObject
 */
abstract class GOMObject
{
  // ************************************************************************ //
  // ATTRIBUTS
  // ************************************************************************ //
  /**
   * Connection commune à la BD
   *
   * @staticvar
   * @var \PDO
   */
  protected static  $_oPDOCommonDBConnection = NULL;
  /**
   * Connection à la BD spécifique à l'objet
   * @var \PDO
   */
  protected  $_oPDODBConnection = NULL;
  /**
   * TID de l'objet
   * @var string
   */
  private $_sTID = NULL;
  /**
   * Tableau des valeurs des champs de l'objet
   * @var array(mixed)
   * IDEA Typage des champs par sous classe avec classe
   * Factory Collection management
   */
  private $_aFieldValue = [];

  /**
   * Tableau des valeurs initiales des champs de l'objet
   * @var array(mixed)
   */
  private $_aInitFieldValue = [];

  /**
   * Tableau des définitions SQL des champs de l'objet
   *
   * @var array([], [])
   * @internal [[ 'name' => 'Champs1',
   *            'type' => 'string',
   *            sql_name' => 'TOTO' ...],
   *            [ 'Name' => 'Champs2',
   *            'Type' => 'date', ...]]
   */
  private $_aFieldDefinition = [];

  /**
   * Table de l'objet
   *
   * @var string
   */
  private $_sTablename = NULL;

  // ************************************************************************ //
  // CONSTRUCTEUR
  // ************************************************************************ //
  /**
   * Constructeur par défaut
   *
   * Retourne un objet depuis son TID
   *
   * @param string $psTID  TID de l'objet à charger (si null => Mode Création)
   */
  public function __construct(
    string $psTID=NULL,
    string $psTablename,
    \PDO $poDBConn = NULL)
  {
    // Connection BD passée en paramètres ?
    if ($poDBConn === NULL) {
      $this->_oPDODBConnection = self::$_oPDOCommonDBConnection;
    } else {
      $this->_oPDODBConnection = $poDBConn;
    }

    $this->_sTablename = $psTablename;

    // TID définie ?
    if ($psTID !== NULL) {
      // Mode MAJ !
      $this->_sTID = $psTID;
    } else {
      // Mode Création !
    }

  }//end __construct()

  // ************************************************************************ //
  // ACCESSEURS & FLAGS MNGT
  // ************************************************************************ //
  /**
   * Définie l'objet de connection  à la base de données relative à l'objet
   *
   * @param \PDO  $poPDOConnection   Instance d'objet PDO.
   */
  public function setPDOConnection(\PDO $poPDOConnection)
  {
    $this->_oPDODBConnection = $poPDOConnection;
  }//end setPDOConnection()

  /**
   * Retourne le TID de l'objet
   *
   * @return string   TID de l'objet sinon NULL
   */
  public function getTID()
  {
    return $this->_sTID;
  }//end getTID()

  // ************************************************************************ //
  // METHODES PROTECTED
  // ************************************************************************ //
  /**
   * Retourne vrai si l'objet existe dans la base de données
   *
   * @final
   * @internal Vérification de l'existance de l'attribut TID
   * @return bool   Vrai si l'objet existe en DB
   * @access  protected
   */
  final protected function existsInDatabase()
  {
    return ($this->_sTID!==NULL);
    // TODO Ajouter deuxième niveau de chargement // depuis la base
  }//end existsInDatabase()

  /**
   * Retourne vrai si l'objet courant nécessite une maj en base
   *
   * @final
   * @internal Vérification de différence entre les statuts d'attribut
   * @return bool   Vrai si l'objet existe en DB
   * @access  protected
   */
  final protected  function needAnUpdate()
  {
    return (count($this->getFieldsToUpdate())>0);
  }//end needAnUpdate()

  /**
   * Retourne le tableau des champs à mettre à jour
   *
   * @final
   * @return array(string=>mixed)
   * @access  protected
   */
  final protected function getFieldsToUpdate()
  {
    return array_filter(
        $this->_aFieldValue,
        function ($pelem) {
            return $pelem!== NULL;
        }
    );
  }//end getFieldsToUpdate()

  // ************************************************************************ //
  // METHODES
  // ************************************************************************ //
  /**
   * Définie la valeur d'un champ d'un objet depuis son nom SQL
   *
   * @param string  $psFieldSQLName  Nom SQL du champs à mettre à jour.
   * @param mixed   $pxNewValue      Nouvelle Valeur du champs
   */
  public function setFieldValueFromSQLName(
    string $psFieldSQLName,
     $pxNewValue)
  {
    $lsFieldName = $this->getFieldNameFromSQLName($psFieldSQLName);
    // Définition de champs trouvée ?
    if ($lsFieldName !== NULL) {
      $this->_aFieldValue[$psFieldSQLName] = $pxNewValue;
    } else {
      // TODO Faire une classe Exception spécifique 'FieldDefinitionNotExists'
      $lsMsgException = sprintf(
          "Le champs SQL '%s' n'est pas défini sur l'objet courant.",
          $psFieldSQLName
      );
      throw new \Exception($lsMsgException);
    }
  }//end setFieldValueFromSQLName()

  /**
   * Définie la valeur d'un champ d'un objet depuis son nom
   *
   * @param string  $psFieldName     Nom du champs à mettre à jour.
   * @param mixed   $pxNewValue      Nouvelle Valeur du champs
   */
  public function setFieldValueFromName(string $psFieldName, $pxNewValue)
  {
    // TODO Validation du type d'attribut
    if ($this->isFieldDefinitionExists($psFieldName)) {
      $lsSQLFieldName = $this->getSQLFieldNameFromName($psFieldName);
      $this->_aFieldValue[$lsSQLFieldName] = $pxNewValue;
    } else {
      // TODO Faire une classe Exception spécifique 'FieldDefinitionNotExists'
      $lsMsgException = sprintf(
          "Le champs '%s' n'est pas défini sur l'objet courant.",
          $psFieldName
      );
      throw new \Exception($lsMsgException);
    }
  }//end setFieldValueFromName()

  /**
   * Ajoute la definition d'un champ sur l'objet courant
   *
   * @param string  $psFieldName     Nom du champ
   * @param string  $psSQLFieldName  Nom SQL du champs
   * @param string  $psFieldType     Type du champs (string, date, int,...)
   * @param string  $psFieldLabel    Libellé du champs (Optionnel)
   */
  public function addFieldDefinition( string $psFieldName,
    string $psSQLFieldName,
    string $psFieldType,
    string $psFieldLabel = null)
  {
      $lbNameAlreadyExists = count(
        $this->getFieldDefinitionByAttrValue(
            'sql_name',
            $psSQLFieldName
        )
      )>0;
      // Un champs de même nom est-il déjà défini ?
      if ($lbNameAlreadyExists) {
        // TODO Faire une classe Exception spécifique 'FieldDefinitionNotExists'
        $lsMsgException = sprintf(
            "Le nom de champs '%s' est déjà défini pour l'objet.",
            $psFieldName
        );
        throw new \Exception($lsMsgException);
      }

      // Ajout du nouveau champs !
      $this->_aFieldDefinition[] = [
        'name' => $psFieldName,
        'sql_name' => $psSQLFieldName,
        'type' => $psFieldType,
        'label' => $psFieldLabel
      ];
  }//end addFieldDefinition()

  /**
   * Retourne vrai si la definition de champs existe
   *
   * @param string $psFieldName    Nom du champs demandé.
   * @return bool   TRUE si la définition est trouvée.
   */
  public function isFieldDefinitionExists(string $psFieldName)
  {
      return $this->getSQLFieldNameFromName($psFieldName)!==NULL;
  }//end isFieldDefinitionExists()

  /**
   * Retourne le nom sql du champs à partir de son nom
   *
   * @param string  $psSQLFieldName  Nom SQL du champs
   * @return string   Nom SQL du champs (NULL si non trouvé)
   */
  public function getSQLFieldNameFromName(string $psFieldName)
  {
    $lsResultat = NULL;
    $laFieldDefinition = $this->getFieldDefinitionByAttrValue(
        'name',
        $psFieldName
    );
    if (array_key_exists('sql_name', $laFieldDefinition)) {
      $lsResultat = $laFieldDefinition['sql_name'] ;
    }
    return $lsResultat;
  }//end getSQLFieldNameFromName()

  /**
   * Retourne le nom du champs à partir de son nom SQL
   *
   * @param string  $psSQLFieldName  Nom SQL du champs
   * @return string   Nom du champs (NULL si non trouvé)
   */
  public function getFieldNameFromSQLName(string $psSQLFieldName)
  {
    $lsResultat = NULL;
    $laFieldDefinition = $this->getFieldDefinitionByAttrValue(
      'sql_name',
      $psSQLFieldName
    );
    if (array_key_exists('name', $laFieldDefinition)) {
      $lsResultat = $laFieldDefinition['name'] ;
    }
    return $lsResultat;
  }//end getFieldNameFromSQLName()

  /**
   * Retourne le tableau de definition du champs à partir de son nom
   *
   * @param string  $psFieldName  Nom SQL du champs
   * @return array(string)   Definition du champs
   */
  protected function getFieldDefinitionFromName(string $psFieldName)
  {
      $laFieldDefinition = $this->getFieldDefinitionByAttrValue(
          'name',
          $psFieldName
      );
      return $laFieldDefinition;
  }//end getFieldDefinitionFromName()


  /**
   * Retourne le tableau de definition du champs à partir de son nom SQL
   *
   * @param string  $psSQLFieldName  Nom SQL du champs
   * @return string   Nom du champs (NULL si non trouvé)
   */
  public function getFieldDefinitionFromSQLName(string $psSQLFieldName)
  {
    $laFieldDefinition = $this->getFieldDefinitionByAttrValue(
        'sql_name',
        $psSQLFieldName
    );
    return $laFieldDefinition;
  }//end getFieldDefinitionFromSQLName()

  /**
   * Retourne la définition d'un champ de l'objet
   *
   * @param string  $psFieldAttrName
   *    Nom interne de l'attribut (name, sql_name, type, label).
   * @param string  $psFieldAttrValue        Valeur à rechercher sur l'attribut.
   * @param bool    $pbAllowMultipleResult
   *    (Optionnel) Permet de renvoyer plusieurs résultat (défaut : FALSE)
   *
   * @return array(mixed)   Definition du champs
   */
  public function getFieldDefinitionByAttrValue(
    $psFieldAttrName,
    $psFieldAttrValue,
    $pbAllowMultipleResult = FALSE
  )
  {
    if (count($this->_aFieldDefinition) > 0) {
      $laFieldDefinition = [];

      foreach ($this->_aFieldDefinition as $lskey => $laValue){
        if(array_key_exists($psFieldAttrName, $laValue)
            && strtolower($laValue[$psFieldAttrName])==strtolower(
              $psFieldAttrValue
              ))
        {
          $laFieldDefinition[$lskey] = $laValue;
        }
      }

      // Plus de 1 résultat => Exception !
      if (count($laFieldDefinition)>1 && !$pbAllowMultipleResult) {
        // TODO Faire une classe Exception spécifique 'FieldDefintionNotExists'
        $lsMsgException = sprintf(
          "Le nombre de résultat dont l'attribut '%s' vaut '%s'
          est anormal. Nb Résultat: %i.",
          $psFieldAttrName,
          $psFieldAttrValue,
          count($laFieldDefinition)
        );
        throw new \Exception($lsMsgException);
      }

      if (count($laFieldDefinition)==1) {
        return array_shift($laFieldDefinition);
      } else {
          return $laFieldDefinition;
      }
    } else {
      return [];
    }
  }//end getFieldDefinitionByAttrValue()

  /**
   * Retourne la valeur initiale du champs (dernier chargement)
   *
   * @param string $psFieldName  Nom du champs
   * @return mixed  Valeur initiale du champs
   */
  public function getFieldInitValueFromName($psFieldName)
  {
    $lxResult = NULL;
    $lsSQLFieldname = $this->getSQLFieldNameFromName($psFieldName);

    if (array_key_exists($lsSQLFieldname, $this->_aInitFieldValue)) {
      $lxResult = $this->_aInitFieldValue[$lsSQLFieldname];
    }
    return $lxResult;
  }//end getFieldInitValueFromName()

  /**
   * Retourne la valeur depuis son nom
   * (Initiale si non mise à jour, sinon valeur mise à jour)
   *
   * @param string $psFieldName  Nom du champs
   * @return mixed  Valeur du champs
   */
  public function getFieldValueFromName($psFieldName)
  {
    $lxResult = NULL;
    $lsSQLFieldname = $this->getSQLFieldNameFromName($psFieldName);

    if (array_key_exists($lsSQLFieldname, $this->_aFieldValue)
        && $this->_aFieldValue[$lsSQLFieldname] !== NULL ) {
      $lxResult = $this->_aFieldValue[$lsSQLFieldname];
    } else {
      $lxResult = $this->getFieldInitValueFromName($psFieldName);
    }

    return $lxResult;
  }//end getFieldValueFromName()

  // ************************************************************************ //
  // METHODES 'PERSISTANCE DONNEES'
  // ************************************************************************ //
  /**
   * Chargement de l'objet depuis la base de données
   */
  final public function loadObject()
  {
    // Mode Création ? Impossible de charger l'objet...
    if ($this->_sTID === NULL) {
      // TODO Faire une classe Exception spécifique 'LoadObjectInvalidParameters'
      $lsMsgException = sprintf(
          "Un objet sans TID ne peut pas être chargé. (i.e : mode creation)"
      );
      throw new \Exception($lsMsgException);
    }

    // Chargement de l'objet depuis la BD!
    try {
        // DB connection active ?
        if ($this->_oPDODBConnection === NULL) {
          // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
          $lsMsgException = sprintf(
              "La connexion à la base de données n'est pas définie."
          );
          throw new \Exception($lsMsgException);
        } else {
          $laWhereCondition  = ['TID = :tid'];
          $lsSQLQuery        = SQLQueryGenerator::buildSQLSelectQuery(
            array_map(
                function ($pelem) {
                  return $pelem['sql_name'];
                },
                $this->_aFieldDefinition
            ),
            $this->_sTablename,
            $laWhereCondition
          );
          $loPDOStat         = $this->_oPDODBConnection->prepare($lsSQLQuery);

          $loPDOStat->bindValue(
              ':tid',
              $this->getTID(),
              \PDO::PARAM_STR
          );

          // Execution de la requete
          $loPDOStat->execute();
          $laResultat = $loPDOStat->fetchAll(\PDO::FETCH_ASSOC);

          // Aucun résultat ?
          if (count($laResultat)==0) {
            // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
            $lsMsgException = sprintf(
              "L'Objet avec le TID '%s' n'a pu être chargé depuis la table '%s'.",
              $this->getTID(),
              $this->_sTablename
            );
            throw new \Exception($lsMsgException);
          }

          // Plusieurs résultats !
          if (count($laResultat) > 1) {
            // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
            $lsMsgException = sprintf(
              "Plusieurs objets avec le TID '%s' sont défini dans la table '%s'.
              Impossible de réaliser le chargement en mémoire !",
              $this->getTID(),
              $this->_sTablename
            );
            throw new \Exception($lsMsgException);
          } else {
            $this->initFieldValuesArrayFromDefinition();
            $this->_aInitFieldValue = array_merge(
                $this->_aInitFieldValue,
                array_shift($laResultat)
            );
          }
        }
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } finally {
      // TODO To implement
    }

  }//end loadObject()

  /**
   * Sauvegarde de l'objet dans la base de données
   *
   * Gestion du mode (création ou mise à jour)
   *
   * @throws GOM\Core\Internal\Exception\DatabaseSQLException
   */
  public function saveObject()
  {
    try {
      // DB connection active ?
      if ($this->_oPDODBConnection === NULL) {
        $lsMsgException = sprintf(
            "La connexion à la base de données n'est pas définie."
        );
        throw new \Exception($lsMsgException);
      }

      $lsSQLQuery = null;
      $lsSQLQueryTID = null;

      // Mode Création ?
      if ($this->_sTID === NULL) {
        $lsSQLQuery = SQLQueryGenerator::buildSQLInsertQuery($this->_aFieldValue,$this->_sTablename);
        $lsSQLQueryTID = SQLQueryGenerator::buildSQLSelectQuery(
            ["MAX(TID) AS MTID"],
            $this->_sTablename,
            ["CDATE = (SELECT MAX(CDATE) FROM $this->_sTablename)"]
          );
        //SELECT MAX(TID) INTO lStrTID FROM A000_MDL WHERE CDATE = (SELECT MAX(CDATE) FROM A000_MDL) ;
      } else {
        $lsSQLQuery = SQLQueryGenerator::buildSQLUpdateQuery($this->_aFieldValue,$this->_sTablename,["TID = '$this->_sTID'"]);
      }

      $loPDOStat = $this->_oPDODBConnection->prepare($lsSQLQuery);
      // Execution de la requete!
      $loPDOStat->execute();

      // Aucun résultat ?
      if ($loPDOStat->rowCount()==0) {
        $lsMsgException = sprintf("L'enregistrement de l'objet dans la table '%s' a rencontré une erreur technique.",$this->_sTablename);
        throw new DatabaseSQLException($lsMsgException,$loPDOStat);
      }

      $lfinalResult = null;
      if ($lsSQLQueryTID !== NULL) {
          $loPDOStat = $this->_oPDODBConnection->prepare($lsSQLQueryTID);
          $loPDOStat->execute();
          $laResultat = $loPDOStat->fetchAll();
          if (count($laResultat)>0) {
            $lfinalResult = $laResultat[0][0];
          }
      }

      return $lfinalResult;
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } finally {
      // TODO To implement
    }
  }//end saveObject()

  // ************************************************************************ //
  // METHODES PRIVEES
  // ************************************************************************ //
  /**
   * Initialisation des tableaux des valeurs de champs depuis le tableau des definitions.
   *
   * @internal redéfinition des tableaux internes des valeurs 'initiales' et 'courantes (i.e mise à jour)'
   * @access private
   */
  private function initFieldValuesArrayFromDefinition()
  {
    // Destruction des tableaux en cours ?!
    $this->_aFieldValue = [];
    $this->_aInitFieldValue = [];

    // Pour chacun des champs définis !
    foreach($this->_aFieldDefinition as $lxValue)
    {
      $lsSQLFieldName = $lxValue['sql_name'];
      $this->_aFieldValue[$lsSQLFieldName] = NULL;
      $this->_aInitFieldValue[$lsSQLFieldName] = NULL;
    }
  }//end initFieldValuesArrayFromDefinition()

  // ************************************************************************ //
  // METHODES STATIQUES
  // ************************************************************************ //
  /**
   * Définie l'object de Connection commune à la BD
   *
   * @param \PDO  $poPDOConnection   Instance d'objet PDO.
   * @static
   */
  public static function setCommonPDOConnection(\PDO $poPDOConnection)
  {
    self::$_oPDOCommonDBConnection = $poPDOConnection;
  }//end setCommonPDOConnection()

  /**
   * Retourne un tableau de TID d'objet(s) trouvé(s)
   *
   * @param array  $paWhereCondition  Tableau des Conditions SQL (AND)
   * @param string $psTablename       Nom de la table de l'objet à cherché
   *
   * @return array  Tableau contenant les TID des objets trouvés
   */
  public static function searchObjectFromSQLConditions(
    $paWhereCondition,
    $psTablename
  )
  {
    $laResults = null;
    $lsSQLQuery = SQLQueryGenerator::buildSQLSelectQuery(
        ['TID'],
        $psTablename,$paWhereCondition
    );
    $laResults = DatabaseManager::getAllRows($lsSQLQuery);
    return $laResults;
  }//end searchObjectFromSQLConditions()

  // ************************************************************************ //
  // METHODES ABSTRAITES
  // ************************************************************************ //
  /**
   * Initialisation des définitions de champs de l'objet
   * @abstract
   */
  public abstract function initFieldDefinition();

}//end class
