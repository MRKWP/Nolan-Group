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
                <h1>Import Nolan Group CSV Import Tool.</h1>
                <p>Load files to uploads/nolan-group-import/</p>
                <p>CSV Files for products.csv, product-images.csv and product-swatches.csv can be found here.</p>
                <p>Sub Folders for the Thumbnails, Images and Swatches are all inside here too.</p>
            </div>
        <?php
        
        $upload_dir   = wp_upload_dir();

        //load the CSV document from a file path
        $csv = Reader::createFromPath($upload_dir['basedir'].DIRECTORY_SEPARATOR.'nolan-group-import'.DIRECTORY_SEPARATOR.'products-sample.csv', 'r');
        $csv->setHeaderOffset(0);

        $header = $csv->getHeader(); //returns the CSV header record

        print_r($header);
        
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object
        
        foreach ($records as $record) {
            if(!empty($record['Product ID'])){
                $data['record'] = $record;
                print_r($record);
                //do_action('run_single_product_hook', $data);
                die();
            }
        }

        unset($csv);
    }
}