<?php

namespace GOM\Core\Data\Internal;

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
   * IDEA Typage des champs par sous classe avec classe Factory Collection management
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
   * @var array([],[])
   * @internal [[ 'name' => 'Champs1', 'type' => 'string', 'sql_name' => 'TOTO' ...],[ 'Name' => 'Champs2', 'Type' => 'date', ...]]
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
   * @param string $p_sTID  TID de l'objet à charger (si null => Mode Création)
   */
  public function __construct(string $p_sTID=NULL,string $p_sTablename,\PDO $p_oDBConn = NULL)
  {
    // Connection BD passée en paramètres ?
    if($p_oDBConn === NULL)
		{
      $this->_oPDODBConnection = self::$_oPDOCommonDBConnection;
    } else {
      $this->_oPDODBConnection = $p_oDBConn;
    }

    $this->_sTablename = $p_sTablename;

    // TID définie ?
    if($p_sTID !== NULL)
    {
      // Mode MAJ !
      $this->_sTID = $p_sTID;
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
   * @param \PDO  $p_oPDOConnection   Instance d'objet PDO.
   */
  public function setPDOConnection(\PDO $p_oPDOConnection)
  {
    $this->_oPDODBConnection = $p_oPDOConnection;
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
    return array_filter($this->_aFieldValue,function ($p_elem){ return $p_elem!== NULL;});
  }//end getFieldsToUpdate()

  // ************************************************************************ //
  // METHODES
  // ************************************************************************ //
  /**
   * Définie la valeur d'un champ d'un objet depuis son nom SQL
   *
   * @param string  $p_sFieldSQLName  Nom SQL du champs à mettre à jour.
   * @param mixed   $p_xNewValue      Nouvelle Valeur du champs
   */
  public function setFieldValueFromSQLName(string $p_sFieldSQLName,mixed $p_xNewValue)
  {
    $l_sFieldName = $this->getFieldNameFromSQLName($p_sFieldSQLName);
    // Définition de champs trouvée ?
    if ($l_sFieldName !== NULL) {
      $this->_aFieldValue[$p_sFieldSQLName] = $p_xNewValue;
    } else {
      // TODO Faire une classe Exception spécifique 'FieldDefinitionNotExists'
      $l_sMsgException = sprintf("Le champs SQL '%s' n'est pas défini sur l'objet courant.",$p_sFieldSQLName);
      throw new \Exception($l_sMsgException);
    }
  }//end setFieldValueFromSQLName()

  /**
   * Définie la valeur d'un champ d'un objet depuis son nom
   *
   * @param string  $p_sFieldName     Nom du champs à mettre à jour.
   * @param mixed   $p_xNewValue      Nouvelle Valeur du champs
   */
  public function setFieldValueFromName(string $p_sFieldName,mixed $p_xNewValue)
  {
    // TODO Validation du type d'attribut
    if ($this->isFieldDefinitionExists($p_sFieldName)) {
      $l_sSQLFieldName = $this->getSQLFieldNameFromName($p_sFieldName);
      $this->_aFieldValue[$l_sSQLFieldName] = $p_xNewValue;
    } else {
      // TODO Faire une classe Exception spécifique 'FieldDefinitionNotExists'
      $l_sMsgException = sprintf("Le champs '%s' n'est pas défini sur l'objet courant.",$p_sFieldName);
      throw new \Exception($l_sMsgException);
    }
  }//end setFieldValueFromName()

  /**
   * Ajoute la definition d'un champ sur l'objet courant
   *
   * @param string  $p_sFieldName     Nom du champ
   * @param string  $p_sSQLFieldName  Nom SQL du champs
   * @param string  $p_sFieldType     Type du champs (string,date,int,double...) IDEA
   * @param string  $p_sFieldLabel    Libellé du champs (Optionnel)
   */
  public function addFieldDefinition(string $p_sFieldName, string $p_sSQLFieldName, string $p_sFieldType, string $p_sFieldLabel = null)
  {
      $l_bNameAlreadyExists = count($this->getFieldDefinitionByAttrValue('sql_name',$p_sSQLFieldName))>0;
      // Un champs de même nom est-il déjà défini ?
      if ($l_bNameAlreadyExists) {
        // TODO Faire une classe Exception spécifique 'FieldDefinitionNotExists'
        $l_sMsgException = sprintf("Le nom de champs '%s' est déjà défini pour l'objet.",$p_sFieldName);
        throw new \Exception($l_sMsgException);
      }

      // Ajout du nouveau champs !
      $this->_aFieldDefinition[] = [
        'name' => $p_sFieldName,
        'sql_name' => $p_sSQLFieldName,
        'type' => $p_sFieldType,
        'label' => $p_sFieldLabel
      ];
  }//end addFieldDefinition()

  /**
   * Retourne vrai si la definition de champs existe
   *
   * @param string $p_sFieldName    Nom du champs demandé.
   * @return bool   TRUE si la définition est trouvée.
   */
  public function isFieldDefinitionExists(string $p_sFieldName)
  {
      return $this->getSQLFieldNameFromName($p_sFieldName)!==NULL;
  }//end isFieldDefinitionExists()

  /**
   * Retourne le nom sql du champs à partir de son nom
   *
   * @param string  $p_sSQLFieldName  Nom SQL du champs
   * @return string   Nom SQL du champs (NULL si non trouvé)
   */
  public function getSQLFieldNameFromName(string $p_sFieldName)
  {
    $l_sResultat = NULL;
    $l_aFieldDefinition = $this->getFieldDefinitionByAttrValue('name',$p_sFieldName);
    if (array_key_exists('sql_name',$l_aFieldDefinition)) {
      $l_sResultat = $l_aFieldDefinition['sql_name'] ;
    }
    return $l_sResultat;
  }//end getSQLFieldNameFromName()

  /**
   * Retourne le nom du champs à partir de son nom SQL
   *
   * @param string  $p_sSQLFieldName  Nom SQL du champs
   * @return string   Nom du champs (NULL si non trouvé)
   */
  public function getFieldNameFromSQLName(string $p_sSQLFieldName)
  {
    $l_sResultat = NULL;
    $l_aFieldDefinition = $this->getFieldDefinitionByAttrValue('sql_name',$p_sFieldName);
    if (array_key_exists('name',$l_aFieldDefinition)) {
      $l_sResultat = $l_aFieldDefinition['name'] ;
    }
    return $l_sResultat;
  }//end getFieldNameFromSQLName()

  /**
   * Retourne le tableau de definition du champs à partir de son nom
   *
   * @param string  $p_sFieldName  Nom SQL du champs
   * @return array(string)   Definition du champs
   */
  protected function getFieldDefinitionFromName(string $p_sFieldName)
  {
      $l_aFieldDefinition = $this->getFieldDefinitionByAttrValue('name',$p_sFieldName);
      return $l_aFieldDefinition;
  }//end getFieldDefinitionFromName()


  /**
   * Retourne le tableau de definition du champs à partir de son nom SQL
   *
   * @param string  $p_sSQLFieldName  Nom SQL du champs
   * @return string   Nom du champs (NULL si non trouvé)
   */
  public function getFieldDefinitionFromSQLName(string $p_sSQLFieldName)
  {
    $l_aFieldDefinition = $this->getFieldDefinitionByAttrValue('sql_name',$p_sSQLFieldName);
    return $l_aFieldDefinition;
  }//end getFieldDefinitionFromSQLName()

  /**
   * Retourne la définition d'un champ de l'objet
   *
   * @param string  $p_sFieldAttrName         Nom interne de l'attribut (name,sql_name,type,label).
   * @param string  $p_sFieldAttrValue        Valeur à rechercher sur l'attribut.
   * @param bool    $p_bAllowMultipleResult   (Optionnel) Permet de renvoyer plusieurs résultat (défaut : FALSE)
   *
   * @return array(mixed)   Definition du champs
   */
  public function getFieldDefinitionByAttrValue( $p_sFieldAttrName, $p_sFieldAttrValue, $p_bAllowMultipleResult = FALSE)
  {
    if (count($this->_aFieldDefinition) > 0) {
      //DEBUG echo sprintf("\n--> Field to search '%s' with value '%s'.",$p_sFieldAttrName,$p_sFieldAttrValue);
      $l_aFieldDefinition = []; //array_filter($this->_aFieldDefinition, function($p_elem){ return strtolower($p_elem[$p_sFieldAttrName])==strtolower($p_sFieldAttrValue);} );

      foreach ($this->_aFieldDefinition as $l_skey => $l_aValue){
        if(array_key_exists($p_sFieldAttrName,$l_aValue) && strtolower($l_aValue[$p_sFieldAttrName])==strtolower($p_sFieldAttrValue))
        {
          $l_aFieldDefinition[$l_skey] = $l_aValue;
        }
      }

      // Plus de 1 résultat => Exception !
      if (count($l_aFieldDefinition)>1 && !$p_bAllowMultipleResult) {
        // TODO Faire une classe Exception spécifique 'FieldDefintionNotExists'
        $l_sMsgException = sprintf("Le nombre de résultat dont l'attribut '%s' vaut '%s' est anormal. Nb Résultat: %i.",$p_sFieldAttrName,$p_sFieldAttrValue,count($l_aFieldDefinition));
        throw new \Exception($l_sMsgException);
      }

      if (count($l_aFieldDefinition)==1) {
        return array_shift($l_aFieldDefinition);
      } else {
          return $l_aFieldDefinition;
      }
    } else {
      return [];
    }
  }//end getFieldDefinitionByAttrValue()

  /**
   * Retourne la valeur initiale du champs (dernier chargement)
   *
   * @param string $p_sFieldName  Nom du champs
   * @return mixed  Valeur initiale du champs
   */
  public function getFieldInitValueFromName($p_sFieldName)
  {
    $l_xResult = NULL;
    $l_sSQLFieldname = $this->getSQLFieldNameFromName($p_sFieldName);

    if (array_key_exists($l_sSQLFieldname,$this->_aInitFieldValue)) {
      $l_xResult = $this->_aInitFieldValue[$l_sSQLFieldname];
    }
    return $l_xResult;
  }//end getFieldInitValueFromName()

  /**
   * Retourne la valeur depuis son nom
   * (Initiale si non mise à jour, sinon valeur mise à jour)
   *
   * @param string $p_sFieldName  Nom du champs
   * @return mixed  Valeur du champs
   */
  public function getFieldValueFromName($p_sFieldName)
  {
    $l_xResult = NULL;
    $l_sSQLFieldname = $this->getSQLFieldNameFromName($p_sFieldName);

    if (array_key_exists($l_sSQLFieldname,$this->_aFieldValue) && $this->_aFieldValue[$l_sSQLFieldname] !== NULL ) {
      $l_xResult = $this->_aFieldValue[$l_sSQLFieldname];
    } else {
      $l_xResult = $this->getFieldInitValueFromName($p_sFieldName);
    }

    return $l_xResult;
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
      $l_sMsgException = sprintf("Un objet sans TID ne peut pas être chargé. (i.e : mode creation)");
      throw new \Exception($l_sMsgException);
    }

    // Chargement de l'objet depuis la BD!
    try {
        // DB connection active ?
        if ($this->_oPDODBConnection === NULL) {
          // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
          $l_sMsgException = sprintf("La connexion à la base de données n'est pas définie.");
          throw new \Exception($l_sMsgException);
        } else {
          $l_aWhereCondition  = ['TID = :tid'];
          $l_sSQLQuery        = $this->buildSQLSelectQuery($l_aWhereCondition);
          $l_oPDOStat         = $this->_oPDODBConnection->prepare($l_sSQLQuery);

          $l_oPDOStat->bindValue(
            ':tid',
            $this->getTID(),
            \PDO::PARAM_STR
          );

          // Execution de la requete
          $l_oPDOStat->execute();
          $l_aResultat = $l_oPDOStat->fetchAll(\PDO::FETCH_ASSOC);

          // Aucun résultat ?
          if (count($l_aResultat)==0) {
            // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
            $l_sMsgException = sprintf("L'Objet avec le TID '%s' n'a pu être chargé depuis la table '%s'.",$this->getTID(),$this->_sTablename);
            throw new \Exception($l_sMsgException);
          }

          // Plusieurs résultats !
          if (count($l_aResultat) > 1) {
            // TODO Faire une classe Exception spécifique 'LoadObjectInvalidDBConnection'
            $l_sMsgException = sprintf("Plusieurs objets avec le TID '%s' sont défini dans la table '%s'. Impossible de réaliser le chargement en mémoire !",$this->getTID(),$this->_sTablename);
            throw new \Exception($l_sMsgException);
          } else {
            $this->initFieldValuesArrayFromDefinition();
            $this->_aInitFieldValue = array_merge(
                $this->_aInitFieldValue,
                array_shift($l_aResultat)
            );
          }
        }
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } finally {
      // TODO To implement
    }

  }//end loadObject()

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
    $_aFieldValue = [];
    $_aInitFieldValue = [];

    // Pour chacun des champs définis !
    foreach($this->_aFieldDefinition as $l_xValue)
    {
      $l_sSQLFieldName = $l_xValue['sql_name'];
      $this->_aFieldValue[$l_sSQLFieldName] = NULL;
      $this->_aInitFieldValue[$l_sSQLFieldName] = NULL;
    }
  }//end initFieldValuesArrayFromDefinition()

  /**
   * Retourne la requête SQL de sélection de l'objet
   *
   * @param array(string) $p_aWhereCondition  Tableau des conditions SQL Where (AND)
   * @return  string  Requete SQL de sélection
   */
  protected function buildSQLSelectQuery($p_aWhereCondition)
  {
    $l_sSQLQuery = "SELECT ";

    // SELECT Part
    $l_aFieldDefinitionSQLName = array_map(function($p_elem) { return $p_elem['sql_name'];},$this->_aFieldDefinition);
    $l_sSQLQuery .= implode(", ",$l_aFieldDefinitionSQLName);

    // TODO Gestion des type de données pour formatage (... date, reel ...)

    // FROM part
    $l_sSQLQuery .= " FROM ";
    $l_sSQLQuery .= $this->_sTablename;

    // WHERE part nécessaire ?
    if (count($p_aWhereCondition)> 0) {
      $l_sSQLQuery .= " WHERE ";
      $l_sSQLQuery .= implode(", ",$p_aWhereCondition);
    }

    //DEBUG echo $l_sSQLQuery."\n";
    return $l_sSQLQuery;
  }//end buildSQLSelectQuery()

  // ************************************************************************ //
  // METHODES STATIQUES
  // ************************************************************************ //
  /**
   * Définie l'object de Connection commune à la BD
   *
   * @param \PDO  $p_oPDOConnection   Instance d'objet PDO.
   * @static
   */
  public static function setCommonPDOConnection(\PDO $p_oPDOConnection)
  {
    self::$_oPDOCommonDBConnection = $p_oPDOConnection;
  }//end setCommonPDOConnection()


  // ************************************************************************ //
  // METHODES ABSTRAITES
  // ************************************************************************ //
  /**
   * Initialisation des définitions de champs de l'objet
   * @abstract
   */
  public abstract function initFieldDefinition();

}//end class
