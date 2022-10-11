<?php
/**
 * @package Nolan_Group;
 * Register Filters for Paginations
 */
namespace Nolan_Group\Filters;

/**
 * Handle the paginations and facet filtering related .
 */
class Swatches
{
    /**
     * Register filters and actions
     */
    public function register()
    {
        //sort filter swatches alphabetically
        add_filter('rwmb_meta', [$this, 'sort_swatches_alphabetically'], 10, 4);
        
    }
    
    /**
     * Sort the colour swatches 'swatch' alphabetically
     *
     * @param $meta
     * @param $key
     * @param $args
     * @param $post_id
     * @return mixed
     */
    public function sort_swatches_alphabetically( $meta, $key, $args, $post_id ) {
        if($key == 'swatch' && !is_admin()) {
            usort($meta, function($a, $b) {
                return strcmp($a['swatch_name'], $b['swatch_name']);
            });
        }
        
        return $meta;
    }
    
    
}
