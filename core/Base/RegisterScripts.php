<?php
/**
 * Initialize classes in the register all global scripts and styles.
 *
 * @package  MRK_Logo_Carousel.
 */
namespace Nolan_Group\Base;

class RegisterScripts extends BaseController {
    
    /**
     * Register function is called by default to get the class running
     *
     * @return void
     */
    public function register() {
        // enqueue block assets
        add_action( 'enqueue_block_assets', [ $this, 'enqueue_mrklightbox_frontend_assets' ] );
        add_action( 'wp_block_assets', [ $this, 'enqueue_mrklightbox_frontend_assets' ] );
        add_action( 'enqueue_block_assets', [ $this, 'enqueue_mrklightbox_admin_scripts' ] );
    }
    
    
    /**
     * Enqueue external scripts and styles for the frontend
     *
     */
    public function enqueue_mrklightbox_frontend_assets() {
        // only enqueue when not in wordpress admin
        if( ! is_admin() ) {
            wp_enqueue_script('nolan-group-mrk-lightbox-library-js', $this->plugin_url.'assets/src/js/mrklightbox-bundle.js', ['nolan-group-mrk-lightbox-front-js'], NOLAN_GROUP_LIBRARY_VERSION, true);
            wp_enqueue_script('nolan-group-mrk-lightbox-front-js', $this->plugin_url.'assets/src/js/lightbox.js', [], NOLAN_GROUP_LIBRARY_VERSION, true);
        }
    }
    
    /**
     * Enqueue block script only in admin
     */
    public function enqueue_mrklightbox_admin_scripts() {
        // enqueue in wordpress admin only
        if(is_admin()) {
            wp_enqueue_script('mrk-lightbox-block-js', $this->plugin_url.'build/blocks/index.js', [], NOLAN_GROUP_LIBRARY_VERSION, true);
        }
    }
}