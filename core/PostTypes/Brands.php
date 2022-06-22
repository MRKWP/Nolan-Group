<?php
/**
 * Register New Post Type with Fields via ACF Plugin.
 */
namespace Nolan_Group\PostTypes;

/**
 * Register New Post Type.
 * Add Fields via ACF Plugin.
 */
class Brands {
    
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
            'name'                  => _x( 'Brands', 'Post Type General Name', 'nolan-group' ),
            'singular_name'         => _x( 'Brand', 'Post Type Singular Name', 'nolan-group' ),
            'menu_name'             => __( 'Brands', 'nolan-group' ),
            'name_admin_bar'        => __( 'Brand', 'nolan-group' ),
            'archives'              => __( 'Brand Archives', 'nolan-group' ),
            'attributes'            => __( 'Brand Attributes', 'nolan-group' ),
            'parent_item_colon'     => __( 'Parent Brand:', 'nolan-group' ),
            'all_items'             => __( 'All Brands', 'nolan-group' ),
            'add_new_item'          => __( 'Add Brand', 'nolan-group' ),
            'add_new'               => __( 'Add New Brand', 'nolan-group' ),
            'new_item'              => __( 'New Brand', 'nolan-group' ),
            'edit_item'             => __( 'Edit Brand', 'nolan-group' ),
            'update_item'           => __( 'Update Brand', 'nolan-group' ),
            'view_item'             => __( 'View Brand', 'nolan-group' ),
            'view_items'            => __( 'View Brands', 'nolan-group' ),
            'search_items'          => __( 'Search Brand', 'nolan-group' ),
            'not_found'             => __( 'Not found', 'nolan-group' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'nolan-group' ),
            'featured_image'        => __( 'Featured Image', 'nolan-group' ),
            'set_featured_image'    => __( 'Set featured image', 'nolan-group' ),
            'remove_featured_image' => __( 'Remove featured image', 'nolan-group' ),
            'use_featured_image'    => __( 'Use as featured image', 'nolan-group' ),
            'insert_into_item'      => __( 'Insert into Brand', 'nolan-group' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Brand', 'nolan-group' ),
            'items_list'            => __( 'Brands list', 'nolan-group' ),
            'items_list_navigation' => __( 'Brands list navigation', 'nolan-group' ),
            'filter_items_list'     => __( 'Filter Brands list', 'nolan-group' ),
        );
        $rewrite = array(
            'slug'                  => 'brand',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $args = array(
            'label'                 => __( 'Brand', 'nolan-group' ),
            'description'           => __( 'Brand Items for Nolan Group', 'nolan-group' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'excerpt' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-awards',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'brands',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type( 'brand', $args );

    }

    /**
     * Add ACF Fields for the Post Type
     *
     * @return void
     */
    public function acf_fields() {

    }
}