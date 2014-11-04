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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;

function ftp_send($_eqLogic, $_source, $_cible, $_file) {
    $filesystem = new Filesystem(new Adapter(array(
        'host' => $_eqLogic->getConfiguration('server'),
        'username' => $_eqLogic->getConfiguration('username'),
        'password' => $_eqLogic->getConfiguration('password'),
        /** optional config settings */
        'port' => $_eqLogic->getConfiguration('port'),
        'root' => $_cible,
        'passive' => ($_eqLogic->getConfiguration('passive', 0) == 1) ? false : true,
        'ssl' => ($_eqLogic->getConfiguration('ssl', 0) == 1) ? false : true,
        'timeout' => 30,
    )));

    $filesystem->put($_file, file_get_contents($_source . '/' . $_file));
}
