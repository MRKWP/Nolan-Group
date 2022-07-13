<?php
/**
 * @package Nolan_Group;
 */
namespace Nolan_Group\Filters;

/**
 * Handle the breadcrumbs for post types.
 */
class Breadcrumbs
{
    public function register() {
       add_filter('wpseo_breadcrumb_links', [ $this, 'change_brochure_breadcrumb_to_resources' ]);
	}
    
    /**
     * Brochure breadcrumb filter to 'resourcest text in the breadcrumb 
     *
     * @param string  $url  Post Permalink.
     * @return string $url  Post Permalink.
     */

    public function change_brochure_breadcrumb_to_resources($links) {
    
        $post_type = get_post_type();

        if ($post_type === 'brochure') {
            
            //Get the page the "resources" text should link
            $resources = get_page_by_path('/resources');
            
            //Check if Page exists
            if (!empty($resources)){

                //Set the breadcrumb array with values inside $resources
                $breadcrumb[] = array(
                    'url' => get_permalink($resources),
                    'text' => $resources->post_name
                );

                //Set the Breadcrumb
                array_splice($links, 1, -3, $breadcrumb);

            }
                
        }
        return $links;

    }
}