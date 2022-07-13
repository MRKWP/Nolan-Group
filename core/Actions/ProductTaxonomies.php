<?php
/**
 * @package Nolan_Group;
 * Register Actions for Post Types
 */
namespace Nolan_Group\Actions;

/**
 * Handle the paginations and facet filtering related .
 */
class ProductTaxonomies
{
    /**
     * register actions
     */
    public function register() {
        add_action( 'pre_get_posts', [ $this, 'customize_taxonomy_archive_display' ] );
    }
    
    /**
     * Sort post types alphabetically
     *
     * @param $query
     */
    public function customize_taxonomy_archive_display( $query ) {
        if (($query->is_main_query()) && (is_tax('product-category'))){
            $query->set( 'post_type', 'nolan-product' );
            $query->set( 'posts_per_page', '20' );
        }
    }
}