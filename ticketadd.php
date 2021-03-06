<?php
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
$title = 'Open a New Ticket';
$page = 'ticketadd';
$tab = 5;
$isSummary = TRUE;
$return = 'ticketadd.php';


require("configuration.php");
require("include.php");
include("./bootstrap/header.php");

/**
 * Notifications
 */
if (isset($_SESSION['msg1']) && isset($_SESSION['msg2']) && isset($_SESSION['msg-type']))
{
?>
			<div class="alert alert-<?php
	switch ($_SESSION['msg-type'])
	{
		case 'block':
			echo 'block';
			break;

		case 'error':
			echo 'error';
			break;

		case 'success':
			echo 'success';
			break;

		case 'info':
			echo 'info';
			break;
	}
?>">
				<a class="close" data-dismiss="alert">&times;</a>
				<h4 class="alert-heading"><?php echo $_SESSION['msg1']; ?></h4>
				<?php echo $_SESSION['msg2']; ?>
			</div>
<?php
	unset($_SESSION['msg1']);
	unset($_SESSION['msg2']);
	unset($_SESSION['msg-type']);
}
/**
 *
 */
?>

	<div class="well">
		<form method="post" action="ticketprocess.php">
			<input type="hidden" name="task" value="ticketadd" />
			<label>Subject</label>
			<input type="text" name="subject" class="span5"/>
			<label>Server Associated</label>			
			<select name="serverID">
				<option value="none">Don't Associate a Server</option>
				<?php 
					$groups = getClientGroups($_SESSION['clientid']);
					if ($groups != FALSE)
					{
						foreach($groups as $value)
						{
							if (getGroupServers($value) != FALSE)
							{
								foreach(getGroupServers($value) as $subkey => $server)
								{
									echo '<option value="'.$server['serverid'].'">'.htmlspecialchars($server['name'], ENT_QUOTES).'</option>';
								}
							}
						}
					}
				?>
			</select>
			<label>Message</label>
			<textarea name="message" class="textarea span5"></textarea>
			<div style="text-align: center; margin-top: 19px;">
				<button type="submit" class="btn btn-primary">Open New Ticket</button>
				<div style="text-align: center; margin-top: 19px;">
						<ul class="pager">
							<li>
								<a href="tickets.php">Back</a>
							</li>
						</ul>
					</div>
			</div>
		</form>
	</div>
<?php
include("./bootstrap/footer.php"); 
?>