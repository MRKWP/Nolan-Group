<?php
/**
 * Register Category Taxonomy for products.
 */
namespace Nolan_Group\Taxonomies;

/**
 * Register Category Taxonomy for Products.
 */
class ProductCategory {
    
    /**
     * Register Product Categories Taxonomy in WordPress.
     *
     * @return void
     */
    public function register() {
        add_action( 'init', [ $this, 'create_taxonomy'], 10 );
    }
    
    /**
     * Register Taxonomy Arguments.
     *
     * @return void
     */
    public function create_taxonomy() {
        $args = array(
            'hierarchical'      => true,
            'public'            => false,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => false,
            'capabilities'      => array(
                'manage_terms' => 'edit_posts',
                'edit_terms'   => 'edit_posts',
                'delete_terms' => 'edit_posts',
            
            ),
            'labels'                => array(
                'name'                       => __( 'Product Categories'),
                'singular_name'              => __( 'Product Category'),
                'search_items'               => __( 'Search Product Categories'),
                'popular_items'              => __( 'Popular Product Categories' ),
                'all_items'                  => __( 'All Product Categories'),
                'parent_item'                => __( 'Parent Product Category'),
                'parent_item_colon'          => __( 'Parent Product Category:'),
                'edit_item'                  => __( 'Edit Product Category'),
                'update_item'                => __( 'Update Product Category'),
                'add_new_item'               => __( 'New Product Category'),
                'new_item_name'              => __( 'New Product Category'),
                'separate_items_with_commas' => __( 'Separate Product Categories with commas'),
                'add_or_remove_items'        => __( 'Add or remove Product Categories'),
                'choose_from_most_used'      => __( 'Choose from the most used Product Categories'),
                'not_found'                  => __( 'No Product Categories found.'),
                'menu_name'                  => __( 'Product Categories'),
            ),
            'show_in_rest'          => true,
            'rest_base'             => 'product-category',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        );
        
        register_taxonomy('product-category', array('product','brand', 'brochure', 'guide'), $args);
    }
}