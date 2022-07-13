<?php
/**
 * @package Nolan_Group;
 * Register Filters for Paginations
 */
namespace Nolan_Group\Filters;

/**
 * Handle the paginations and facet filtering related .
 */
class Paginations
{
    
    public function register() {
        add_filter( 'gettext', [ $this, 'replace_text' ], 10, 3 );
        add_filter('facetwp_shortcode_html', [ $this, 'replaces_class_facewp' ], 10, 2);
    }
    
    /**
     * Search and replaces Any with All in FaceWP
     *
     * @param $translated_text
     * @param $text
     * @param $domain
     * @return mixed|string
     */
    public function replace_text( $translated_text, $text, $domain ) {
        if ( 'fwp-front' == $domain && 'Any' == $text ) {
            $translated_text = 'All';
        }
        return $translated_text;
    }
    
    /**
     * Replace class name in facetwp to fix pagination design from facetwp compatible with blocksy
     *
     * @param $output
     * @param $atts
     */
    public function replaces_class_facewp( $output, $atts ) {
        if ( isset( $atts['pager'] ) ) {
            $output = str_replace('facetwp-pager', 'facetwp-pager nolan-group-paginations ct-pagination', $output);
        }
    
        return $output;
    }
}