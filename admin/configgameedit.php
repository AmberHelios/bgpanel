<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * LICENSE:
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @categories	Games/Entertainment, Systems Administration
 * @package		Bright Game Panel
 * @author		warhawk3407 <warhawk3407@gmail.com> @NOSPAM
 * @copyleft	2013
 * @license		GNU General Public License version 3.0 (GPLv3)
 * @version		0.4.8
 * @link		http://www.bgpanel.net/
 */



$page = 'configgameedit';
$tab = 5;
$isSummary = TRUE;

if ( !isset($_GET['id']) || !is_numeric($_GET['id']) )
{
	exit('Error: GameID error.');
}

$gameid = $_GET['id'];
$return = 'configgameedit.php?id='.urlencode($gameid);


require("../configuration.php");
require("./include.php");
include("../libs/lgsl/lgsl_protocol.php");


$title = T_('Edit Game');

$gameid = mysql_real_escape_string($_GET['id']);


if (query_numrows( "SELECT `game` FROM `".DBPREFIX."game` WHERE `gameid` = '".$gameid."'" ) == 0)
{
	exit('Error: GameID is invalid.');
}


$rows = query_fetch_assoc( "SELECT * FROM `".DBPREFIX."game` WHERE `gameid` = '".$gameid."' LIMIT 1" );


include("./bootstrap/header.php");


/**
 * Notifications
 */
include("./bootstrap/notifications.php");


if (query_numrows( "SELECT `serverid` FROM `".DBPREFIX."server` WHERE `gameid` = '".$gameid."'" ) != 0)
{
?>
			<div class="alert alert-block">
				<h4 class="alert-heading">"<?php echo htmlspecialchars($rows['game'], ENT_QUOTES); ?>" <?php echo T_('is currently in use by game servers!'); ?></h4>
			</div>
<?php
}


?>
			<div class="well">
				<form method="post" action="configgameprocess.php">
					<input type="hidden" name="task" value="configgameedit" />
					<input type="hidden" name="gameid" value="<?php echo $gameid; ?>" />
					<label><?php echo T_('Game Name'); ?></label>
						<input type="text" name="gameName" class="span4" value="<?php echo htmlspecialchars($rows['game'], ENT_QUOTES); ?>">
					<label><?php echo T_('Status'); ?></label>
						<div class="btn-group" data-toggle="buttons-radio" style="margin-bottom: 5px;">
							<a class="btn btn-primary <?php
if ($rows['status']	== 'Active')
{
	echo 'active';
}
?>" onclick="switchRadio();return false;">Active</a>
							<a class="btn btn-primary <?php
if ($rows['status']	== 'Inactive')
{
	echo 'active';
}
?>" onclick="switchRadio();return false;">Inactive</a>
						</div>
						<div class="collapse">
							<label class="radio">
								<input id="status0" type="radio" value="Active" name="status" <?php
if ($rows['status']	== 'Active')
{
	echo "checked=\"\"";
}
?>>
							</label>
							<label class="radio">
								<input id="status1" type="radio" value="Inactive" name="status" <?php
if ($rows['status']	== 'Inactive')
{
	echo "checked=\"\"";
}
?>>
							</label>
						</div>
					<label><?php echo T_('Max Slots'); ?></label>
						<input type="text" name="maxSlots" class="span1" value="<?php echo $rows['maxslots']; ?>">
						<span class="help-inline">{slots}</span>
					<label><?php echo T_('Default Server Port'); ?></label>
						<input type="text" name="defaultPort" class="span1" value="<?php echo $rows['defaultport']; ?>">
						<span class="help-inline">{port}</span>
					<label><?php echo T_('Query Port'); ?></label>
						<input type="text" name="queryPort" class="span1" value="<?php echo $rows['queryport']; ?>">
						<span class="help-inline"><?php echo T_('Leave blank to use server port'); ?></span>
					<div class="row">
						<div class="span6">
							<div style="text-align: center; margin-bottom: 5px;">
								<span class="label"><?php echo T_('Game Configuration'); ?></span>
							</div>
							<table class="table table-striped table-bordered">
								<tr>
									<td><?php echo T_('Configuration Name'); ?></td>
									<td><?php echo T_('Associated Option'); ?></td>
									<td><?php echo T_('Alias'); ?></td>
								</tr>
<?php

$n = 1;
while ($n < 10)
{
?>
								<tr>
									<td>
										<input type="text" name="cfg<?php echo $n; ?>Name" class="span2" style="margin-bottom: 0px;" value="<?php echo htmlspecialchars($rows['cfg'.$n.'name'], ENT_QUOTES); ?>">
									</td>
									<td>
										<input type="text" name="cfg<?php echo $n; ?>" class="span4" style="margin-bottom: 0px;" value="<?php echo htmlspecialchars($rows['cfg'.$n], ENT_QUOTES); ?>">
									</td>
									<td style="padding-left: 3px;">
										<div style="text-align: center; margin-bottom: 0px;">
											<span class="help-inline" style="padding-top: 5px;">{cfg<?php echo $n; ?>}</span>
										</div>
									</td>
								</tr>
<?php
	++$n;
}
unset ($n);

?>
							</table>
						</div>
					</div>
					<label><?php echo T_('Start Command'); ?></label>
						<textarea name="startLine" class="textarea span5"><?php echo htmlspecialchars($rows['startline'], ENT_QUOTES); ?></textarea>
					<label><?php echo T_('Query Type'); ?></label>
						<select name="queryType">
<?php
//---------------------------------------------------------+

$gamequery = lgsl_type_list();

foreach ($gamequery as $key => $value)
{
	if ($key == $rows['querytype'])
	{
		$output = "\t\t\t\t\t\t<option value=\"".$key."\" selected=\"selected\">".$value." -- ".$key."</option>\r\n";
		echo $output;
	}
	else
	{
		$output = "\t\t\t\t\t\t<option value=\"".$key."\">".$value." -- ".$key."</option>\r\n";
		echo $output;
	}
}
//---------------------------------------------------------+
?>
						</select>
					<label><?php echo T_('Cache Directory'); ?></label>
						<input type="text" name="cacheDir" class="span6" value="<?php echo htmlspecialchars($rows['cachedir'], ENT_QUOTES); ?>">
					<div style="text-align: center; margin-top: 19px;">
						<button type="submit" class="btn btn-primary"><?php echo T_('Save Changes'); ?></button>
						<button type="reset" class="btn"><?php echo T_('Cancel Changes'); ?></button>
					</div>
					<div style="text-align: center; margin-top: 19px;">
						<ul class="pager">
							<li>
								<a href="configgame.php"><?php echo T_('Back to Games'); ?></a>
							</li>
						</ul>
					</div>
				</form>
			</div>
			<script language="javascript" type="text/javascript">
			function switchRadio()
			{
				var statusActive = document.getElementById('status0');
				var statusInactive = document.getElementById('status1');
				<!-- -->
				var active = statusActive.getAttribute('checked');
				var inactive = statusInactive.getAttribute('checked');
				<!-- -->
				if (active == '') {
					statusActive.removeAttribute('checked');
					statusInactive.setAttribute('checked', '');
				} else if (inactive == '') {
					statusActive.setAttribute('checked', '');
					statusInactive.removeAttribute('checked');
				}
			}
			</script>
<?php


include("./bootstrap/footer.php");
?>