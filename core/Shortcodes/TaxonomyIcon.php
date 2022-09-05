<?php
/**
 * Register Shortcode for Taxonomies Block
 *
 * @package  Nolan_Group
 */
namespace Nolan_Group\Shortcodes;

use Nolan_Group\Base\BaseController;

class TaxonomyIcon extends BaseController {
    /**
     * Register `taxonomy-icons` shortcode.
     *
     * @return void
     */
    public function register() {
        add_shortcode( 'taxonomy-icons', [ $this, 'get_taxonomy_icons'] );
    }
    
    
    /**
     * Get taxonomy icons is a render callback for the dynamic shortcode - taxonomy-icons;.
     * Returns a formatted list of html
     *
     * @param $attr
     * @param $content
     * @return string
     */
    public function get_taxonomy_icons($attr, $content) {
        $attr = wp_parse_args($attr, [
            'taxonomy'  => 'category',
        ]);
    
        $taxonomy = $attr['taxonomy'];
        
        $args = [
            'taxonomy'  => $taxonomy,
            'number'    => 10
        ];
    
        $terms = get_terms($args);
    
        if(empty($terms)) {
            return 'No terms found for this taxonomy';
        }
    
        wp_enqueue_style( 'nolan-group-library-taxonomy-icon', $this->plugin_url . '/assets/build/css/taxonomy-icon.css' );
        
        $count_items = count($terms);
    
        $grid_columns = "repeat({$count_items}, minmax(0,1fr))";
        if($count_items > 5) {
            $grid_columns = 'repeat(3, minmax(0,1fr))';
        }
    
        $post_list_formatted = '<div class="nolan-group-taxonomies-content">';
    
        $post_list_formatted .= sprintf('<ul class="nolan-group-taxonomies" style="grid-template-columns: %s">', $grid_columns);
    
        foreach ($terms as $term) {
            $term_id = $term->term_id;
        
            $term_name = $term->name;
        
            $term_link = get_term_link($term_id, $taxonomy);
        
            $term_icon_url = function_exists('get_term_featured_image') ? get_term_featured_image( $term_id ) : '';
        
            $post_list_formatted .= sprintf('<li class="nolan-group-taxonomy"><a href="%s" title="%s">', $term_link, $term_name);
        
            if(!empty($term_icon_url))  $post_list_formatted .= sprintf('<div class="nolan-group-taxonomy-icon"><img src="%s" alt="%s Icon" class="category-icon"></div>', $term_icon_url, $term_name);
        
            $post_list_formatted .= sprintf('<div class="nolan-group-taxonomy-name"><p>%s</p></div>', $term_name);
        
            $post_list_formatted .= '</a></li>';
        }
    
        $post_list_formatted .= '</ul>';
    
        $post_list_formatted .= '</div>';
    
        return $post_list_formatted;
        
    }
}
