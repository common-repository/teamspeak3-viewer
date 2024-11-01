<?php
/*
Plugin Name: TS3 Viewer 
Plugin URI: http://wordpress.twinkclub.de 
Description: TS Viewer for Wordpress Sites - Shortcode:  <code>[ts3view ip=ServerIp:QueryPort port=9987 logo=true|false gt=true|false]</code> works with <a href="http://www.planetteamspeak.com"> TS3 PHP Framework 1.1.5-BETA</a> 
Author: Steven Hernicht
Version: 1.0 beta
Author URI: http://wordpress.twinkclub.de
License: GPL 3.0
*/

/*
		    Copyright (C) 2011  Steven Hernicht

		    	This program is free software: you can redistribute it and/or modify
		    	it under the terms of the GNU General Public License as published by
		    	the Free Software Foundation, either version 3 of the License, or
		    	(at your option) any later version.

		    	This program is distributed in the hope that it will be useful,
			but WITHOUT ANY WARRANTY; without even the implied warranty of
			MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    			GNU General Public License for more details.

    			You should have received a copy of the GNU General Public License
    			along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

class ts3view{

function shortcode($atts) {
	$serverip = ($atts['ip'] ? $atts['ip'] : '');
	$virtualserverport = ($atts['port'] ? $atts['port'] : '9987');
	$showlogo = ($atts['logo'] ? $atts['logo'] : 'false');
	$gtime = ($atts['gt'] ? $atts['gt'] : 'false'); 
	$path =  plugins_url( 'teamspeak3-viewer/images/viewer/' );
	$errormsg = "No Server - Shortcode [ts3view ip=ServerIp:QueryPort port=9987 logo=true|false gt=true|false]";


	if ($serverip == "") // Server check
	{ 
 		$out[]  ='<div id="ts3_div">';
		$out[] = $errormsg;
		$out[]  ='</div>';
                return join($out, "\n");

	}
	else { 

                // Lib load
                require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	try
	{
		// Server connect and output 
		$ts3_VirtualServer = TeamSpeak3::factory("serverquery://" . $serverip."/?server_port=". $virtualserverport."#no_query_clients");
	  	$tsserverlist = $ts3_VirtualServer->getViewer(new TeamSpeak3_Viewer_Html($path));
	  	$welcommsg = $ts3_VirtualServer["virtualserver_welcomemessage"];
		$serverlogotag = $ts3_VirtualServer["virtualserver_hostbanner_gfx_url"];
		$serverlogo = "<img src=$serverlogotag>";
		$gentime = "<br />Generated in " . TeamSpeak3_Helper_Profiler::get()->getRuntime() . " seconds";
	}
		catch(Exception $e)
		{
  		$tsserverlist = "Error (ID " . $e->getCode() . ") <b>" . $e->getMessage() . "</b>";
		}
	        // output
		if ($showlogo == "true") { $out[] = $serverlogo; } 
		$out[]  ='<div id="ts3_div">';
		$out[] = $tsserverlist;
                $out[]  ='</div>';
		if ($gtime == "true") { $out[] = $gentime; }
        	return join($out, "\n");

	}
      return ts3view::view(false);
    }


}


add_shortcode('ts3view', array('ts3view', 'shortcode'));
?>
