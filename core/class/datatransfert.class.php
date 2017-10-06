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

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../core/php/datatransfert.inc.php';

class datatransfert extends eqLogic {
    public static function dependancy_info() {
        $return = array();
        $return['log'] = __CLASS__ . '_update';
        $return['progress_file'] = jeedom::getTmpFolder(__CLASS__) . '_progress';
        $state = '';
        if (file_exists(dirname(__FILE__) . "/../../external/rclone/rclone")) {
            $state = 'ok';
        } else {
            $state = 'nok';
        }
        $return['state'] = $state;
        return $return;
    }

    public static function dependancy_install() {
        log::remove(__CLASS__ . '_update');
        $cmd = dirname(__FILE__) . '/../../external/rclone/download.sh';
        $cmd .= ' ' . jeedom::getTmpFolder(__CLASS__) . '_progress';
        return array('script' => $cmd, 'log' => log::getPathToLog(__CLASS__ . '_update'));
    }

    public static function supportedProtocol() {
        $return = array();
        foreach (ls(dirname(__FILE__) . '/../php', '*.protocol.php') as $file) {
            $protocol = explode('.', $file);
            $return[] = $protocol[0];
        }
        return $return;
    }

    public function postSave() {
        foreach ($this->getCmd() as $cmd) {
            if (strpos($cmd->getName(), "_status") !== false)
                continue;
            $id = $cmd->getName() . "_status";
            $logic = cmd::byEqLogicIdAndLogicalId($this->getId(), $id);
            if (!is_object($logic)) {
                $cmd = new cmd();
                $cmd->setEventOnly(1);
                $cmd->setIsHistorized(1);
                $cmd->setOrder(count($this->getCmd()));
                $cmd->setEqLogic_id($this->getId());
                $cmd->setEqType('datatransfertInfo');
                $cmd->setLogicalId($id);
                $cmd->setName($cmd->getLogicalId());
                $cmd->setType('info');
                $cmd->setSubType('string');
                $cmd->setIsVisible(false);
                $cmd->save();
                $logic = $cmd;
            }
        }
    }
    
    public function setUploadStatus($name, $status) {
        $logic = cmd::byEqLogicIdAndLogicalId($this->getId(), $name . "_status");
        if ($logic)
            $logic->event($status);
        else
            \log::add('datatransfert', 'info', "missing: " . $name);
    }
}

class datatransfertCmd extends cmd {
    public static function orderFile($a, $b) {
        if ($a['datetime'] == $b['datetime']) {
            return 0;
        }
        return ($a['datetime'] < $b['datetime']) ? +1 : -1;
    }

    public static function getDirContents($dir, &$results = array()){
        $files = scandir($dir);
        foreach($files as $key => $value){
            $path = realpath($dir."/".$value);
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                self::getDirContents($path, $results);
                //$results[] = $path;
            }
        }

        return $results;
    }
    
    public function ls($dir, $filter) {
        $lst = array();
        self::getDirContents($dir, $lst);
        $res = array();
        foreach ($lst as $file)  {
            if (fnmatch($filter, $file))
                array_push($res, str_replace($dir . "/", "", $file));
        }
        return $res;
    }

    public function execute($_options = null) {
        try {
            $eqLogic = $this->getEqLogic();
            $eqLogic->setUploadStatus($this->getName(), "uploading");
            $protocol = $eqLogic->getConfiguration('protocol');
            include_file('core', $protocol . '.protocol', 'php', 'datatransfert');
            $class = call_user_func('DataTransfert\\' . $protocol . '::withEqLogic', $eqLogic);
            $cible = $this->getConfiguration('cible');
            $source = calculPath($this->getConfiguration('source'));
            $res = array();
            $filter_recentfile = $this->getConfiguration('filter_recentfile');
            //$this->ls($source, $this->getConfiguration('filter_file', '*'));
            if ($this->getConfiguration('filter_recentfile') != '') {
                $filelist = array();
                foreach ($this->ls($source, $this->getConfiguration('filter_file', '*')) as $file) {
                    $filelist[] = array(
                        'file' => $file,
                        'datetime' => filemtime($source . '/' . $file)
                    );
                }
                usort($filelist, 'datatransfertCmd::orderFile');
                foreach (array_slice($filelist, 0, $this->getConfiguration('filter_recentfile')) as $file)
                    array_push($res, $file['file']);
            } else {
                $res = $this->ls($source, $this->getConfiguration('filter_file', '*'));
            }
            foreach ($res as $file) {
                \log::add('datatransfert', 'info', "uploading " . $source . "/" . $file . " to " . $cible . "/" . $file);
                if (dirname($file) != "" && dirname($file) != null)
                    $class->mkdir(dirname($cible . "/" . $file));
                $class->put($source . "/" . $file, $cible . "/" . $file);
                \log::add('datatransfert', 'info', "upload " . $source . "/" . $file . " to " . $cible . "/" . $file . " complete !");
            }
            $eqLogic->setUploadStatus($this->getName(), "cleaning");
            if ($this->getConfiguration('remove_old') != "")
                $class->removeOlder($cible, $this->getConfiguration('remove_old'));
            $eqLogic->setUploadStatus($this->getName(), "ok");
        } catch (Exception $e) {
            $eqLogic->setUploadStatus($this->getName(), "ko");
            throw $e;
        }
    }
}

class datatransfertInfo extends cmd {
}
class datatransfertInfoCmd extends cmd {
}
?>