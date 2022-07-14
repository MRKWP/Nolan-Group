<?php
/**
 * Register Shortcode for Product Carousel Feature
 *
 * @package  Nolan_Group
 */
namespace Nolan_Group\Shortcodes;

use Nolan_Group\Base\BaseController;

class ProductCarousel extends BaseController {
    
    /**
     * Register `product-carousel` shortcode.
     *
     * @return void
     */
    public function register() {
        add_shortcode( 'product-carousel', [ $this, 'get_product_carousel'] );
    }
    
    
    /**
     * Get product carousel is a render callback for the dynamic shortcode - product-carousel;.
     * Returns a formatted list of html
     *
     * @param $attr
     * @param $content
     * @return string
     */
    public function get_product_carousel($attr, $content) {
        global $post;
    
        $attr = wp_parse_args($attr, [
            'number'  => 5,
            'brand'  => '',
            'category'  => 0,
        ]);
        
        $numberOfItems = $attr['number'];
        $brandSlug = $attr['brand'];
        $categoryID = $attr['category'];
        
        if(empty($brandSlug)) {
            if(!empty($post->ID) && $post->post_type == 'brand') {
                $brandSlug = $post->post_name;
            }
        }
    
        $brand = '';
        if(!empty($brandSlug)) {
            $brand = $this->get_post_by_slug($brandSlug);
        }
        
        $args = [
            'post_type'             => ['nolan-product'],
            'posts_per_page'        => $numberOfItems,
        ];
        
        if(!empty($brand)) {
            $product_terms = get_the_terms($brand, 'product-category');
            if(!empty($product_terms)) {
                $product_terms = wp_list_pluck($product_terms, 'term_id');
                $args['tax_query'][] = [
                    'taxonomy'  => 'product-category',
                    'field'     => 'term_id',
                    'terms'     => $product_terms,
                    'operator'  => 'IN'
                ];
            }
        } else if (!empty($categoryID)) {
            $product_terms = get_term_by('slug', $categoryID, 'product-category' );
            if(!empty($product_terms)) {
                $args['tax_query'][] = [
                    'taxonomy'  => 'product-category',
                    'field'     => 'term_id',
                    'terms'     => $product_terms,
                ];
            }
        }
        
        $results = new \WP_Query( $args );
    
        wp_enqueue_style( 'nolan-group-library-product-carousel', $this->plugin_url . '/assets/build/css/product-carousel.css' );
        wp_enqueue_script( 'nolan-group-library-swiper-bundle', $this->plugin_url.'/assets/src/js/swiper-bundle.min.js', [], NOLAN_GROUP_LIBRARY_VERSION);
        wp_enqueue_style( 'nolan-group-library-swiper-bundle', $this->plugin_url.'/assets/src/css/swiper-bundle.min.css', [], NOLAN_GROUP_LIBRARY_VERSION);
        wp_enqueue_script( 'nolan-group-library-product-carousel', $this->plugin_url.'/assets/src/js/carousel.js', ['nolan-group-library-swiper-bundle'], NOLAN_GROUP_LIBRARY_VERSION);
        
        if(!$results->have_posts())  return 'No Products found for this selection.';
        
        $results = $results->get_posts();
        $post_list_formatted = '<div class="nolan-group-carousel-contents swiper">';
        $post_list_formatted .= '<div class="nolan-group-library-controls">';
        $post_list_formatted .= '<button class="swiper-button-prev"></button>';
        $post_list_formatted .= '<button class="swiper-button-next"></button>';
        $post_list_formatted .= "</div>";
        $post_list_formatted .= '<div class="swiper-wrapper">';
        
        foreach ($results as $result) {
            $postID = $result->ID;
            $featured_image = get_the_post_thumbnail($postID, 'post-thumbnail', ['class'=> 'swiper-lazy']);
            $permalink = get_permalink($postID);
            $company_name = get_post_meta($postID, 'company_name', true);
            $post_title = get_post_meta($postID, 'name', true);
            if(empty($post_title)) $post_title = $result->post_title;
            $post_list_formatted .= '<div class="nolan-group-carousel-content swiper-slide">';
            $post_list_formatted .= sprintf('<a href="%s" title="%s">', $permalink, $post_title);
            $post_list_formatted .= sprintf('<div class="nolan-group-featured-image"><div class="nolan-group-image">%s</div></div>', $featured_image);
            $company_name_formatted = '';
            if(!empty($company_name)) {
                $company_name_formatted .= sprintf('<p>%s</p>', $company_name);
            }
            
            $post_list_formatted .= sprintf('<div class="nolan-group-title"><h2>%s</h2>%s</div>', $post_title, $company_name_formatted);
            
            $post_list_formatted .= '</a>';
            $post_list_formatted .= '</div>';
        }
        
        $post_list_formatted .= '</div>';
        $post_list_formatted .= '<div class="nolan-group-pagination swiper-pagination"></div>';
        $post_list_formatted .= '</div>';
        
        return $post_list_formatted;
    }
    
    /**
     * Get posts by the slug
     *
     * @param $slug
     * @param string $post_type
     * @return false|int|\WP_Post
     */
    public function get_post_by_slug( $slug, $post_type = 'brand' ){
        $args = [
            'name' => $slug,
            'post_type' => $post_type,
            'posts_per_page' => 1
        ];
        
        $posts = get_posts( $args );
        
        if(!empty( $posts[0] ) ) {
           return $posts[0];
        }
        return false;
    }
}