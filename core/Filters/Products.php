<?php
/**
 * @package Nolan_Group;
 * Register Filters for Paginations
 */
namespace Nolan_Group\Filters;

/**
 * Handle the paginations and facet filtering related .
 */
class Products
{
    /**
     * Register filters and actions
     */
    public function register() {
        //set link redirection
		add_filter( 'post_type_link', [ $this, 'modify_nolan_product_permalink' ], 8, 2 );
//		add filter that hides the sidebar on product archive page
        add_filter('the_post', [$this,'hide_sidebar_on_product_archive']);
        
        //add filter to sort products in alphabetical order
        add_action('pre_get_posts', [$this, 'sort_products_alphabetically']);
    }


    /**
     * If a Product has a link type change the permalink to that post ID object or redirect to URL.
     * If Post ID and link_page_id are the same, return the default permalink
     *
     * @param $url
     * @param $post
     */
    public function modify_nolan_product_permalink(  $url, $post ) {
        //Check post type is nolan-product
        if ( 'nolan-product' === $post->post_type ) {
            
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
    
    /**
     * Hide sidebar property on product archive page
     *
     * @param [type] $post_object
     * @return void
     */
    public function hide_sidebar_on_product_archive( $post_object ) {
        // check if the current page is a nolan product archive page or the product category is outdoor blind system
        if( is_post_type_archive('nolan-product') || is_tax('product-category', 'outdoor-blind-system') ) {
            add_filter('blocksy:general:sidebar-position', function ($current_value) {
                return 'none';
            });
        }
    }
    
    /**
     * sort products in alphabetical order
     *
     * @param $query
     * @return mixed
     */
    public function sort_products_alphabetically($query) {
        // check if the current page is not an admin and the post_type is nolan-product
        if ( ! is_admin() &&  $query->get('post_type') === 'nolan-product' ) {
            $query->set( 'orderby', 'title' );
            $query->set( 'order', 'ASC' );
        }
        
        return $query;
    }
}