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
}