<?php 
/**
 * @package Nolan_Group
 */
namespace Nolan_Group\Base;

// use get_field;

class BaseController
{
	public $plugin_path;

	public $plugin_url;

	public $plugin;

	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url  = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin      = plugin_basename( dirname( __FILE__, 3 ) ) . '/nolan-group.php';
	}
}