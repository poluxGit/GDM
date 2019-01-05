<?php
namespace GOM\Core\Data;

use GOM\Core\Internal\Exception\ApplicationFileNotFoundException;

use GOM\Core\Internal\Exception\ApplicationGenericException;
use GOM\Core\Data\Model;
use GOM\Core\Data\ObjectDefinition;
use GOM\Core\Data\ObjectMetaDefinition;
use GOM\Core\Tools\StringTools;
use GOM\Core\Application;

/**
 * Classe ModelDefinition
 *
 * Définition d'un modèle de données
 *
 */
class ModelDefinition
{

  protected static $_tmpSQLFilename = 'tmp.sql';

  /**
   * @var string
   */
  public $sourceFilepath = null;
  /**
   * @var array
   */
  public $content = [];
  /**
   * @var string
   */
  protected $modelTID = null;
  /**
   * @var string
   */
  protected $modelDBTablePrefix = null;

  /**
   * @var array
   */
  protected $objectDefinitionTID = null;
  /**
   * Constructeur par défaut
   */
  public function __construct(string $inputFile)
  {
    $this->sourceFilepath = $inputFile;
  }//end __construct()

  /**
   * Charge la définition du Model
   *
   * @throw ApplicationFileNotFoundException  Fichier du modèle inexistant
   */
  public function loadModelDefinition()
  {
    // Fichier source inexistant ?
    if (!file_exists($this->sourceFilepath)) {
      throw new ApplicationFileNotFoundException(sprintf("Erreur durant l'import du schéma : le fichier source '%s' n'existe pas.",$this->sourceFilepath));
    }

    // Loading content in memory!
    $lsContent = file_get_contents($this->sourceFilepath);
    $this->content = json_decode($lsContent,true);
  }

  /**
   * Retourne TRUE si le Model est chargé
   *
   * @return boolean
   */
  public function isModelLoaded()
  {
    return ($this->content != [])?true:false;
  }//end isModelLoaded()


  /**
   * Retourne vrai si la définition du model est valide
   *
   * @return boolean  TRUE si valide, FALSE sinon
   */
  public function isValid()
  {
    $bResult = true;
    // Attributs obligatoires sur le model !
    if (!array_key_exists('modele_prefix',$this->content)
        || !array_key_exists('modele_uid',$this->content)
        || !array_key_exists('modele_version',$this->content)
        || !array_key_exists('modele_definition',$this->content)  ) {
      $bResult = false;
    }

    // Validation de la partie définition du model !
    $laSubData = $this->content['modele_definition'];
    if (!array_key_exists('objects',$laSubData)
        || !array_key_exists('links',$laSubData)
        || !array_key_exists('constraints',$laSubData)) {
      $bResult = false;
    }
    return $bResult;
  }//end isValid()


  public function getSummary()
  {
    $sResult = "";

    $sResult .= sprintf(
      "=> %s - UID : %s | Prefix : %s | Version : %s - %s \n",
      $this->getModelTitle(),
      $this->getModelUID(),
      $this->getModelPrefix(),
      $this->getModelVersion(),
      $this->getModelComment()
    );

    return $sResult;
  }//end getSummary()

  /**
   * Retourne vrai si le model existe déjà en BD
   *
   * @return boolean  TRUE si MDL existe in DB, FALSE sinon
   */
  protected function isTargetModelExists()
  {
    // TODO A implémenter
    return false;
  }//end isTargetModelExists()

  /**
   * Import du model en base de données
   */
  public function importAll()
  {
    try {
      if ($this->isValid()) {
        $this->importModel();
        return $this->importObjects($this->modelTID,$this->getModelPrefix());

      } else {
        // code...
      }
    } catch (\Exception $e) {
      echo $e->getMessage();
    }

    /**
     *
     * Création des définitions d'objet.
     * Génération des scripts SQL des tables, triggers et vues du model
     * Déploiement...
     */
  }//end importModel()



