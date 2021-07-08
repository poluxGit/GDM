<?php

namespace GOM\Core\Data;

use GOM\Core\Data\ObjectDefinition;
use GOM\Core\Data\ObjectInstance;
use GOM\Core\Data\Model;

/**
 * Classe d'instance de metadonnées relatives à une instance d'objet
 *
 */
class ObjectMetaInstance extends Internal\GOMObject
{
  /**
   * Constructeur par défaut
   *
   * @param string $psTID   TID de l'instance de metadonnées.
   */
  public function __construct(string $psTID=NULL){
    parent::__construct($psTID, 'A100_OBMI');
    $this->initFieldDefinition();
  }//end __construct()

  /**
   * Initialisation des définitions de champs de l'objet
   *
   */
  public function initFieldDefinition()
  {
    $this->addFieldDefinition('ID', 'BID', 'string', 'Id. (BID)');
    $this->addFieldDefinition('ObjectTID', 'OBI_TID', 'string', 'OBI (TID)');
    $this->addFieldDefinition('ObjectMetaDefTID', 'OBMD_TID', 'string', 'OBMD (TID)');
    $this->addFieldDefinition('TitreCourt', 'STITLE', 'string', 'Titre Court');
    $this->addFieldDefinition('TitreLong', 'LTITLE', 'string', 'Titre Long');
    $this->addFieldDefinition('Commentaire', 'COMMENT', 'string', 'Commentaire divers');
    $this->addFieldDefinition('DataType', 'OBMI_DATA_TYPE', 'string', 'Type de la metadonnées');
    $this->addFieldDefinition('DataPattern', 'OBMI_DATA_PATTERN', 'string', 'Pattern de la metadonnées');
    $this->addFieldDefinition('ObjectMetaValue', 'OBMI_VALUE', 'string', 'Valeur de la metadonnees');
    $this->addFieldDefinition('JSONData', 'JSON_DATA', 'string', 'Données complémentaires');
    $this->addFieldDefinition('DateCreation', 'CDATE', 'date', 'Date de création');
    $this->addFieldDefinition('DateMaj', 'UDATE', 'date', 'Date de dernière maj');
    $this->addFieldDefinition('UserCreation', 'CUSER', 'string', 'Compte Utilisateur du créateur');
    $this->addFieldDefinition('UserMaj', 'UUSER', 'string', 'Compte Utilisateur de l updateur');
    $this->addFieldDefinition('EstSupprime', 'IS_DELETED', 'INT', 'Flag de suppression');
  }//end initFieldDefinition()

  /**
   * instanciateObjectMetaInstance
   *
   * Instanciation d'une metadonnées pour un nouvel objet
   *
   * @param string $psTIDOBMD   TID de la définition de metadonnées
   * @param string $psTIDOBI    TID de l'instance d'objet
   *
   * @return \GOM\Core\Data\ObjectMetaInstance
   */
  public static function instanciateObjectMetaInstance($psTIDOBMD,$psTIDOBI)
  {
    // TODO Instanication d'une metatdonnées depuis son type pour un objet données  - A implémenter
  }//end createObjectMetaInstanceFromOBD()

  /**
   * duplicateObjectMetaInstance
   *
   * Duplication des metadonnées d'un objet vers un autre
   *
   * @param string $psTIDOBISrc   TID de l'instance d'objet source.
   * @param string $psTIDOBIDst   TID de l'instance d'objet destination.
   *
   * @return boolean
   */
  public static function duplicateObjectMetaInstance($psTIDOBISrc,$psTIDOBIDst)
  {

  }//end duplicateObjectMetaInstance()

  /**
   * createNewObjectMetaInstance
   *
   * Création d'une metadonnées sur OBI
   *
   * @param string $psObjTID                TID de l'instance d'objet.
   * @param string $psObjMetaDefTID         TID de la définition de metadonnées.
   * @param string $psShortTitle            Titre Court
   * @param string $psLongTitle             Titre Long
   * @param string $psComment               Commentaires
   * @param string $psObjMetaDataType       Type d'attribut sur lien ENUM('String', 'Date', 'Datetime', 'Integer', 'Real')
   * @param string $psObjMetaDataPattern    Data Pattern sur meta objet
   * @param string $psObjMetaValue          Valeur de la metadonnées
   * @param string $psJSONData              JSON Additional Data
   */
  public static function createNewObjectMetaInstance($psObjTID,
    $psObjMetaDefTID,
    $psShortTitle,
    $psLongTitle = NULL,
    $psComment = NULL,
    $psObjMetaDataType = 'String',
    $psObjMetaDataPattern,
    $psObjMetaValue,
    $psJSONData
  )
  {
    $obmd = new ObjectMetaDefinition($psObjMetaDefTID);
    $obmd->loadObject();

    $obd = new ObjectDefinition($obmd->getFieldValueFromName('ObjectDefTID'));
    $obd->loadObject();

    $model = new Model($obmd->getFieldValueFromName('MODEL'));
    $model->loadObject();

    $obj = new ObjectInstance($psObjTID);
    $obj->loadObject();

    // Calcul du BID de la définition de meta d'objet!
    $sBID = self::generateBIDForNewOBMI(
        $model->getFieldValueFromName('CODE'),
        $obj->getFieldValueFromName('ID'),
        $obmd->getFieldValueFromName('BIDPattern')
    );

    // Création de l'objet en mémoire!
    $objMetaInstance = new ObjectMetaInstance();

    $objMetaInstance->setFieldValueFromSQLName('OBI_TID',$psObjTID);
    $objMetaInstance->setFieldValueFromSQLName('OBMD_TID',$psObjMetaDefTID);
    $objMetaInstance->setFieldValueFromSQLName('BID',$sBID);
    $objMetaInstance->setFieldValueFromSQLName('STITLE',$psShortTitle);
    $objMetaInstance->setFieldValueFromSQLName('LTITLE',$psLongTitle);
    $objMetaInstance->setFieldValueFromSQLName('COMMENT',$psComment);
    $objMetaInstance->setFieldValueFromSQLName('OBMI_DATA_TYPE',$psObjMetaDataType);
    $objMetaInstance->setFieldValueFromSQLName('OBMI_DATA_PATTERN',$psObjDefDataPattern);
    $objMetaInstance->setFieldValueFromSQLName('OBMI_VALUE',$psObjMetaValue);
    $objMetaInstance->setFieldValueFromSQLName('JSON_DATA',$psJSONData);

    // Création en base de données!
    $lsTID = $objMetaInstance->saveObject();

    // Maj des statistics !
    \GOM\Core\DatabaseManager::refreshStatisticsForTable('A100_OBMI');
    \GOM\Core\DatabaseManager::refreshStatisticsForLogsTable();

    return $lsTID;
  }//end createNewObjectMetaInstance()

  /**
   * Genere le BID d'une nouvelle définition de meta sur d'objet
   *
   * @param string $modelPrefix   Prefix du model de l'objet
   * @param string $objBIDPrefix  BID de l'objet concerné
   * @param string $metaBID       Prefix BID de la meta de l'objet
   *
   * @example self::generateBIDForNewOBMD('E3','CAT','TEST') => (string) 'OBMD.E3-CAT.TEST'
   *
   * @return string   BID généré
   */
  protected static function generateBIDForNewOBMI($modelPrefix,$objBIDPrefix,$metaBIDPrefix)
  {
    return  'OBMI.'.$modelPrefix.'-'.$objBIDPrefix.".".$metaBIDPrefix;
  }//end generateBIDForNewOBMI()

}//end class
