<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

namespace DataTransfert;

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class local extends DataTransfert {
  function __construct() {
    $this->forceBase = false;
  }

  static function withEqLogic($_eqLogic) {
    return new self();
  }
  
  function put($_source, $_cible) {
    if(!file_exists(dirname($_cible)) || !is_dir(dirname($_cible))){
        throw new \Exception(__('Répertoire cible innexistant : ',__FILE__).dirname($_cible));
        
    }
    if(!copy($_source,$_cible)){
        throw new \Exception(__('La copie de : ',__FILE__).$_source.__(' vers : ',__FILE__).$_cible.__(' a échoué pour une raison incconue',__FILE__));
    }
  }
  
  function ls($_cible) {
	$res = array();
    foreach (scandir($_cible) as $fichier) {
	  if ($fichier != "." and $fichier != "..") {
	    array_push($res, array("name" => $fichier, "time" => filemtime($_cible . "/" . $fichier)));
      }
	}
    return $res;
  }
  
  function remove($_cible) {
    unlink($_cible);
  }
  
  function mkdir($_cible) {
    @mkdir($_cible, 0755, true);
  }
}
