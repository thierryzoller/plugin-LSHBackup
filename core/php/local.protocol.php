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

function local_send($_eqLogic, $_source, $_cible, $_file) {
    if(!file_exists($_cible) || !is_dir($_cible)){
        throw new Exception(__('Répertoire cible innexistant : ',__FILE__).$_cible);
        
    }
    if(!copy($_source . '/' . $_file,$_cible.'/'.$_file)){
        throw new Exception(__('La copie de : ',__FILE__).$_source . '/' . $_file.__(' vers : ',__FILE__).$_cible.'/'.$_file.__(' a échoué pour une raison incconue',__FILE__));
    }
}
