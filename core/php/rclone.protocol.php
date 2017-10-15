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
require_once dirname(__FILE__) . '/../../core/php/datatransfert.inc.php';

use Rclonewrapper\Rclonewrapper;

class rclone extends DataTransfert {
  function __construct($_config, $_speed) {
    $this->config = $_config;
    $this->speed = $_speed;
	$this->rclone = $this->getRclone();
  }

  static function withEqLogic($_eqLogic) {
    return new self($_eqLogic->getConfiguration('rclone'),
                    $_eqLogic->getConfiguration('speed'));
  }
  
  function getRclone() {
    $rclone_path = dirname(__FILE__) . '/../../external/rclone/rclone';
    $rclone_config = tempnam(sys_get_temp_dir(),'rclone');
    if ($this->speed != "")
      $rclone_path = $rclone_path . " --bwlimit " . $this->speed . "k ";
    file_put_contents($rclone_config, $this->config);
    $rclone = new Rclonewrapper($rclone_path, $rclone_config);
    $rclone->setremote($rclone->listremotes()[0]);
    return $rclone;
  }
  
  function put($_source, $_cible) {
    if (isset($this->header)) {
      $this->header = true;
      $this->log('debug', "rclone version " . $rclone->version());
      $this->log('debug', "remotes " . json_encode($rclone->listremotes()));
    }
    $this->rclone->copy($_source, dirname($_cible));
  }
  
  function ls($_source) {
    $res = array();
    foreach ($this->rclone->lsl($_source) as $val1) {
      foreach ($val1 as $val) {
        if (is_array($val) && isset($val[0]) && is_array($val[0]))
          continue;
        //$this->log('debug', "list val " . json_encode($val));
        array_push($res, array("name" => $val["name"], "alias" => $val["name"], "time" => strtotime(explode(".", $val["time"])[0])));
      }
    }
    return $res;
  }
  
  function remove($_cible) {
    $this->rclone->delete($_cible);
  }
  
  function mkdir($_cible) {
    $this->log('debug', "mkdir " . $_cible);
    $this->rclone->mkdir($_cible);
  }
}
