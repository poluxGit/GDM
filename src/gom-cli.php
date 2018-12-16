<?php

/**
 * Generic Object Management -  Main Script
 * -----------------------------------------------------------------------------
 * @author poluxGit
 * -----------------------------------------------------------------------------
 */
require_once '../vendor/autoload.php';

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
     0=> ["JSON_Filepath", "Model to import filepath (JSON Format)"]
   ],
   'GEN_DB'  => [
     0=> ["JSON_Filepath", "Model to import filepath (JSON Format)"],
     1=> ["SQL_OutputFilepath", "Output SQL filepath (SQL Format)"]
   ],
   'LOAD_OBJ' => [
     0=> ["OBJ_TID", "TID of Object to load."]
   ],
   'LOAD_OBD' => [
     0=> ["OBD_TID", "TID of OBject Definition to load."]
   ],
   'LOAD_MDL' => [
     0=> ["MDL_TID", "TID of Model to load."]
   ],
   'LOAD_LNKD' => [
     0=> ["LNKD_TID", "TID of LiNK Definition."]
   ],
   'LOAD_LNKMD' => [
     0=> ["LNKMD_TID", "TID of LiNKMeta Definition to load."]
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
   $lsMessage  = "Syntaxe: php gom-cli.php {ACTION} {ACTION_PARAMETERS}\n";
   $lsMessage .= "avec:\n";
   // Pour chacune des actions
   foreach (PROG_ACTIONS as $key => $ActionValue) {
      $lsMessage .= " Action: $key \n";
      $lsMessageBis = " Exemple: php gom.php $key";
      // Actions parameters
      foreach ($ActionValue as $value) {
         $lsMessage .= " --> $value[0] : $value[1]\n";
         $lsMessageBis .= " $value[0] ";
      }
      $lsMessage .= $lsMessageBis."\n"."\n";
   }
   return $lsMessage;
 }//end generateMessageProgHelp()

function generatePROGName()
{
  return PROG_NAME."@".PROG_VERSION;
}

function generateHeaderForCommandLine($psAction)
{
  $lsMessage =  "*************************************************************\n";
  $lsMessage .= " ".generatePROGName()." - Action '$psAction'.\n";
  $lsMessage .= "*************************************************************\n";
  return $lsMessage;
}

function generateFooterForCommandLine($psAction)
{
  $lsMessage =  "\n*************************************************************\n";
  $lsMessage .= " ".generatePROGName()." - Fin Action '$psAction'.\n";
  $lsMessage .= "*************************************************************\n";
  return $lsMessage;
}
/* -------------------------------------------------------------------------- */

/**
 * Traitement Principal du script
 */
function main($argc, $argv)
{
  try{

    $lbHeaderGenerated = false;

    // Action spécifiée dans l'appel ?
    if($argc < 2)
    {
      throw new Exception(generatePROGName()." : Nombre d'arguments minimal non atteint ! \n".generateMessageProgHelp());
    }
    $lsAction = strtoupper($argv[1]);

    // Validation de l'action
    if (!array_key_exists(strtoupper($lsAction), PROG_ACTIONS)) {
      throw new Exception(generatePROGName()." : Action '$lsAction' non reconnue ! \n".generateMessageProgHelp());
    }

    // Validation du nombre de paramètre
    $liNbParamActionAttendu = count(PROG_ACTIONS[strtoupper($lsAction)]);
    $liNbParamActionReel = ($argc-2);
    if ( $liNbParamActionAttendu != $liNbParamActionReel ) {
      throw new Exception(generatePROGName()." : Nombre de paramètres pour l'action '$lsAction' invalid (Donné(s): $liNbParamActionReel | Attendu : $liNbParamActionAttendu) !\n".generateMessageProgHelp());
    }

    echo generateHeaderForCommandLine(strtoupper($lsAction));
    $lbHeaderGenerated = true;

    // Aiguillage principal
    switch (strtoupper($lsAction)) {
      case 'IMP_MDL':
        // IMPORT DE MODEL
        // fichier JSON existant ?
        if(!file_exists($argv[2]))
        {
          throw new Exception(PROG_NAME." : Le fichier source ne peux pas être atteint. (i.e : '".$argv[2]."')");
        } else {
          $lStrJSONFile = $argv[2];
        }
        // fichier JSON  bien formé ?
        $json_data = file_get_contents($lStrJSONFile);
        $json_data = stripslashes($json_data);
        $ljsonContent = json_decode($json_data , true);
        if($ljsonContent === NULL)
        {
          $lstrErrJSON = json_last_error_msg();
          throw new Exception(PROG_NAME." : Le fichier source n'est pas interprétable. (i.e : '".$lStrJSONFile."')\nJSON Error => ".$lstrErrJSON);
        }
        echo "- Démarrage de l'import...\n";
        GOM\Core\Application::importModelFromJSONData($ljsonContent);
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
          $loObj = GOM\Core\DataFactory::getObjectDefinition($argv[2]);
          print_r($loObj);
          break;
      case 'LOAD_MDL':
          // Chargement d'un modele
          $loObj = GOM\Core\DataFactory::getModel($argv[2]);
          print_r($loObj);
          break;
      case 'LOAD_LNKD':
          // Chargement d'une définition de lien
          $loObj = GOM\Core\DataFactory::getLinkDefinition($argv[2]);
          print_r($loObj);
          break;
      case 'LOAD_LNKMD':
          // Chargement d'une définition de metadonnées de lien
          $loObj = GOM\Core\DataFactory::getLinkMetaDefinition($argv[2]);
          print_r($loObj);
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
    if($lbHeaderGenerated)
      echo  generateFooterForCommandLine($lsAction);
  }
}//end main()

// INITIALISATION GOM
GOM\Core\Application::loadDBSettings('./gom-settings.json');

// Démarrage du traitement
/* -------------------------------------------------------------------------- */
main($argc, $argv);
