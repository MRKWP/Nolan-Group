<?php
/**
 * Register New Post Type with Fields via MetaBox.
 */
namespace Nolan_Group\PostTypes;

/**
 * Register New Post Type.
 * Add Fields via Metabox.
 */
class CaseStudies {
    
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
            'name'                  => _x( 'Case Studies', 'Post Type General Name', 'nolan-group' ),
            'singular_name'         => _x( 'Case Study', 'Post Type Singular Name', 'nolan-group' ),
            'menu_name'             => __( 'Case Studies', 'nolan-group' ),
            'name_admin_bar'        => __( 'Case Study', 'nolan-group' ),
            'archives'              => __( 'Case Study Archives', 'nolan-group' ),
            'attributes'            => __( 'Case Study Attributes', 'nolan-group' ),
            'parent_item_colon'     => __( 'Parent Case Study:', 'nolan-group' ),
            'all_items'             => __( 'All Case Studies', 'nolan-group' ),
            'add_new_item'          => __( 'Add Case Study', 'nolan-group' ),
            'add_new'               => __( 'Add New Case Study', 'nolan-group' ),
            'new_item'              => __( 'New Case Study', 'nolan-group' ),
            'edit_item'             => __( 'Edit Case Study', 'nolan-group' ),
            'update_item'           => __( 'Update Case Study', 'nolan-group' ),
            'view_item'             => __( 'View Case Study', 'nolan-group' ),
            'view_items'            => __( 'View Case Studies', 'nolan-group' ),
            'search_items'          => __( 'Search Case Study', 'nolan-group' ),
            'not_found'             => __( 'Not found', 'nolan-group' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'nolan-group' ),
            'featured_image'        => __( 'Featured Image', 'nolan-group' ),
            'set_featured_image'    => __( 'Set featured image', 'nolan-group' ),
            'remove_featured_image' => __( 'Remove featured image', 'nolan-group' ),
            'use_featured_image'    => __( 'Use as featured image', 'nolan-group' ),
            'insert_into_item'      => __( 'Insert into Case Study', 'nolan-group' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Case Study', 'nolan-group' ),
            'items_list'            => __( 'Case Studies list', 'nolan-group' ),
            'items_list_navigation' => __( 'Case Studies list navigation', 'nolan-group' ),
            'filter_items_list'     => __( 'Filter Case Studies list', 'nolan-group' ),
        );
        $rewrite = array(
            'slug'                  => 'case-study',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $args = array(
            'label'                 => __( 'Case Study', 'nolan-group' ),
            'description'           => __( 'Case Study Items for Nolan Group', 'nolan-group' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'excerpt' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-id',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'case-studies',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type( 'case-study', $args );

    }

}