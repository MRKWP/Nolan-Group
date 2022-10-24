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
    public $categories = array('floor-coverings','commercial-upholstery','blinds-awnings','marine','industrial','shade-structures','automotive','acoustics','agriculture','hospitality');

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

        if($query->is_tax( 'product-category' )) {
    
            $this_term = get_queried_object();
            
            if(isset($this_term->term_id)) {
                $get_active_filters  = rwmb_meta( 'turn_on_off_filter_types',['object_type' => 'term'], $this_term->term_id );
    
                if (in_array($query->query['product-category'], $this->categories) || empty( $get_active_filters )){
                    add_filter('blocksy:general:sidebar-position', function ($current_value) {
                        return 'none';
                    });
                }
            }
        }
    }
}