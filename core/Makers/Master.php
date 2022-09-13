<?php
/**
 * Make a product entry
 *
 * @package  Nolan_group
 */
namespace Nolan_Group\Makers;

use WP_Query, MB_Relationships_API;

Class Master{

    /**
     * This is the Data Object from the CSV
     *
     * @var [type]
     */
    public $data = Array();

    /**
	 * Post ID for the new object.
	 *
	 * @var int|bool WordPress Post ID or false if empty
	 */
    public $post_id = false;

    /**
	 * List ID from AssetEx
	 *
	 * @var int|bool listing ID or false if empty
	 */
    public $list_id = false;

    /**
     * does this object need to be processed?
     *
     * @var bool true when we should update
     */
    public $process_req = false;


    /**
     * The name of the post type for second hand machinery
     *
     * @var string
     */
    public $post_type = 'nolan-product';

    /**
     * Used to setup the post title or name of the second hand machinery listing.
     *
     * @param [type] $post title
     */
    public $post_title;

    /**
     * Upload directory for the currently processed CSV
     *
     * @var String
     */
    public $upload_dir;

    /**
     * Upload URL for the currently processed CSV
     *
     * @var String
     */
    public $upload_url;

    /**
	 * Constructor.
	 *
	 * @param string $sv_data
	 *
	 */
    public function __construct( $data ) {

        $this->data         = $data;
        $this->list_id      = $data['Product ID'];
        $this->post_title   = $data['Product Name'];

        $upload_dir         = wp_upload_dir();

        $this->upload_dir   = $upload_dir['basedir'].DIRECTORY_SEPARATOR."nolan-group-import".DIRECTORY_SEPARATOR;

        $this->upload_url   = $upload_dir['baseurl']."/nolan-group-import";
    }


    /**
     * initialise the CSV Data and matchup the ID if it already exists inside our database
     *
     * @return int
     */
    public function initData(){

        $args = array(
            'numberposts'	=> 1,
            'post_type'		=> $this->post_type,
            'meta_key'		=> 'nolan_external_id',
            'meta_value'    => $this->list_id,
            'compare'       => '='
            );

        // query the item
        $the_query = new WP_Query( $args );

        //We found posts and need to do an update
        if( $the_query->have_posts() ):
            while ( $the_query->have_posts() ) :
                $the_query->the_post();
                $this->post_id = $the_query->post->ID;
            endwhile;
        else:
            $this->process_req = true;

            //map up my insert
            $some_post = array(
                'post_title' => $this->post_title,
                'post_name' => sanitize_title_with_dashes($this->post_title,'','save'),
                'post_type' => $this->post_type,
                'post_content' => 'Content',
                'post_status' => 'draft'
            );
        
            $this->post_id = wp_insert_post($some_post);
            
        endif;

        return $this->post_id;
    }


    /**
     * Update ACF Fields
     */
    public function updateData(){

        //Process Featured Image
        $this->processThumb();

        //Set the Catagory
        $this->setCategory();

        //Set meta fields that are one to one
        rwmb_set_meta( $this->post_id, 'nolan_external_id', $this->list_id );

        rwmb_set_meta( $this->post_id, 'description', $this->data['Product Description'] );
        rwmb_set_meta( $this->post_id, 'application', $this->data['Application'] );
        rwmb_set_meta( $this->post_id, 'material', $this->data['Material Type'] );
        rwmb_set_meta( $this->post_id, 'width', $this->data['Width - CM'] );
        rwmb_set_meta( $this->post_id, 'weight', $this->data['Weight - GSM'] );
        rwmb_set_meta( $this->post_id, 'roll_length', $this->data['Roll Length - M'] );
        
        // request for a sample
        if(!empty($this->data['Request a sample'])) {
            $request_sample = $this->data['Request a sample'];
            $request_sample_value = $request_sample == 'Enabled' ? '1' : '0';
            rwmb_set_meta( $this->post_id, 'checkbox_request_sample', $request_sample_value );
        }
    
        if(!empty($this->data['Technical Guides'])) {
            $technical_guides = $this->data['Technical Guides'];
            $technical_guides_value = $technical_guides == 'Enabled' ? '1' : '0';
            rwmb_set_meta( $this->post_id, 'checkbox_tech_guides', $technical_guides_value );
        }

        // get the current brand relationship
        $previous_linked_brands  = new \WP_Query( [
            'relationship' => [
                'id' => 103,
                'from' => $this->post_id,
            ],
        ] );

        while ( $previous_linked_brands->have_posts() ) : $previous_linked_brands->the_post();
            // Loop through and delete all existing relationships
            foreach ( $previous_linked_brands->posts as $p ) :
                MB_Relationships_API::delete( $this->post_id, $p->ID, 103);
            endforeach;
        endwhile;
        
        //set nwe brand relationship
        MB_Relationships_API::add( $this->post_id, $this->data['Brand'], 103, 1, 1 );

        //set the features
        $features = explode("|",$this->data['Features (Only 5)']);
        $featureitems = array();

        if(is_array($features)){

            foreach($features as $feature){
                array_push($featureitems, array(0=>$feature));
            }

            rwmb_set_meta( $this->post_id, 'features_and_benefits', $featureitems );
        }
        
        //set the technical spec
        $techspecs = explode("|",$this->data['Technical Specifications']);
        
        $techitems = array();

        if(is_array($techspecs)){
            
            foreach($techspecs as $techspec){
                array_push($techitems, array(0=>$techspec));
            }

            rwmb_set_meta( $this->post_id, 'technical_specifications', $techitems );
        }

        //Publish the post
        wp_publish_post($this->post_id);

    }

    /**
     * Process a search for uploaded images
     *
     * @return void
     */
    public function processThumb(){

            //get the file path
            $image = $this->data['Thumbnail'];
            $image_file = $this->upload_dir.'thumb-images'.DIRECTORY_SEPARATOR.$image;

                //Check the files exists
                if(file_exists($image_file)){

                    //Get the attachment if it already exists
                    $attachment_id = $this->MediaFileAlreadyExists($image);

                    if(!$attachment_id){

                        //Create an image URL to pull the image into the media library
                        $image_url = $this->upload_url.'/thumb-images'.'/'.$image;

                        echo $image_url." <br>";

                        //Set the featured image if we are getting the first image
                        $attachment_id = $this->beliefmedia_import_image($image_url, $image);

                    }

                    //Set the featured image if we are getting the first image
                     set_post_thumbnail($this->post_id, $attachment_id);

                }else{
                    echo "<br>File not found for thumbnail<br>";
                    print($image_file);
                    echo "<br>";
                }
    }


    /**
     * Set Category for the product items
     *
     * @return void
     */
    public function setCategory(){
    
        $taxonomy = "product-category";
        $terms = array();
        
        // check if the primary category is set, if it is then update the terms array
        if(!empty($this->data['Primary Category'])) {
            $primary_category_id = $this->data['Primary Category'];
            
            $check_term = get_term_by('term_id', $primary_category_id, $taxonomy);
            if(!is_wp_error($check_term)) {
                array_push($terms, $primary_category_id);
            }
        }
    
        // check if the secondary category is set, if it is then update the terms array
        if(!empty($this->data['Secondary category'])) {
            $secondary_categories = $this->data['Secondary category'];
            
            $secondary_categories = (array) explode("|", $secondary_categories);
            
            foreach ($secondary_categories as $secondary_category) {
                $check_term = get_term_by('term_id', $secondary_category, $taxonomy);
                if(!is_wp_error($check_term)) {
                    array_push($terms, $secondary_category);
                }
            }
        }
    
        wp_set_post_terms( $this->post_id, $terms, $taxonomy );
    }


    /**
     * returns the Post ID or Attachment ID for a media file. Gets oldest.
     *
     * @param [type] $filename
     * @return [number] attachment_id
     */
    public function MediaFileAlreadyExists($filename){
        global $wpdb;
        $query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'";
        return $wpdb->get_var($query);
    }


    /**
     * do a sideload of an image import
     *
     * @param [string] $url
     * @param [string] $description
     * @return [number] attachment_id
     */
    public function beliefmedia_import_image($url, $description) {
        
        global $wpdb;
        
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        
        /* https://codex.wordpress.org/Function_Reference/media_sideload_image */
        return media_sideload_image($url, $this->post_id, $description,'id');
        
    }
}