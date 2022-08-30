<?php
/**
 * Register Shortcode for Global Contact CTA
 *
 * @package  Nolan_Group
 */
namespace Nolan_Group\Shortcodes;

use Nolan_Group\Base\BaseController;

class ContactCTA extends BaseController {
    
    /**
     * Register `global-contact-cta` shortcode.
     *
     * @return void
     */
    public function register() {
        add_shortcode( 'global-contact-cta', [ $this, 'get_contact_cta_block'] );
    }
    
    
    /**
     * Get contact cta block is a render callback for the dynamic shortcode - global-contact-cta;.
     * Returns a formatted list of html
     *
     * @param $attr
     * @param $content
     * @return string
     */
    public function get_contact_cta_block($attr, $content) {
        $attr = wp_parse_args($attr, [
            'button_text'  => __('CONTACT US', 'nolan-group'),
            'button_url'  => '',
            'header_text'  => __('Need more information about our products?', 'nolan-group'),
            'subheader_text'  => __('Get in contact with our friendly team today.', 'nolan-group'),
        ]);
    
        if(empty($attr['button_url']))  return 'The Button URL (button_url) is missing.';
        
        $button_url = $attr['button_url'];
        $button_text = $attr['button_text'];
        $header_text = $attr['header_text'];
        $subheader_text = $attr['subheader_text'];
    
        wp_enqueue_style( 'nolan-group-library-global-contact-cta', $this->plugin_url . '/assets/build/css/global-contact-cta.css' );
    
        $post_list_formatted = '<div class="nolan-group-global-contact-cta-container">';
        $post_list_formatted .= '<div class="nolan-group-global-contact-cta-header-text">';
        $post_list_formatted .= sprintf('<h2>%s</h2>', $header_text);
        $post_list_formatted .= sprintf('<h3>%s</h3>', $subheader_text);
        $post_list_formatted .= '</div>';
        $post_list_formatted .= sprintf('<a href="%1$s" title="%2$s">%2$s <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.47 5.47a.75.75 0 0 1 1.06 0l6 6a.75.75 0 0 1 0 1.06l-6 6a.75.75 0 1 1-1.06-1.06L14.94 12 9.47 6.53a.75.75 0 0 1 0-1.06Z" fill="#ffffff"/></svg></a>', $button_url, $button_text);
        $post_list_formatted .= '</div>';
        
        return $post_list_formatted;
    }
}