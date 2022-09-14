<?php
/**
 * @package  Simpleview_Listings
 */
namespace Nolan_Group\ScheduledActions;

use League\Csv\Reader;
use Nolan_Group\Makers\Master;
use Nolan_Group\Makers\ImageGallery;
use Nolan_Group\Makers\Swatch;
use \DateTime;
use WP_Query;

class ProcessSync{

    public $productHook                   = 'run_single_product_hook';
    public $addProductHook                = 'add_single_product_hook';

    public $productGalleryHook            = 'run_single_product_gallery_hook';
    public $addProductGalleryHook         = 'add_single_product_gallery_hook';

    public $productSwatchHook            = 'run_single_product_swatch_hook';
    public $addProductSwatchHook         = 'add_single_product_swatch_hook';

    public $csvHook                       = 'get_all_product_hook';
    public $csvImagesHook                 = 'get_all_product_images_hook';
    public $csvSwatchHook                 = 'get_all_product_swatch_hook';

    public $allProductProcessHook         = 'get_all_product_process_hook';
    public $allProductImagesProcessHook   = 'get_all_product_images_process_hook';
    public $allProductSwatchProcessHook   = 'get_all_product_swatch_process_hook';
    
    public $actionGroup                   = 'nolangroup_sync';
    public $allActionGroup                = 'all_nolangroup_sync';

    public function __construct() {
        
        add_action( $this->productHook, [$this, 'runSingleProductHook'] );

        add_action( $this->addProductHook, [$this, 'addSingleProductHook'], 0 , 1 );

        add_action( $this->productGalleryHook, [$this, 'runSingleProductGalleryHook'] );

        add_action( $this->addProductGalleryHook, [$this, 'addSingleProductGalleryHook'], 0 , 1 );

        add_action( $this->productSwatchHook, [$this, 'runSingleProductSwatchHook'] );

        add_action( $this->addProductSwatchHook, [$this, 'addSingleProductSwatchHook'], 0 , 1 );

        add_action( $this->allProductProcessHook, [$this, 'runAllProductHook'] );

        add_action( $this->allProductImagesProcessHook, [$this, 'runAllProductImagesHook'] );

        add_action( $this->allProductSwatchProcessHook, [$this, 'runAllProductSwatchHook'] );

        add_action( $this->csvHook, [$this, 'runCSVProcessHook'] );

        add_action( $this->csvImagesHook, [$this, 'runImageCSVProcessHook'] );

        add_action( $this->csvSwatchHook, [$this, 'runSwatchCSVProcessHook'] );

    }

    /**
     * Hooked action to run the CSV Process hook.
     *
     * This process imports all items from all CSV Files for Products, Product Galleries and Product Swatches
     *
     * Triggers a DO ACTION on the run single product hook for each item in the CSV
     *
     * @param [type] $data
     * @return void
     */
    public function runCSVProcessHook($data){
        
        //get the upload directory
        $upload_dir   = wp_upload_dir();

        //load the CSV document from a file path
        $csv = Reader::createFromPath($upload_dir['basedir'].DIRECTORY_SEPARATOR.'nolan-group-import'.DIRECTORY_SEPARATOR.'products.csv', 'r');
        $csv->setHeaderOffset(0);

        $header = $csv->getHeader(); //returns the CSV header record
        
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object
        
        foreach ($records as $record) {
            if(!empty($record['Product ID'])){
                $data['record'] = $record;
                do_action('run_single_product_hook', $data);
            }
        }

        unset($csv);


    }

    /**
     * Hooked action to run the CSV Process hook.
     *
     * This process imports all items from all CSV Files for Products, Product Galleries and Product Swatches
     *
     * Triggers a DO ACTION on the run single product hook for each item in the CSV
     *
     * @param [type] $data
     * @return void
     */
    public function runImageCSVProcessHook($data){

        //get the upload directory
        $upload_dir   = wp_upload_dir();

        //load the CSV document from a file path
        $csv = Reader::createFromPath($upload_dir['basedir'].DIRECTORY_SEPARATOR.'nolan-group-import'.DIRECTORY_SEPARATOR.'product-images.csv', 'r');
        $csv->setHeaderOffset(0);
        
        $header = $csv->getHeader(); //returns the CSV header record
        
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object
        
        foreach ($records as $record) {
            if(!empty($record['Reference ID'])){
                $data['record'] = $record;
                do_action('run_single_product_gallery_hook', $data);
            }
        }
        
        unset($csv);

    }

    /**
     * Hooked action to run the CSV Process hook.
     *
     * This process imports all items from all CSV Files for Products, Product Galleries and Product Swatches
     *
     * Triggers a DO ACTION on the run single product hook for each item in the CSV
     *
     * @param [type] $data
     * @return void
     */
    public function runSwatchCSVProcessHook($data){

        //get the upload directory
        $upload_dir   = wp_upload_dir();

        //load the CSV document from a file path
        $csv = Reader::createFromPath($upload_dir['basedir'].DIRECTORY_SEPARATOR.'nolan-group-import'.DIRECTORY_SEPARATOR.'product-swatches.csv', 'r');
        $csv->setHeaderOffset(0);
        
        $header = $csv->getHeader(); //returns the CSV header record
        
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object
        
        // delete all swatch
        $this->deleteAllSwatches($records);
        
        foreach ($records as $record) {
            if(!empty($record['Product ID'])){
                $data['record'] = $record;

                //clear the swatches for the updated product ID
                if($this->clearswatch($data['record']['Product ID'])){
                    do_action('run_single_product_swatch_hook', $data);
                };
            }
        }
        
        unset($csv);

    }

