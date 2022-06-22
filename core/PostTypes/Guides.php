<?php
/**
 * Register New Post Type with Fields via MetaBox.
 */
namespace Nolan_Group\PostTypes;

/**
 * Register New Post Type.
 * Add Fields via Metabox.
 */
class Guides {
    
    /**
     * Register New Post Type.
     *
     * @return void
     */
    public function register() {
        add_action( 'init', [$this, 'cpt_init'], 9);
    }

    /**
     * Post Type Arguments and Registration
     *
     * @return void
     */
    // Register Custom Post Type
    function cpt_init() {

        $labels = array(
            'name'                  => _x( 'Guides', 'Post Type General Name', 'nolan-group' ),
            'singular_name'         => _x( 'Guide', 'Post Type Singular Name', 'nolan-group' ),
            'menu_name'             => __( 'Guides', 'nolan-group' ),
            'name_admin_bar'        => __( 'Guide', 'nolan-group' ),
            'archives'              => __( 'Guide Archives', 'nolan-group' ),
            'attributes'            => __( 'Guide Attributes', 'nolan-group' ),
            'parent_item_colon'     => __( 'Parent Guide:', 'nolan-group' ),
            'all_items'             => __( 'All Guides', 'nolan-group' ),
            'add_new_item'          => __( 'Add Guide', 'nolan-group' ),
            'add_new'               => __( 'Add New Guide', 'nolan-group' ),
            'new_item'              => __( 'New Guide', 'nolan-group' ),
            'edit_item'             => __( 'Edit Guide', 'nolan-group' ),
            'update_item'           => __( 'Update Guide', 'nolan-group' ),
            'view_item'             => __( 'View Guide', 'nolan-group' ),
            'view_items'            => __( 'View Guides', 'nolan-group' ),
            'search_items'          => __( 'Search Guide', 'nolan-group' ),
            'not_found'             => __( 'Not found', 'nolan-group' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'nolan-group' ),
            'featured_image'        => __( 'Featured Image', 'nolan-group' ),
            'set_featured_image'    => __( 'Set featured image', 'nolan-group' ),
            'remove_featured_image' => __( 'Remove featured image', 'nolan-group' ),
            'use_featured_image'    => __( 'Use as featured image', 'nolan-group' ),
            'insert_into_item'      => __( 'Insert into Guide', 'nolan-group' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Guide', 'nolan-group' ),
            'items_list'            => __( 'Guides list', 'nolan-group' ),
            'items_list_navigation' => __( 'Guides list navigation', 'nolan-group' ),
            'filter_items_list'     => __( 'Filter Guides list', 'nolan-group' ),
        );
        $rewrite = array(
            'slug'                  => 'guide',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $args = array(
            'label'                 => __( 'Guide', 'nolan-group' ),
            'description'           => __( 'Guide Items for Nolan Group', 'nolan-group' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'excerpt' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-info-outline',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'guides',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type( 'guide', $args );

    }

}