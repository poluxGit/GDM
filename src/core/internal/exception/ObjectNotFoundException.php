<?php

/**
 * ObjectNotFoundException Class definition
 *
 * Définition de la classe ObjectNotFoundException
 *
 * @category
 */
namespace GOM\Core\Internal\Exception;

use \GOM\Core\Internal\GOMException as GOMEx;

/**
 * Classe ObjectNotFoundException
 *
 * Lancée quand un objet n'as pu être trouvée.
 */
class ObjectNotFoundException extends GOMEx
{
  /**
   * Constructeur par défaut
   *
   * @param string $objectTypename  Libellé du type de l'objet non trouvé
   * @param string $objectTID       TID de l'objet non trouvé
   */
  public function __construct($objectTypename,$objectTID)
  {
    parent::__construct(4601,"Object of type '%s' with TID = '%s' not founded!",[$objectTypename,$objectTID]);
  }//end __construct()

}//end class
