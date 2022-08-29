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
use Nolan_Group\Makers\ImageGallery;

class ManualSync {

    public function register() {
        add_action( 'admin_menu' , [ $this, 'setup_menu' ]);
    }

    public function setup_menu() {

        add_menu_page(
            'Nolan CSV Import',
            'Nolan CSV Import',
            'manage_options',
            'nolan-group-import',
            [ $this, 'start_sync_callback'],
        );

    }

    public function start_sync_callback() {
        ?>
            <div class="wrap">
                <h1>Import Nolan Group CSV Check.</h1>
            </div>
        <?php
        
        //get the upload directory
        $upload_dir   = wp_upload_dir();

        //load the CSV document from a file path
        $csv = Reader::createFromPath($upload_dir['basedir'].DIRECTORY_SEPARATOR.'nolan-group-import'.DIRECTORY_SEPARATOR.'products.csv', 'r');
        $csv->setHeaderOffset(0);

        $header = $csv->getHeader(); //returns the CSV header record
        
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object
        
        foreach ($records as $record) {

            $features = explode("|",$record['Features (Only 5)']);
            foreach ($features as $feature){
                print_r($feature);
                print('<br>');
            }
            print('<hr>');
        }
        
    }
}