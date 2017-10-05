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

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
include_file('core', 'datatransfert', 'class', 'datatransfert');
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

function timesort($a, $b)
{
    return strcmp($a["time"], $b["time"]);
}

class DataTransfert {
  static function withEqLogic($_eqLogic) {
    \log::add('datatransfert', 'error', "withEqLogic unimplemented");
  }

  function put($_source, $_cible) {
    \log::add('datatransfert', 'error', "put unimplemented");
  }
  
  function ls($_cible) {
    \log::add('datatransfert', 'error', "list unimplemented");
    return array();
  }
  
  function remove($_cible) {
    \log::add('datatransfert', 'error', "remove unimplemented");
  }
  
  function removeOlder($_cible, $numberToKeep) {
    \log::add('datatransfert', 'info', "removing " . $numberToKeep . " older files in " . $_cible);
    $ls = $this->ls($_cible);
    foreach ($ls as $val) {
      if ($val["time"] == null) {
        \log::add('datatransfert', 'info', "time not implemented. clean skipped.");
        return;
      }
    }
    usort($ls, "\\DataTransfert\\timesort");
    $todel = array_slice($ls, 0, -$numberToKeep);
    foreach ($todel as $val) {
      $this->remove($_cible . "/" . $val["name"]);
    }
  }
}

class Fly extends DataTransfert {
  function basename($_file) {
    if ($this->forceBase)
      return basename($_file);
    return $_file;
  }
  
  function dirname($_file) {
    if ($this->forceBase)
      return trim(dirname($_file), "/");
    return "";
  }

  function put($_source, $_cible) {
    if (isset($this->removeDupes) && $this->removeDupes == true) {
      foreach ($this->ls(dirname($_cible)) as $val) {
	    if ($val["alias"] == basename($_cible)) {
		  $this->remove(dirname($_cible) . "/" . $val["name"]);
		}
      }
    }

    \log::add('datatransfert', 'info', "uploading " . $_source . " to " . $_cible);
    $filesystem = $this->getFly($this->dirname($_cible));
    $filesystem->putStream($this->basename($_cible), fopen($_source, 'r'));
	\log::add('datatransfert', 'info', "upload " . $_source . " to " . $_cible . "complete !");
  }
  
  function timestamp($_val) {
    return $_val["timestamp"];
  }
  
  function ls($_source) {
    $filesystem = $this->getFly($this->dirname($_source));
    $res = array();
    foreach ($filesystem->listContents($this->basename($_source), false) as $val) {
      if ($val["type"] == "file") {
        \log::add('datatransfert', 'debug', "list " . json_encode($val));
        array_push($res, array("name" => $val["basename"], "alias" => $val["filename"], "time" => $this->timestamp($val)));
      }
    }
	\log::add('datatransfert', 'debug', "list " . json_encode($res));
    return $res;
  }
  
  function remove($_cible) {
    \log::add('datatransfert', 'info', "removing " . $_cible);
    $filesystem = $this->getFly($this->dirname($_cible));
    $filesystem->delete($this->basename($_cible));
  }
}
?>
