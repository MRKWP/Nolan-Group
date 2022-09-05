<?php
/**
 * Register Shortcode for Featured Brands
 *
 * @package  Nolan_Group
 */
namespace Nolan_Group\Shortcodes;

use Nolan_Group\Base\BaseController;

class BrandsFeatured extends BaseController {
    
    /**
     * Register `product-carousel` shortcode.
     *
     * @return void
     */
    public function register() {
        add_shortcode( 'brands-featured', [ $this, 'get_featured_brands'] );
    }
    
    
    public function get_featured_brands($attr, $content) {
        $args = [
            'post_type'     => 'brand',
            'meta_key'      => 'featured_brand',
            'meta_value'    => 1
        ];
    
        $results = new \WP_Query( $args );
    
        wp_enqueue_style( 'nolan-group-library-product-carousel', $this->plugin_url . '/assets/build/css/product-carousel.css' );
        wp_enqueue_script( 'nolan-group-library-swiper-bundle', $this->plugin_url.'/assets/src/js/swiper-bundle.min.js', [], NOLAN_GROUP_LIBRARY_VERSION);
        wp_enqueue_style( 'nolan-group-library-swiper-bundle', $this->plugin_url.'/assets/src/css/swiper-bundle.min.css', [], NOLAN_GROUP_LIBRARY_VERSION);
        wp_enqueue_script( 'nolan-group-library-product-carousel', $this->plugin_url.'/assets/src/js/carousel.js', ['nolan-group-library-swiper-bundle'], NOLAN_GROUP_LIBRARY_VERSION);
    
        if(!$results->have_posts())  return 'No Featured Brands found for this selection.';
        
        $results = $results->posts;
        $post_list_formatted = '<div class="nolan-group-carousel-contents-container">';
        $post_list_formatted .= '<div class="nolan-group-library-controls nolan-group-library-outside-container-controls">';
        $post_list_formatted .= '<button class="swiper-button-prev"></button>';
        $post_list_formatted .= '<button class="swiper-button-next"></button>';
        $post_list_formatted .= "</div>";
        $post_list_formatted .= '<div class="nolan-group-carousel-contents swiper nolan-group-outside-arrows-container">';
        $post_list_formatted .= '<div class="nolan-group-product-entries swiper-wrapper">';
    
    
        foreach ($results as $result) {
            $postID = $result->ID;
            $featured_image = get_the_post_thumbnail($postID, 'post-thumbnail', ['class'=> 'swiper-lazy']);
            $permalink = get_permalink($postID);
            $post_title = $result->post_title;
            $post_list_formatted .= '<div class="nolan-group-carousel-content swiper-slide">';
            $post_list_formatted .= sprintf('<a href="%s" class="%s" title="%s">', $permalink, 'nolan-group-product-entry', $post_title);
            $post_list_formatted .= sprintf('<div class="nolan-group-product-image"><div class="ct-image-container nolan-group-image">%s</div></div>', $featured_image);
            $post_list_formatted .= sprintf('<div class="nolan-group-product-title"><h2>%s</h2></div>', $post_title);
        
            $post_list_formatted .= '</a>';
            $post_list_formatted .= '</div>';
        }
    
        $post_list_formatted .= '</div>';
        $post_list_formatted .= '<div class="nolan-group-pagination swiper-pagination"></div>';
        $post_list_formatted .= '</div>';
        $post_list_formatted .= '</div>';
    
        return $post_list_formatted;
    }
}