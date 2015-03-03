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
 * @version		(Release 0) DEVELOPER BETA 9
 * @link		http://www.bgpanel.net/
 */



//Prevent direct access
if (!defined('LICENSE'))
{
	exit('Access Denied');
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
//MySQL


$mysql_link = mysql_connect(DBHOST,DBUSER,DBPASSWORD);
if (!$mysql_link)
{
	die('Could not connect to MySQL: '.mysql_error());
}
else
{
	$mysql_database_link = mysql_select_db(DBNAME);
	if ($mysql_database_link == FALSE)
	{
		echo "Could not connect to MySQL database";
	}
	else
	{


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+


		//---------------------------------------------------------+

		/*
		-- BrightGamePanel Database Update
		-- Version 0.4.7 to Version 0.4.8
		-- 03/03/2015
		*/

		//---------------------------------------------------------+



		//---------------------------------------------------------+
		// AJXP Update

		if (is_writable( "../ajxp/data/plugins/boot.conf/bootstrap.json" ))
		{
			$crypt_key = file_get_contents("../.ssh/passphrase");
			$api_key = substr($crypt_key, (strlen($crypt_key) / 2));

			$bootstrap = file_get_contents( "../ajxp/data/plugins/boot.conf/bootstrap.json" );
			$bootstrap = str_replace( "\"SECRET\":\"void\"", "\"SECRET\":\"".$api_key."\"", $bootstrap );

			$handle = fopen( "../ajxp/data/plugins/boot.conf/bootstrap.json" , 'w' );
			fwrite($handle, $bootstrap);
			fclose($handle);
			unset($handle);
		}

		//---------------------------------------------------------+

		//Table structure for table "Tickets"

			query_basic( "DROP TABLE IF EXISTS `".DBPREFIX."ticketmsgs`  ; " );
			query_basic( "
		CREATE TABLE `".DBPREFIX."ticketmsgs` (
			`msgid` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`ticket` int(10) unsigned NOT NULL,
			`message` text NOT NULL,
			`admin` int(10) unsigned DEFAULT NULL,
			`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`msgid`),
			KEY `otic_idx` (`ticket`),
			KEY `otic_fk_idx` (`msgid`),
			CONSTRAINT `otic_fk` FOREIGN KEY (`ticket`) REFERENCES `bgp_tickets` (`ticketid`) ON DELETE CASCADE ON UPDATE CASCADE
			) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1; " );
			
			//Table structure for table "Tickets"

			query_basic( "DROP TABLE IF EXISTS `".DBPREFIX."tickets`  ; " );
			query_basic( "
		CREATE TABLE `".DBPREFIX."tickets` (
			`ticketid` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`subject` varchar(255) NOT NULL,
			`creator` int(10) unsigned NOT NULL,
			`ts_created` timestamp NULL DEFAULT NULL,
			`ts_updated` timestamp NULL DEFAULT NULL,
			`status` tinyint(1) NOT NULL DEFAULT '1',
			`server` int(8) unsigned DEFAULT NULL,
			PRIMARY KEY (`ticketid`),
			KEY `creator_idx` (`creator`)
			) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1; " );

		//Updating data for table "config"

			query_basic( "UPDATE `".DBPREFIX."config` SET `value` = '0.4.8' WHERE `setting` = 'panelversion' LIMIT 1" );
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+


		mysql_close($mysql_link);
	}
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+


?>