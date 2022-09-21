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
        add_action('blocksy:hero:after', [$this, 'add_mobile_filter_button']);
    }
    
    
    public function add_mobile_filter_button() {
        if(is_tax('product-category')) {
          
          ob_start();
            ?>
            <div class="ct-container">
                <div class="nolan-group-mobile_filter">
                    <button onclick=""><?php echo __('Open Filter', 'nolan-group') ?></button>
                </div>
            </div>
            <?php
            
            echo ob_get_clean();
        }
    }

    /**
     * Initilise the filter to turn off the side bar
     *
     * @param [type] $term
     * @return void
     */
    public function turn_off_side( $query ) {

        if($query->is_tax( 'product-category' )){
            
            if (in_array($query->query['product-category'], $this->categories)){
                add_filter('blocksy:general:sidebar-position', function ($current_value) {
                    return 'none';
                });
            }
        }
    }
}