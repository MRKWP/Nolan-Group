<?php
/**
 * @package  mrk-lightbox
 */
namespace Nolan_Group\Blocks;

use Nolan_Group\Base\BaseController;

class LightboxAddOn extends BaseController {
    
    /**
     * Register MRK Lightbox Extension
     *
     * @return void
     */
    public function register() {
        add_action( 'enqueue_block_editor_assets' , [ $this, 'extend_core_gallery_block_editor_assets' ]);
        add_filter( 'render_block', [$this, 'modify_render_block_editor'], 10, 3 );
    }
    
    /**
     * Instantiate the Lightbox AddOn
     *
     */
    public function extend_core_gallery_block_editor_assets() {
        // Enqueue our script
        wp_enqueue_script(
            'mrk-lightbox-addon-script',
            $this->plugin_url. 'build/index.js',
            array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
            NOLAN_GROUP_LIBRARY_VERSION,
            true // Enqueue the script in the footer.
        );
    }
    
    
    public function modify_render_block_editor( $block_content, $block, $instance ) {
        if( $block['blockName'] == 'core/gallery') {
            
            $attrs = $block['attrs'];
            
            if(!empty($attrs['enableMrkLightBox']) && $attrs['enableMrkLightBox'] === true) {
                $enable_lightbox = $attrs['enableMrkLightBox'];
                $uniqueID = $attrs['mrkLightBoxUniqueID'];
                
                $block_content = sprintf('<mrkwp-lightbox data-enable-mrk-lightbox="%s" data-enable-mrk-lightbox-unique-id="%s" >%s</mrkwp-lightbox>', $enable_lightbox, $uniqueID, $block_content);
            }
        }
        
        return $block_content;
    }
}