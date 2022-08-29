<?php
/**
 * Make a product entry
 * 
 * @package  Nolan_group
 */
namespace Nolan_Group\Makers;

use WP_Query, MB_Relationships_API;

Class ImageGallery{

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
     * All the gallery images for the gallery field
     *
     * @var Array
     */
    public $gallery_images;

    /**
	 * Constructor.
	 *
	 * @param string $sv_data
	 *
	 */
    public function __construct( $data ) {

        $this->data         = $data;
        $this->list_id      = $data['Reference ID'];
        $this->post_title   = $data['Product Name'];

        $upload_dir         = wp_upload_dir();

        $this->upload_dir   = $upload_dir['basedir'].DIRECTORY_SEPARATOR."nolan-group-import".DIRECTORY_SEPARATOR;

        $this->upload_url   = $upload_dir['baseurl']."/nolan-group-import";

        $this->gallery_images = array();
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
     * Update Image Field for Gallery
     */
    public function updateData(){

        //Process images
        if(!empty($this->data['Gallery 1'])){
            $attachment_id_1 = $this->processImage($this->data['Gallery 1']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_1);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_1, false );
        }
        if(!empty($this->data['Gallery 2'])){
            $attachment_id_2 = $this->processImage($this->data['Gallery 2']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_2);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_2, false );
        }
        if(!empty($this->data['Gallery 3'])){
            $attachment_id_3 = $this->processImage($this->data['Gallery 3']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_3);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_3, false );
        }
        if(!empty($this->data['Gallery 4'])){
            $attachment_id_4 = $this->processImage($this->data['Gallery 4']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_4);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_4, false );
        }
        if(!empty($this->data['Gallery 5'])){
            $attachment_id_5 = $this->processImage($this->data['Gallery 5']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_5);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_5, false );
        }
        if(!empty($this->data['Gallery 6'])){
            $attachment_id_6 = $this->processImage($this->data['Gallery 6']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_6);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_6, false );
        }
        if(!empty($this->data['Gallery 7'])){
            $attachment_id_7 = $this->processImage($this->data['Gallery 7']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_7);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_7, false );
        }
        if(!empty($this->data['Gallery 8'])){
            $attachment_id_8 = $this->processImage($this->data['Gallery 8']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_8);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_8, false );
        }
        if(!empty($this->data['Gallery 9'])){
            $attachment_id_9 = $this->processImage($this->data['Gallery 9']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_9);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_9, false );
        }
        if(!empty($this->data['Gallery 10'])){
            $attachment_id_10 = $this->processImage($this->data['Gallery 10']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_10);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_10, false );
        }
        if(!empty($this->data['Gallery 11'])){
            $attachment_id_11 = $this->processImage($this->data['Gallery 11']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_11);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_11, false );
        }
        if(!empty($this->data['Gallery 12'])){
            $attachment_id_12 = $this->processImage($this->data['Gallery 12']);
            delete_post_meta($this->post_id, 'gallery_images', $attachment_id_12);
            add_post_meta( $this->post_id, 'gallery_images', $attachment_id_12, false );
        }

        //Finished.
    }

    /**
     * Process a search for uploaded images
     *
     * @return void
     */
    public function processImage($image){

            //get the file path
            $image_file = $this->upload_dir.'gallery-images'.DIRECTORY_SEPARATOR.$image;

                //Check the files exists
                if(file_exists($image_file)){

                    //Get the attachment if it already exists
                    $attachment_id = $this->MediaFileAlreadyExists($image);

                    if(!$attachment_id){

                        //Create an image URL to pull the image into the media library
                        $image_url = $this->upload_url.'/gallery-images'.'/'.$image;

                        echo $image_url." <br>";

                        //Set the featured image if we are getting the first image
                        $attachment_id = $this->beliefmedia_import_image($image_url, $image);

                    }

                    return $attachment_id;
                    //Set the featured image if we are getting the first image
                    //Set_post_thumbnail($this->post_id, $attachment_id);

                }else{
                    echo "<br>File not found for Gallery Image<br>";
                    print($image_file);
                    echo "<br>";
                }
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