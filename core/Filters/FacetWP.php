<?php
/**
 * @package Nolan_Group;
 * Register Filters for Paginations
 */
namespace Nolan_Group\Filters;

/**
 * Handle facetwp related filters
 */
class FacetWP
{
    /**
     * Register filters and actions
     */
    public function register() {
        add_filter( 'facetwp_template_force_load', '__return_true');
        add_filter( 'pre_get_posts', [$this, 'apply_facet_filter']);
        add_filter( 'paginate_links_output', [$this, 'paginated_links_output'], 10, 2);
        add_filter( 'facetwp_filtered_query_args', [$this, 'modify_posts_per_page'], 10, 2);
    }
    
    /**
     * Hook into pre get posts to apply custom facet filters for brands
     *
     * @param $query
     */
    public function apply_facet_filter($query) {
        if ( ! empty($_GET['_brands_product_categories'])) {
            $brands_product_categories = explode(',', $_GET['_brands_product_categories']);
    
            $taxonomy_query = array(
                array(
                    'taxonomy' => 'product-category',
                    'field' => 'slug',
                    'terms' => $brands_product_categories,
                ),
            );
            $query->set( 'tax_query', $taxonomy_query );
        }
    
        if ( $query->get('post_type') === 'brochures' && ! empty($_GET['_related_brands_brochures'])) {
            $brand = $_GET['_related_brands_brochures'];
        
            $relationship_query = [
                'id'   => '103',
                'to' => $brand->ID,
            ];
            
            $query->set( 'relationship', $relationship_query );
        }
    
        if ( ! empty($_GET['_guide_categories'])) {
            $guide_categories = explode(',', $_GET['_guide_categories']);
        
            $taxonomy_query = array(
                array(
                    'taxonomy' => 'product-category',
                    'field' => 'slug',
                    'terms' => $guide_categories,
                ),
            );
            $query->set( 'tax_query', $taxonomy_query );
            var_dump($query->get('paged'));
        }
    }
    
    /**
     * Modify facetwp paginated links on brochures archive page
     *
     * @param $r
     * @param $args
     * @return mixed|string
     */
    public function paginated_links_output($r, $args) {
      if(is_post_type_archive('brochures')) {
        $pager_shortcode = do_shortcode('[facetwp facet="nolan_facetwp_pager"]');
        
        $new_paginated_links = '<div class="nolan-facetwp-pager">';
        $new_paginated_links .= $pager_shortcode;
        $new_paginated_links .= '</div>';
        
        return $new_paginated_links.$r;
      }
      
      return $r;
    }
    
    /**
     * Modify default facetwp posts per page used in pagination
     *
     * @param $query_args
     * @param $_this
     * @return mixed
     */
    public function modify_posts_per_page( $query_args, $_this ) {
        $query_args['posts_per_page'] = (int) get_option('posts_per_page');
        
        return $query_args;
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
