<?php
/**
 * @package Nolan_Group;
 * Register Actions for Post Types
 */
namespace Nolan_Group\Actions;

/**
 * Handle the paginations and facet filtering related
 *
 */
class PostTypes
{
    /**
     * register actions
     */
    public function register() {
        add_action( 'pre_get_posts', [ $this, 'sort_post_types_alphabetically' ], 10, 1 );
    }
    
    /**
     * Sort post types alphabetically
     *
     * @param $query
     */
    public function sort_post_types_alphabetically( $query ) {
        $sorted_post_types = apply_filters('nolan_group_sort_query_alphabetically', ['brand', 'nolan-product']);
    
        if( $query->is_main_query() && !empty($query->query['post_type']) && in_array($query->query['post_type'], $sorted_post_types) ) {
            $query->set( 'order', 'ASC' );
            $query->set( 'orderby', 'title' );
        }
    }
}