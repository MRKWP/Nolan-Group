<?php
/**
 * @package Nolan_Group;
 */
namespace Nolan_Group\Filters;

/**
 * This class is used to turn off the side bars in the top level category pages.
 */
class CategorySideBar {
    
    //Array of Term IDs for the side bar to be off
    public $categories = array('floor-coverings','commercial-upholstery','blinds-awnings','marine','industrial','shade','automotive','acoustics','agriculture','hospitality');

    /**
     * Register Filters and Actions
     *
     * @return void
     */
    public function register() {
        add_filter('pre_get_posts', [$this,'turn_off_side']);
    }

    /**
     * Initilise the filter to turn off the side bar
     *
     * @param [type] $term
     * @return void
     */
    public function turn_off_side( $query ) {

        if($query->is_tax( 'product-category' )){

            do_action( 'qm/debug', $query->query['product-category']);
            
            if (in_array($query->query['product-category'], $this->categories)){
                add_filter('blocksy:general:sidebar-position', function ($current_value) {
                    return 'none';
                });
            }
        }
    }
}