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
        //add new source to facetwp
        add_filter( 'facetwp_facet_sources', [ $this, 'add_data_sources' ], 10, 1);
        //set link redirection
		add_filter( 'post_type_link', [ $this, 'modify_brand_permalink' ], 8, 2 );
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

    /**
     * If a Brand has a link type change the permalink to that post ID object or redirect to URL.
     * If Post ID and link_page_id are the same, return the default permalink
     *
     * @param $url
     * @param $post
     */
    public function modify_brand_permalink(  $url, $post ) {
	    
	//Check post type is brand
        if ( 'brand' === $post->post_type ) {
	    
	    //Get the link type field for checking if a redirect is needed
            $link_type = rwmb_meta( 'link_type', '', $post->ID );

	    //If empty of NONE do default behaviour
            if(! empty ($link_type) && $link_type !== 'none') {
		    
                if($link_type == 'page_id') {
			
                    $page_id_value = rwmb_meta( 'link_page_id', '', $post->ID );
		
                    //Check if the link is the same as the current post and return URL if it is.
                    if($page_id_value == $post->ID){
                        return $url;
		    }
	
		    //BUG: IF doesnt work and have found it creates a circular reference.
                    if( ! empty ( $page_id_value )  && $page_id_value !== $post->ID ) {
                        return get_permalink($page_id_value) ? get_permalink($page_id_value) :  $url;
                    }
		
                } elseif ($link_type == 'external_url') {
		    
		    //Get the external URL
                    $page_external_url = rwmb_meta( 'external_url', '', $post->ID );
		
		    //If this is Not Empty use as permalink
                    if(!empty($page_external_url)) {
                        return esc_url($page_external_url);
                    }else{
			return $url;
		    }
			
                } else {
                    return $url;
                }
            }
        }

        return $url;
    }
}
