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
    }


    /**
     * Modify Nolan Product Permalink
     *
     * @param $url
     * @param $post
     */
    public function modify_nolan_product_permalink(  $url, $post ) {
        if ( 'nolan-product' === $post->post_type ) {
            $link_type = rwmb_meta( 'link_type', '', $post->ID );

            if(! empty ($link_type) && $link_type !== 'none') {
                if($link_type == 'page_id') {
                    $page_id_value = rwmb_meta( 'link_page_id', '', $post->ID );
                    if( ! empty ( $page_id_value ) ) {
                        return get_permalink($page_id_value) ? get_permalink($page_id_value) :  $url;
                    }
                } elseif ($link_type == 'external_url') {
                    $page_external_url = rwmb_meta( 'external_url', '', $post->ID );
                        if(!empty($page_external_url)) {
                            return esc_url($page_external_url);
                        }
                } else {
                    return $url;
                }
            }
        }

        return $url;
    }
}