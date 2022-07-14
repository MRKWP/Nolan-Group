<?php
/**
 * @package  Nolan_Group
 */
namespace Nolan_Group\Base;

class Deactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}