<?php
/**
 * Register New Post Type with Fields via MetaBox.
 */
namespace Nolan_Group\PostTypes;

/**
 * Register New Post Type.
 * Add Fields via Metabox.
 */
class Brochures {
    
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
            'name'                  => _x( 'Brochures', 'Post Type General Name', 'nolan-group' ),
            'singular_name'         => _x( 'Brochure', 'Post Type Singular Name', 'nolan-group' ),
            'menu_name'             => __( 'Brochures', 'nolan-group' ),
            'name_admin_bar'        => __( 'Brochure', 'nolan-group' ),
            'archives'              => __( 'Brochure Archives', 'nolan-group' ),
            'attributes'            => __( 'Brochure Attributes', 'nolan-group' ),
            'parent_item_colon'     => __( 'Parent Brochure:', 'nolan-group' ),
            'all_items'             => __( 'All Brochures', 'nolan-group' ),
            'add_new_item'          => __( 'Add Brochure', 'nolan-group' ),
            'add_new'               => __( 'Add New Brochure', 'nolan-group' ),
            'new_item'              => __( 'New Brochure', 'nolan-group' ),
            'edit_item'             => __( 'Edit Brochure', 'nolan-group' ),
            'update_item'           => __( 'Update Brochure', 'nolan-group' ),
            'view_item'             => __( 'View Brochure', 'nolan-group' ),
            'view_items'            => __( 'View Brochures', 'nolan-group' ),
            'search_items'          => __( 'Search Brochure', 'nolan-group' ),
            'not_found'             => __( 'Not found', 'nolan-group' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'nolan-group' ),
            'featured_image'        => __( 'Featured Image', 'nolan-group' ),
            'set_featured_image'    => __( 'Set featured image', 'nolan-group' ),
            'remove_featured_image' => __( 'Remove featured image', 'nolan-group' ),
            'use_featured_image'    => __( 'Use as featured image', 'nolan-group' ),
            'insert_into_item'      => __( 'Insert into Brochure', 'nolan-group' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Brochure', 'nolan-group' ),
            'items_list'            => __( 'Brochures list', 'nolan-group' ),
            'items_list_navigation' => __( 'Brochures list navigation', 'nolan-group' ),
            'filter_items_list'     => __( 'Filter Brochures list', 'nolan-group' ),
        );
        $rewrite = array(
            'slug'                  => 'brochure',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $args = array(
            'label'                 => __( 'Brochure', 'nolan-group' ),
            'description'           => __( 'Brochure Items for Nolan Group', 'nolan-group' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'thumbnail', 'revisions' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-info-outline',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'brochures',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type( 'brochure', $args );

    }

}