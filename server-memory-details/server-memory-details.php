<?php
/**
 * Plugin Name: Server Memory Details
 * Plugin URI: https://raw.githubusercontent.com/sarbajitsaha/Server-Memory-Details-WordPressPlugin/master/1.0.zip
 * Description: This plugin adds details about the server memory in the footer of the admin dashboard
 * Version: 1.0.0
 * Author: Sarbajit Saha
 * Author URI: https://github.com/sarbajitsaha
 * License: GPL2
 */

if(is_admin())
{
	function convert($size)
	{
	    $unit=array('B','KB','MB','GB','TB','PB');
	    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}

	function get_format_memory_details()
	{
		$peak_memory = memory_get_peak_usage(false);
		$current_memory = memory_get_usage(false);
		$memory_limit = ini_get('memory_limit');
		$memory_limit = explode("M", $memory_limit);
		$memory_limit = $memory_limit[0]*1024*1024;
		$percent = (int)(($current_memory*100)/$memory_limit);

		$memory = array();
		$memory["percentage"] = $percent;
		$memory["peak"] = convert($peak_memory);
		$memory["limit"] = convert($memory_limit);
		$memory["current"] = convert($current_memory);
		return $memory;
	}

	function print_details()
	{
		$memory = get_format_memory_details();
		$str = "Memory : ".$memory["current"]."/ ".$memory["limit"]." (".$memory["percentage"]."%)"." | Peak Memory : ".$memory["peak"]." | WP Limit : ".WP_MEMORY_LIMIT."B";
		return $str;
	}

	function start_plugin()
	{
		add_filter('admin_footer_text', "print_details");
	}

	add_action( 'plugins_loaded', "start_plugin");
}

?>
