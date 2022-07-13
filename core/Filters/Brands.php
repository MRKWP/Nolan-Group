<?php
/**
 * @package Nolan_Group;
 * Register Filters for Paginations
 */
namespace Nolan_Group\Filters;

/**
 * Handle the paginations and facet filtering related .
 */
class Brands
{
    /**
     * Register filters and actions
     */
    public function register() {
        add_filter( 'facetwp_facet_sources', [ $this, 'add_data_sources' ], 10, 1);
    }
    
    /**
     * Add Brands to the facet data sources
     *
     * @param $sources
     */
    public function add_data_sources( $sources ) {
        $brands['brand'] = [
            'label' => 'Brands',
            'choices' => [
                'post_type' => 'Brand Post Type',
                'post_title' => 'Post Title',
            ]
        ];
    
        return array_merge($sources, $brands);
    }
}