<?php
/**
 * Sync Start page in Admin UI
 * 
 * Do the CSV Import for Nolan Group
 * 
 * @package  Nolan_Group
 */
namespace Nolan_Group\Pages;

use League\Csv\Reader;
use Nolan_Group\Makers\Master;

class BackgroundSync {

    public function register() {
		add_action( 'admin_menu' , [ $this, 'setup_menu' ]);
	}

    public function setup_menu() {

        add_submenu_page(
            'nolan-group-import',
            'Background Sync',
            'Background Sync',
            'manage_options',
            'nolan-group-import-background-sync',
            [ $this, 'start_sync_callback'],
        );
 
    }

    public function start_sync_callback() {
        ?>
            <div class="wrap">
                <h1>Background Sync for Nolan CSV Import</h1>
            </div>
        <?php
        
        echo "Sync started - <a href='/wp-admin/tools.php?page=action-scheduler&status=pending'>check Action Scheduler</a> to view all import actions on the server.";

        do_action('get_all_product_process_hook');
        
    }
}