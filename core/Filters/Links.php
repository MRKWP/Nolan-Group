<?php
/**
 * @package Nolan_Group;
 * Register New Post Type with Fields via MetaBox.
 */
namespace Nolan_Group\Filters;

/**
 * Handle the brochure and guide links.
 */
class Links
{
	public function register() {
		add_filter( 'post_type_link', [ $this, 'change_brochure_permalink_to_pdf' ], 8, 2 );
        add_filter( 'post_type_link', [ $this, 'change_guide_permalink_to_pdf' ], 8, 2 );
	}

	/**
     * Prefix Brochure CPT permalink filter to redirect to PDF.
     *
     * @param string  $url  Post Permalink.
     * @param Object  $post Post Object.
     * @return string $url  Post Permalink.
     */
    public function change_brochure_permalink_to_pdf( $url, $post ) {

        if ( 'brochure' === $post->post_type ) {

            //Get the file with a limit of 1 from advanced field
            $files = rwmb_meta( 'brochure_pdf_ref', ['limit' => 1] );

            //do_action( 'qm/info', $files['0']['url'] );
            
            //No file return empty
            if ( empty($files['0']['url'])){
                return;
            } 

            //set URL;
			$url = $files['0']['url'];

            return $url;

        }

        return $url;

	}

    /**
     * Prefix Guide CPT permalink filter to redirect to PDF.
     *
     * @param string  $url  Post Permalink.
     * @param Object  $post Post Object.
     * @return string $url  Post Permalink.
     */
    public function change_guide_permalink_to_pdf( $url, $post ) {

        if ( 'guide' === $post->post_type ) {
			
            //Get the file with a limit of 1 from advanced field
            $files = rwmb_meta( 'brochure_pdf_ref', ['limit' => 1] );
            
            //No file return empty
            if ( empty($files['0']['url'])){
				$files = $this->get_missing_file($post->ID);
				if(!empty($files[0]->meta_value)){
					return $upload_dir['basedir'].'/'.$files[0]->meta_value;
				}else{
					return;	
				}
            } 

            //set URL;
			$url = $files['0']['url'];

            return $url;

        }

        return $url;

	}
       /**
     * Custom function for when the related PDF cannot be found
     *
     * @param string  $id a post ID for the related meta value
     * @return array $wpdb->result sql return. Is an Array of Objects
     */
	public function get_missing_file($id){

   			global $wpdb;
    		$attachment_id = $wpdb->get_results("SELECT meta_value FROM `{$wpdb->prefix}postmeta` WHERE `post_id` = '{$id}' AND `meta_key` = 'brochure_pdf_ref' LIMIT 1");
			//do_action('qm/info', $attachment_id[0]->meta_value);
			
			return $wpdb->get_results("SELECT meta_value FROM `{$wpdb->prefix}postmeta` WHERE `post_id` = '{$attachment_id[0]->meta_value}' AND `meta_key` = '_wp_attached_file' LIMIT 1");

	}
}
