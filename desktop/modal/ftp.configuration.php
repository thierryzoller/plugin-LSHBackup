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

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Serveur}}</label>
    <div class="col-sm-4">
        Settings have been correctly applied.
    </div>
</div>
<!--
<div class="form-group">
    <label class="col-sm-3 control-label">{{Serveur}}</label>
    <div class="col-sm-4">
        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="server"/>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Port}}</label>
    <div class="col-sm-4">
        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" value="21" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Nom d'utilisateur}}</label>
    <div class="col-sm-4">
        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username"/>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Mot de passe}}</label>
    <div class="col-sm-4">
        <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password"/>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{SSL}}</label>
    <div class="col-sm-4">
		<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="ssl"/>{{SSL}}</label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Passif}}</label>
    <div class="col-sm-4">
        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="passive" checked/>{{Passif}}</label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Mode FTPD}}</label>
    <div class="col-sm-4">
        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="ftpd" checked/>{{Mode FTPD}}</label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Progression précise}}</label>
    <div class="col-sm-4">
        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="preciseProgress"/>{{Progression précise}}</label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Vitesse limite (ko/s)}}</label>
    <div class="col-sm-4">
        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="speed" value="" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Scinder les logs}}</label>
    <div class="col-sm-4">
        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="splitLogs"/>{{Scinder les logs}}</label>
    </div>
</div>-->

