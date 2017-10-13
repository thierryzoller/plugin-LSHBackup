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

class webdav extends Fly {
  function __construct($_baseUri, $_username, $_password, $_disableSslVerification, $_nextcloud) {
    $this->baseUri = $_baseUri;
    $this->username = $_username;
    $this->password = $_password;
    $this->disableSslVerification = $_disableSslVerification;
	$this->forceBase = true;
	$this->nextcloud = $_nextcloud;
  }

  static function withEqLogic($_eqLogic) {
    return new self($_eqLogic->getConfiguration('baseUri'),
	                $_eqLogic->getConfiguration('userName'),
					$_eqLogic->getConfiguration('password'),
					($_eqLogic->getConfiguration('disableSslVerification') == 1) ? true : false,
					($_eqLogic->getConfiguration('nextcloud') == 1) ? true : false);
  }
  
  function getFly($_base) {
    $settings = array(
		'baseUri' => $this->baseUri,
		'userName' => $this->username,
		'password' => $this->password
	);
	$client = new \Sabre\DAV\Client($settings);
	if ($this->disableSslVerification) {
		$client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, false);
		$client->addCurlSetting(CURLOPT_SSL_VERIFYHOST, false);
	}
	$adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, $_base);
	return new \League\Flysystem\Filesystem($adapter);
  }
  
    function ls($_source) {
		if ($this->nextcloud) {
			$baseUri = str_replace("remote.php/webdav/", "", $this->baseUri);
			$dir1 = "remote.php/webdav/";
			$dir2 = $_source;
		} else {
			$baseUri = $this->baseUri;
			$dir = $this->basename($_source);
			$dir2 = "";
		}

		$res = array();
		
		$settings = array(
			'baseUri' => "https://ncloud.zaclys.com/",
			'userName' => $this->username,
			'password' => $this->password
		);
		$client = new \Sabre\DAV\Client($settings);
		if ($this->disableSslVerification) {
			$client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, false);
			$client->addCurlSetting(CURLOPT_SSL_VERIFYHOST, false);
		}
		$adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, $dir1);
		$filesystem = new \League\Flysystem\Filesystem($adapter);
		
		foreach ($filesystem->listContents($dir2, false) as $val) {
		  if ($val["type"] == "file") {
			array_push($res, array("name" => $val["basename"], "time" => $this->timestamp($val)));
		  }
		}
		return $res;
	  }
}