  /**
   * Renvoi le code SQL propre à la création des tables d'un objet Simple
   *
   * @param string  $psTargetDatabaseSchema   Schéma BD cible.
   * @param string  $psTablename              Nom de la table.
   * @param string  $psShortCode              Short Code de la table
   */
  protected function generateSQLScriptForSimpleObject($psTargetDatabaseSchema,$psTablename,$psShortCode)
  {
    // Vars locales!
    $lsSimpleSQLFilename      = realpath(dirname(__FILE__)).'/../../../data/deployment/internal/templates/sql-template-simple_object.sql';
    $lsTargetSQLFilename      = realpath(dirname(__FILE__)).'/./../'.self::$_tmpSQLFilename;
    $loTargetSQLFileHandler   = null;
    $lbRollBackFileExists     = false;

    $lsFileContent = null;
    $lsFileContentUpdated = null;

    try {

      // Fichier CoreSchema ...
      // ***********************************************************************
      // Ouverture Fichier destination!
      $loTargetSQLFileHandler=@fopen($lsTargetSQLFilename,'w+');

      // Echec lors de l'ouverture?
      if (!$loTargetSQLFileHandler) {
        $lsExMessage = sprintf(
            "Error during SQLScript target file initialization (target:'%s').",
            $lsTargetSQLFilename
        );
        throw new ApplicationGenericException($lsExMessage);
      }

      // SimpleObject - lecture fichier & remplacement & ecriture !
      $lsFileContent = \file_get_contents($lsSimpleSQLFilename);
      $lsFileContentUpdated = \str_replace(
          '{$target_schema}',
          $psTargetDatabaseSchema,
          $lsFileContent
        );

      $lsFileContentUpdated = \str_replace(
          '{$tmp_tablename}',
          $psTablename,
          $lsFileContentUpdated
        );

      $lsFileContentUpdated = \str_replace(
          '{$tmp_OBJCODE}',
          $psShortCode,
          $lsFileContentUpdated
        );

      \fwrite($loTargetSQLFileHandler,$lsFileContentUpdated);

    } catch (\Exception $e) {
      // TODO Rollback du fichier precedent
      throw new Exceptions\ApplicationGenericException($e->getMessage());
    } finally {
      // Fermeture du fichier!
      if (!is_null($loTargetSQLFileHandler) && $loTargetSQLFileHandler!=false) {
        \fclose($loTargetSQLFileHandler);
      }
    }

    return $lsTargetSQLFilename;

  }//end generateSQLScriptForSimpleObject()

  /**
   * Renvoi le code SQL propre à la création des tables d'un objet Complex
   *
   * @param string  $psTargetDatabaseSchema   Schéma BD cible.
   * @param string  $psTablename              Nom de la table.
   * @param string  $psShortCode              Short Code de la table
   */
  protected function generateSQLScriptForComplexObject($psTargetDatabaseSchema,$psTablename,$psShortCode)
  {
    // Vars locales!
    $lsSimpleSQLFilename      = realpath(dirname(__FILE__)).'/../../../data/deployment/internal/templates/sql-template-complex_object.sql';
    $lsTargetSQLFilename      = realpath(dirname(__FILE__)).'/./../'.self::$_tmpSQLFilename;
    $loTargetSQLFileHandler   = null;
    $lbRollBackFileExists     = false;

    $lsFileContent = null;
    $lsFileContentUpdated = null;

    try {

      // Fichier CoreSchema ...
      // ***********************************************************************
      // Ouverture Fichier destination!
      $loTargetSQLFileHandler=@fopen($lsTargetSQLFilename,'w+');

      // Echec lors de l'ouverture?
      if (!$loTargetSQLFileHandler) {
        $lsExMessage = sprintf(
            "Error during SQLScript target file initialization (target:'%s').",
            $lsTargetSQLFilename
        );
        throw new ApplicationGenericException($lsExMessage);
      }

      // SimpleObject - lecture fichier & remplacement & ecriture !
      $lsFileContent = \file_get_contents($lsSimpleSQLFilename);
      $lsFileContentUpdated = \str_replace(
          '{$target_schema}',
          $psTargetDatabaseSchema,
          $lsFileContent
        );

      $lsFileContentUpdated = \str_replace(
          '{$tmp_tablename}',
          $psTablename,
          $lsFileContentUpdated
        );

      $lsFileContentUpdated = \str_replace(
          '{$tmp_OBJCODE}',
          $psShortCode,
          $lsFileContentUpdated
        );

      \fwrite($loTargetSQLFileHandler,$lsFileContentUpdated);

    } catch (\Exception $e) {
      // TODO Rollback du fichier precedent
      throw new Exceptions\ApplicationGenericException($e->getMessage());
    } finally {
      // Fermeture du fichier!
      if (!is_null($loTargetSQLFileHandler) && $loTargetSQLFileHandler!=false) {
        \fclose($loTargetSQLFileHandler);
      }
    }

    return $lsTargetSQLFilename;

  }//end generateSQLScriptForSimpleObject()

