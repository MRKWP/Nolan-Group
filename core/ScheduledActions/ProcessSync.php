<?php 
/**
 * @package  Simpleview_Listings
 */
namespace Nolan_Group\ScheduledActions;

use League\Csv\Reader;
use Nolan_Group\Makers\Master;
use Nolan_Group\Makers\ImageGallery;
use \DateTime;

class ProcessSync{

    public $productHook                   = 'run_single_product_hook';
    public $addProductHook                = 'add_single_product_hook';

    public $productGalleryHook            = 'run_single_product_gallery_hook';
    public $addProductGalleryHook         = 'add_single_product_gallery_hook';

    public $csvHook                       = 'get_all_product_hook';
    public $allProductProcessHook         = 'get_all_product_process_hook';
    public $actionGroup                   = 'nolangroup_sync';
    public $allActionGroup                = 'all_nolangroup_sync';

    public function __construct() {

        add_action( $this->productHook, [$this, 'runSingleProductHook'] );

        add_action( $this->addProductHook, [$this, 'addSingleProductHook'], 0 , 1 );

        add_action( $this->productGalleryHook, [$this, 'runSingleProductGalleryHook'] );

        add_action( $this->addProductGalleryHook, [$this, 'addSingleProductGalleryHook'], 0 , 1 );

        add_action( $this->allProductProcessHook, [$this, 'runAllProductHook'] );

        add_action( $this->csvHook, [$this, 'runCSVProcessHook'] );

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
                60 * 60 * 1, // every 24 hours
                $this->csvHook,
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

}