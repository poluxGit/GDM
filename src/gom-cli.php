<?php

/**
 * Generic Object Management -  Main Script
 * -----------------------------------------------------------------------------
 * @author poluxGit
 * -----------------------------------------------------------------------------
 */
require_once './core/Application.php';
require_once './core/data/internal/GOMObject.php';
require_once './core/data/internal/GOMObjectDefinition.php';
require_once './core/data/internal/GOMModel.php';
require_once './core/data/internal/GOMLinkDefinition.php';
require_once './core/data/internal/GOMLinkMetaDefinition.php';
require_once './core/data/internal/GOMObjectMetaDefinition.php';
require_once './core/data/DataFactory.php';

/**
 * Script d'import d'un nouveau modèle en base de données
 * -----------------------------------------------------------------------------
 * cmd ex : php gom.php IMP_MDL Jsonfile_path dsntargetBase dbuser dbpassword
 *
 */

// Constantes du Script
/* -------------------------------------------------------------------------- */
const PROG_NAME = 'GOM Cli';
const PROG_VERSION = '1.0';

// Tableau des actions & paramètres
/* -------------------------------------------------------------------------- */
const PROG_ACTIONS = [
   'IMP_MDL' => [
     0=> ["JSON_Filepath","Model to import filepath (JSON Format)"]
   ],
   'GEN_DB'  => [
     0=> ["JSON_Filepath","Model to import filepath (JSON Format)"],
     1=> ["SQL_OutputFilepath","Output SQL filepath (SQL Format)"]
   ],
   'LOAD_OBJ' => [
     0=> ["OBJ_TID","TID of Object to load."]
   ],
   'LOAD_OBD' => [
     0=> ["OBD_TID","TID of OBject Definition to load."]
   ],
   'LOAD_MDL' => [
     0=> ["MDL_TID","TID of Model to load."]
   ],
   'LOAD_LNKD' => [
     0=> ["LNKD_TID","TID of LiNK Definition."]
   ],
   'LOAD_LNKMD' => [
     0=> ["LNKMD_TID","TID of LiNKMeta Definition to load."]
   ]
 ];
/* -------------------------------------------------------------------------- */

/**
 * Retourne le message d'aide
 *
 * @return string Message d'aide générée
 */
 function generateMessageProgHelp()
 {
   $l_sMessage  = "Syntaxe: php gom-cli.php {ACTION} {ACTION_PARAMETERS}\n";
   $l_sMessage .= "avec:\n";
   // Pour chacune des actions
   foreach (PROG_ACTIONS as $key => $ActionValue) {
      $l_sMessage .= " Action: $key \n";
      $l_sMessageBis = " Exemple: php gom.php $key";
      // Actions parameters
      foreach ($ActionValue as $value) {
         $l_sMessage .= " --> $value[0] : $value[1]\n";
         $l_sMessageBis .= " $value[0] ";
      }
      $l_sMessage .= $l_sMessageBis."\n"."\n";
   }
   return $l_sMessage;
 }//end generateMessageProgHelp()

function generatePROGName()
{
  return PROG_NAME."@".PROG_VERSION;
}

function generateHeaderForCommandLine($p_sAction)
{
  $l_sMessage =  "*************************************************************\n";
  $l_sMessage .= " ".generatePROGName()." - Action '$p_sAction'.\n";
  $l_sMessage .= "*************************************************************\n";
  return $l_sMessage;
}

function generateFooterForCommandLine($p_sAction)
{
  $l_sMessage =  "\n*************************************************************\n";
  $l_sMessage .= " ".generatePROGName()." - Fin Action '$p_sAction'.\n";
  $l_sMessage .= "*************************************************************\n";
  return $l_sMessage;
}
/* -------------------------------------------------------------------------- */

/**
 * Traitement Principal du script
 */
function main($argc, $argv)
{
  try{

    $l_bHeaderGenerated = false;

    // Action spécifiée dans l'appel ?
    if($argc < 2)
    {
      throw new Exception(generatePROGName()." : Nombre d'arguments minimal non atteint ! \n".generateMessageProgHelp());
    }
    $l_sAction = strtoupper($argv[1]);

    // Validation de l'action
    if(!array_key_exists(strtoupper($l_sAction), PROG_ACTIONS)){
      throw new Exception(generatePROGName()." : Action '$l_sAction' non reconnue ! \n".generateMessageProgHelp());
    }

    // Validation du nombre de paramètre
    $l_iNbParamActionAttendu = count(PROG_ACTIONS[strtoupper($l_sAction)]);
    $l_iNbParamActionReel = ($argc-2);
    if( $l_iNbParamActionAttendu != $l_iNbParamActionReel ){
      throw new Exception(generatePROGName()." : Nombre de paramètres pour l'action '$l_sAction' invalid (Donné(s): $l_iNbParamActionReel | Attendu : $l_iNbParamActionAttendu) !\n".generateMessageProgHelp());
    }

    echo generateHeaderForCommandLine(strtoupper($l_sAction));
    $l_bHeaderGenerated = true;

    // Aiguillage principal
    switch (strtoupper($l_sAction)) {
      case 'IMP_MDL':
        // IMPORT DE MODEL
        // fichier JSON existant ?
        if(!file_exists($argv[2]))
        {
          throw new Exception(PROG_NAME." : Le fichier source ne peux pas être atteint. (i.e : '".$argv[2]."')");
        }
        else {
          $lStrJSONFile = $argv[2];
        }
        // fichier JSON  bien formé ?
        $json_data = file_get_contents($lStrJSONFile);
        $json_data = stripslashes($json_data);
        $ljsonContent = json_decode($json_data ,true);
        if($ljsonContent === NULL)
        {
          $lstrErrJSON = json_last_error_msg();
          throw new Exception(PROG_NAME." : Le fichier source n'est pas interprétable. (i.e : '".$lStrJSONFile."')\nJSON Error => ".$lstrErrJSON);
        }
        echo "- Démarrage de l'import...\n";
        GOM\Application::importModelFromJSONData($ljsonContent);
        echo "- Fin de l'import!\n";
        break;

      case 'GEN_DB':
        // GENERATION SQL du modèle - TODO
        echo "TODO - GEN_SQL\n";
        break;
      case 'LOAD_OBJ':
        // Chargement d'un objet
        echo "TODO - LOAD_OBJ\n";
        break;

      case 'LOAD_OBD':
          // Chargement d'une définition d'objet
          $l_oObj = GOM\Data\DataFactory::getObjectDefinition($argv[2]);
          print_r($l_oObj);
          break;
      case 'LOAD_MDL':
          // Chargement d'un modele
          $l_oObj = GOM\Data\DataFactory::getModel($argv[2]);
          print_r($l_oObj);
          break;
      case 'LOAD_LNKD':
          // Chargement d'une définition de lien
          $l_oObj = GOM\Data\DataFactory::getLinkDefinition($argv[2]);
          print_r($l_oObj);
          break;
      case 'LOAD_LNKMD':
          // Chargement d'une définition de metadonnées de lien
          $l_oObj = GOM\Data\DataFactory::getLinkMetaDefinition($argv[2]);
          print_r($l_oObj);
          break;
      default:
        echo "Action non reconnue.";
        break;
    }
  }
  catch(Exception $e)
  {
    echo $e->getMessage()."\n";
  }
  finally{
    if($l_bHeaderGenerated)
      echo  generateFooterForCommandLine($l_sAction);
  }
}//end main()

// INITIALISATION GOM
GOM\Application::loadDBSettings('./gom-settings.json');

// Démarrage du traitement
/* -------------------------------------------------------------------------- */
main($argc,$argv);
?>
