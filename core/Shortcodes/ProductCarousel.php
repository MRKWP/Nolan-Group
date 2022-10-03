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
            'number'  => 12,
            'brand'  => '',
            'category'  => 0,
        ]);
        
        $numberOfItems = $attr['number'];
        $brandSlug = $attr['brand'];
        
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
            $args = [
                'posts_per_page'        => $numberOfItems,
                'relationship' => [
                        'id'   => '103',
                        'to' => $brand->ID,
                    ],
            ];
        }
        
        $results = new \WP_Query( $args );

        wp_enqueue_style( 'nolan-group-library-product-carousel', $this->plugin_url . '/assets/build/css/product-carousel.css' );
        wp_enqueue_script( 'nolan-group-library-swiper-bundle', $this->plugin_url.'/assets/src/js/swiper-bundle.min.js', [], NOLAN_GROUP_LIBRARY_VERSION);
        wp_enqueue_style( 'nolan-group-library-swiper-bundle', $this->plugin_url.'/assets/src/css/swiper-bundle.min.css', [], NOLAN_GROUP_LIBRARY_VERSION);
        wp_enqueue_script( 'nolan-group-library-product-carousel', $this->plugin_url.'/assets/src/js/carousel.js', ['nolan-group-library-swiper-bundle'], NOLAN_GROUP_LIBRARY_VERSION);
        
        $total_posts = $results->found_posts;
        
        if(!$results->have_posts())  return sprintf('<p class="aligncenter">%s</p>', __('No Products found for this selection', 'nolan-group') );
        
        $results = $results->posts;
        $post_list_formatted = '<div class="nolan-group-carousel-contents swiper">';
        $post_list_formatted .= '<div class="nolan-group-library-controls">';
        $post_list_formatted .= '<button class="swiper-button-prev"></button>';
        $post_list_formatted .= '<button class="swiper-button-next"></button>';
        $post_list_formatted .= "</div>";
        $post_list_formatted .= '<div class="nolan-group-product-entries swiper-wrapper">';
        
        foreach ($results as $result) {
            $postID = $result->ID;
            $featured_image = get_the_post_thumbnail($postID, 'post-thumbnail', ['class'=> 'swiper-lazy']);
            $permalink = get_permalink($postID);
            $company_name = get_post_meta($postID, 'company_name', true);
            $post_title = get_post_meta($postID, 'name', true);
            if(empty($post_title)) $post_title = $result->post_title;
            $post_list_formatted .= '<div class="nolan-group-carousel-content swiper-slide">';
            $post_list_formatted .= sprintf('<a href="%s" class="%s" title="%s">', $permalink, 'nolan-group-product-entry', $post_title);
            $post_list_formatted .= sprintf('<div class="nolan-group-product-image"><div class="ct-image-container nolan-group-image">%s</div></div>', $featured_image);
            $company_name_formatted = '';
            if(!empty($company_name)) {
                $company_name_formatted .= sprintf('<p>%s</p>', $company_name);
            }
            
            $post_list_formatted .= sprintf('<div class="nolan-group-product-title"><h2>%s</h2>%s</div>', $post_title, $company_name_formatted);
            
            $post_list_formatted .= '</a>';
            $post_list_formatted .= '</div>';
        }
        
        $post_list_formatted .= '</div>';
        $post_list_formatted .= '<div class="nolan-group-pagination swiper-pagination"></div>';
        $post_list_formatted .= '</div>';
        
        
        if($total_posts > 12) {
            $product_taxonomy = get_the_terms(get_the_ID(), 'product-category');
            if(!empty($product_taxonomy[0])) {
                $term_link = get_term_link($product_taxonomy[0]->term_id);
                $post_list_formatted .= '<div class="nolan-group-see-more-button wp-block-button">';
                $post_list_formatted .= sprintf('<a class="wp-block-button__link" href="%s" title="%s">%s</a>', $term_link, $product_taxonomy[0]->name, __('See more', 'nolan-group'));
                $post_list_formatted .= '</div>';
            }
        }
        
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