  /**
   * Retourne le commentaire du model
   *
   * @return string   Commentaire du model, FALSE si non chargée
   */
  public function getModelComment()
  {
    if ($this->isModelLoaded())
    {
      return $this->content['modele_comments'];
    }
    else {
      return false;
    }
  }//end getModelComment()

  /**
   * Retourne le titre long du model
   *
   * @return string   Titre long du model, FALSE si non chargée
   */
  public function getModelLongTitle()
  {
    if ($this->isModelLoaded())
    {
      return $this->content['modele_ltitle'];
    }
    else {
      return false;
    }
  }//end getModelLongTitle()

  /**
   * Retourne le titre du model
   *
   * @return string   Titre du model, FALSE si non chargée
   */
  public function getModelTitle()
  {
    if ($this->isModelLoaded())
    {
      return $this->content['modele_stitle'];
    }
    else {
      return false;
    }
  }//end getModelTitle()

  /**
   * Retourne la version du model
   *
   * @return string   Version du model, FALSE si non chargée
   */
  public function getModelVersion()
  {
    if ($this->isModelLoaded())
    {
      return $this->content['modele_version'];
    }
    else {
      return false;
    }
  }//end getModelVersion()


  /**
   * Retourne l UID du model
   *
   * @return string   UID du model, FALSE si non chargée
   */
  public function getModelUID()
  {
    if ($this->isModelLoaded())
    {
      return $this->content['modele_uid'];
    }
    else {
      return false;
    }
  }//end getModelUID()

  /**
   * Retourne le prefix du model
   *
   * @return string   Prefix du model, FALSE si non chargée
   */
  public function getModelPrefix()
  {
    if ($this->isModelLoaded())
    {
      return $this->content['modele_prefix'];
    }
    else {
      return false;
    }
  }//end getModelPrefix()

  /**
   * Retourne un tableau contenant tous les objets du model
   *
   * @return array   Tableau des définitions d'objets, FALSE si non chargé
   */
  public function getModelObjects()
  {
    if ($this->isModelLoaded())
    {
      return $this->content['modele_definition']['objects'];
    }
    else {
      return false;
    }
  }//end getModelObjects()

  /**
   * Import du model depuis la définition complète du model
   *
   * Création du model en base de données.
   * @return  string  TID du model importé.
   */
  public function importModel()
  {
    // Création du model en base de données !
    $this->modelTID = Model::createNewModel(
        $this->getModelPrefix(),
        $this->getModelUID(),
        $this->getModelVersion(),
        $this->getModelTitle(),
        $this->getModelLongTitle(),
        $this->getModelComment(),
        json_encode($this->content['modele_definition'])
      );
      $this->modelDBTablePrefix = $this->getModelPrefix()."00_";

    return $this->modelTID;
  }//end importModel()

  protected function _sortObjectByUIDInteger()
  {
    // On tri les objets par leur uid-integer!
    uasort(
        $this->content['modele_definition']['objects'],
        function ($a,$b) {
          if ($a['uid-integer'] == $b['uid-integer']) {
            return 0;
          } else {
            return ($a['uid-integer'] < $b['uid-integer']) ? -1 : 1;
          }
        }
    );
  }//end _sortObjectByUIDInteger()

  /**
   * Import les objets définis pour le model
   *
   * Import des objets sur le model passé en argument. si non défini, le model
   * nouvellement importé est utilisé (i.e. $this->modelTID).
   *
   * @param string $tidModel  TID du model sur lequel importé les objets (Optionnel)
   */
  public function importObjects($tidModel,$tidPrefix)
  {
    // Tir des objets avant création!
    $this->_sortObjectByUIDInteger();

    // Init tableau des objects TID!
    $this->objectDefinitionTID = [];

    // Déclaration des objets !
    foreach ($this->getModelObjects() as $objDef) {
      $this->importObject($objDef,$tidModel,$tidPrefix);
    }
    return $this->objectDefinitionTID;
  }//end importObjects()

