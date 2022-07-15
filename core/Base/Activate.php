<?php
/**
 * @package  Nolan_Group
 */
namespace Nolan_Group\Base;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}