    /**
     * Hooked action to run CSV Imports. This is recurring and daily task
     *
     * @return void
     */
    public function runAllProductHook() {

        $que['snippet'] = 1;
        
        $now = new DateTime();

        if(!as_has_scheduled_action( $this->csvHook)){

            \as_schedule_recurring_action(
                $now->getTimestamp(),
                60 * 60 * 48, // every 48 hours
                $this->csvHook,
                [$que],
                $this->allActionGroup
              );
        }
    }

    /**
     * Hooked action to run CSV Imports. This is recurring and daily task
     *
     * @return void
     */
    public function runAllProductImagesHook() {

        $que['snippet'] = 1;
        
        $now = new DateTime();

        if(!as_has_scheduled_action( $this->csvImagesHook)){

            \as_schedule_recurring_action(
                $now->getTimestamp(),
                60 * 60 * 48, // every 48 hours
                $this->csvImagesHook,
                [$que],
                $this->allActionGroup
              );
        }
    }

    /**
     * Hooked action to run CSV Imports. This is recurring and daily task
     *
     * @return void
     */
    public function runAllProductSwatchHook() {

        $que['snippet'] = 1;
        
        $now = new DateTime();

        if(!as_has_scheduled_action( $this->csvSwatchHook)){

            \as_schedule_recurring_action(
                $now->getTimestamp(),
                60 * 60 * 48, // every 48 hours
                $this->csvSwatchHook,
                [$que],
                $this->allActionGroup
              );
        }
    }

    /**
     * Used to trigger a single sync of a Product Item from CSV into the action schedule
     *
     * @param [type] $sv_data
     * @return void
     */
    public function runSingleProductHook($data){
        $now = new DateTime();

        \as_enqueue_async_action(
          $this->addProductHook,
          [$data],
          $this->actionGroup
        );
    }

    /**
     * This is the actual trigger for a single product import
     *
     * @param [type] $sv_data
     * @return void
     */
    public function addSingleProductHook($data) {

        $master = new Master($data['record']);
        if($master->initData()){
            $master->updateData();
        }
        unset($master);
    }

    /**
     * Used to trigger a single sync of a Product Gallery Item from CSV into the action schedule
     *
     * @param [type] $sv_data
     * @return void
     */
    public function runSingleProductGalleryHook($data){
        $now = new DateTime();

        \as_enqueue_async_action(
          $this->addProductGalleryHook,
          [$data],
          $this->actionGroup
        );
    }

    /**
     * This is the actual trigger for a single product gallery import
     *
     * @param [type] $sv_data
     * @return void
     */
    public function addSingleProductGalleryHook($data) {

        $imagegallery = new ImageGallery($data['record']);
        if($imagegallery->initData()){
            $imagegallery->updateData();
        }
        unset($imagegallery);
    }

    /**
     * Used to trigger a single sync of a Product Swatch Item from CSV into the action schedule
     *
     * @param [type] $sv_data
     * @return void
     */
    public function runSingleProductSwatchHook($data){
        $now = new DateTime();

        \as_enqueue_async_action(
          $this->addProductSwatchHook,
          [$data],
          $this->actionGroup
        );
    }

    /**
     * This is the actual trigger for a single product swatch import
     *
     * @param [type] $sv_data
     * @return void
     */
    public function addSingleProductSwatchHook($data) {

        $productswatch = new Swatch($data['record']);

        //Create the new swatch
        if($productswatch->initData()){
            $productswatch->updateData();
        }
        unset($productswatch);
    }


    /**
     * clear all swatch data from a post ID based on External ID.
     * If ID does not exist return false so swatch is not updated or added.
     *
     * @param [type] $id
     * @return boolean
     */
    public function clearswatch($id){

        $post_id;

        $args = array(
            'numberposts'	=> 1,
            'post_type'		=> 'nolan-product',
            'meta_key'		=> 'nolan_external_id',
            'meta_value'    => $id,
            'compare'       => '='
            );

        // query the item
        $the_query = new WP_Query( $args );

        //We found posts and need to do an update
        if( $the_query->have_posts() ):
            while ( $the_query->have_posts() ) :
                $the_query->the_post();
                $post_id = $the_query->post->ID;
            endwhile;

            $post_id;

            return true;
        endif;

        return false;
    }
    
    
    /**
     * Delete all previously added product swatches before running the import, fixes duplication of product swatches
     *
     * @param $records
     */
    public function deleteAllSwatches($records) {
        foreach ($records as $record) {
            if(!empty($record['Product ID'])){
                $data['record'] = $record;
        
                $external_product_id = $data['record']['Product ID'];
    
                $args = array(
                    'numberposts'	=> 1,
                    'post_type'		=> 'nolan-product',
                    'meta_key'		=> 'nolan_external_id',
                    'meta_value'    => $external_product_id,
                    'compare'       => '='
                );
    
                // query the item
                $the_query = new WP_Query( $args );
    
                //We found posts and need to do an update
                if( $the_query->have_posts() ):
                    while ( $the_query->have_posts() ) :
                        $the_query->the_post();
                        $post_id = $the_query->post->ID;
                        delete_post_meta($post_id, 'swatch');
                    endwhile;
                endif;
            }
        }
    }
}