  /**
   * Import d'une definition d'objet
   *
   * @access protected
   * @param array   $objDefinitionArray   Définition de l'objet
   * @param string  $modelTID             TID du model cible
   * @param string  $modelPrefix          Prefix du model
   */
  protected function importObject($objDefinitionArray, $modelTID, $modelPrefix)
  {
    // TODO Validation de la complétude JSON des données sur l'objet
    $sTIDObject = null;
    $sTablenameObj = $this->getGeneratedTablenamePrefix($modelPrefix).\strtoupper(StringTools::suppr_accents($objDefinitionArray['short_title']));

    $sTIDObject = ObjectDefinition::createNewObjectDefinitionModel(
      $modelTID,
      $modelPrefix,
      $objDefinitionArray['short_title'],
      $objDefinitionArray['long_title'],
      $objDefinitionArray['description'],
      $objDefinitionArray['object_type'],
      $objDefinitionArray['tid_data']['tid_prefix'],
      $objDefinitionArray['tid_data']['tid_numLength'],
      $objDefinitionArray['tid_data']['tid_pattern'],
      $objDefinitionArray['bid_data']['bid_prefix'],
      $objDefinitionArray['bid_data']['bid_pattern'],
      $sTablenameObj
    );

    // Log de l'Ajout de la nouvelle définition d'objet.
    $this->objectDefinitionTID[$objDefinitionArray['tid_data']['tid_prefix']] = [$sTIDObject,$sTablenameObj];

    // Gestion des metadonnées
    if (array_key_exists('metadef',$objDefinitionArray) && count($objDefinitionArray['metadef'])>0) {

      //
      // print_r($objDefinitionArray['metadef']);
      //
      // // On tri les meta par Ordre !
      // uasort(
      //     $objDefinitionArray,
      //     function ($a,$b) {
      //       if ($a['order'] == $b['order']) {
      //         return 0;
      //       } else {
      //         return ($a['order'] < $b['order']) ? -1 : 1;
      //       }
      //     }
      // );

      // Pour chaque Meta données déclarées!
      foreach($objDefinitionArray['metadef'] as $metaInfo)
      {
        print_r($metaInfo);
        // TODO Validation de la complétude JSON des données sur chaque meta d'objet
        $sTIDMetaObj = null;

        $sTIDMetaObj = ObjectMetaDefinition::createNewMetaObjectDefinition(
          $sTIDObject,
          $metaInfo['bid_attr'],
          $metaInfo['short_title'],
          $metaInfo['long_title'],
          $metaInfo['comment'],
          $metaInfo['data_type'],
          $metaInfo['data_pattern'],
          $metaInfo['tid_pattern'],
          $metaInfo['bid_attr'],
          '{}'
        );

        // Log de l'Ajout de la nouvelle définition de meta d'objet.
        $this->objectDefinitionTID[$objDefinitionArray['tid_data']['tid_prefix']][] = [$sTIDMetaObj];
      }
    }

    // Génération SQL =>
    // Selon le type (Simple, Complex) ?
    if ($objDefinitionArray['object_type'] == 'Simple') {
      $sSQLScriptFilePath = $this->generateSQLScriptForSimpleObject('GDM_DEV',$sTablenameObj,$objDefinitionArray['tid_data']['tid_prefix']);
      Application::deploySQLScriptToDatabase($sSQLScriptFilePath);
    } elseif ($objDefinitionArray['object_type'] == 'Complex') {
      $sSQLScriptFilePath = $this->generateSQLScriptForComplexObject('GDM_DEV',$sTablenameObj,$objDefinitionArray['tid_data']['tid_prefix']);
      Application::deploySQLScriptToDatabase($sSQLScriptFilePath);
    } else {
      echo "Type de l'objet non valid !";
    }


  }//end importObject()

  /**
   * Genère le préfix DB appliqué aux tables des objets crées
   *
   * @return string Prefix généré
   */
  protected function getGeneratedTablenamePrefix($sModelPrefix=NULL)
  {
    $sTablePrefix = !is_null($sModelPrefix)?$sModelPrefix:$this->getModelPrefix();
    if (is_null($this->modelDBTablePrefix) || !is_null($sTablePrefix)) {
      $this->modelDBTablePrefix = $sTablePrefix."00_";
    }
    return $this->modelDBTablePrefix;
  }//end getGeneratedTablenamePrefix()

}//end class
