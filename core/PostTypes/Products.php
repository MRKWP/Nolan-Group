<?php
/**
 * Register New Post Type with Fields via ACF Plugin.
 */
namespace Nolan_Group\PostTypes;

/**
 * Register New Post Type.
 * Add Fields via ACF Plugin.
 */
class Products {
    
    /**
     * Register New Post Type.
     *
     * @return void
     */
    public function register() {
        add_action( 'init', [$this, 'cpt_init'], 9);
        add_action( 'init', [$this, 'acf_fields'], 10);
    }

    /**
     * Post Type Arguments and Registration
     *
     * @return void
     */
    // Register Custom Post Type
    function cpt_init() {

        $labels = array(
            'name'                  => _x( 'Products', 'Post Type General Name', 'nolan-group' ),
            'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'nolan-group' ),
            'menu_name'             => __( 'Products', 'nolan-group' ),
            'name_admin_bar'        => __( 'Product', 'nolan-group' ),
            'archives'              => __( 'Product Archives', 'nolan-group' ),
            'attributes'            => __( 'Product Attributes', 'nolan-group' ),
            'parent_item_colon'     => __( 'Parent Product:', 'nolan-group' ),
            'all_items'             => __( 'All Products', 'nolan-group' ),
            'add_new_item'          => __( 'Add Product', 'nolan-group' ),
            'add_new'               => __( 'Add New Product', 'nolan-group' ),
            'new_item'              => __( 'New Product', 'nolan-group' ),
            'edit_item'             => __( 'Edit Product', 'nolan-group' ),
            'update_item'           => __( 'Update Product', 'nolan-group' ),
            'view_item'             => __( 'View Product', 'nolan-group' ),
            'view_items'            => __( 'View Products', 'nolan-group' ),
            'search_items'          => __( 'Search Product', 'nolan-group' ),
            'not_found'             => __( 'Not found', 'nolan-group' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'nolan-group' ),
            'featured_image'        => __( 'Featured Image', 'nolan-group' ),
            'set_featured_image'    => __( 'Set featured image', 'nolan-group' ),
            'remove_featured_image' => __( 'Remove featured image', 'nolan-group' ),
            'use_featured_image'    => __( 'Use as featured image', 'nolan-group' ),
            'insert_into_item'      => __( 'Insert into Product', 'nolan-group' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Product', 'nolan-group' ),
            'items_list'            => __( 'Products list', 'nolan-group' ),
            'items_list_navigation' => __( 'Products list navigation', 'nolan-group' ),
            'filter_items_list'     => __( 'Filter Products list', 'nolan-group' ),
        );
        $rewrite = array(
            'slug'                  => 'product',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $args = array(
            'label'                 => __( 'Product', 'nolan-group' ),
            'description'           => __( 'Product Items for Nolan Group', 'nolan-group' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'excerpt' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-products',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'wp-products',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type( 'product', $args );

    }

    /**
     * Add ACF Fields for the Post Type
     *
     * @return void
     */
    public function acf_fields() {

    }
}