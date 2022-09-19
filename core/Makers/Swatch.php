<?php
/**
 * Make a product entry
 *
 * @package  Nolan_group
 */
namespace Nolan_Group\Makers;

use WP_Query, MB_Relationships_API;

Class Swatch{

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
        $this->list_id      = $data['Product ID'];
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

            return $this->post_id;

        endif;

        return false;
        
    }


    /**
     * Update Image Field for Gallery
     */
    public function updateData(){

        //Process images
        if(!empty($this->data['File Path'])){
            if (str_contains($this->data['File Path'], 'http')) {
                
                $image = str_replace("https://nolans.com.au/wp-content/uploads/","", $image_url);
                $swatch['swatch_image'] = $this->processImage($image, true, $this->data['File Path']);

            }else{
                $swatch['swatch_image'] = $this->processImage($this->data['File Path'], false, "Local File");
            }
        }
        
        $swatch['id']           =   $this->data['Wordpress Swatch ID'];
        $swatch['swatch_name']  =   $this->data['Colour Name'];
        $swatch['swatch_color'] =   $this->data['Colour Family'];
    
        add_post_meta( $this->post_id, 'swatch', $swatch, false );
    
        // update the color
        $swatch['product_tile_color'] = $this->data['Colour Family'];
        $previous_value = (array) get_post_meta($this->post_id, 'product_tile_color');
        if(!empty($this->data['Colour Family']) && !in_array($this->data['Colour Family'], $previous_value)) {
            array_push($previous_value, $this->data['Colour Family']);
            add_post_meta( $this->post_id, 'product_tile_color', $this->data['Colour Family']);
        }

        //Finished.
    }

    /**
     * Process a search for uploaded images
     *
     * @return number
     */
    public function processImage($image, $remote, $remote_url){

        $image_url = $this->upload_url.'/swatch-images'.'/'.$image;

        //If file is local we need to check we can find it for import
        if($remote==true){
            $image_url = $remote_url;
        }

        $image_file = $this->upload_dir.'swatch-images'.DIRECTORY_SEPARATOR.$image;

        //Check the files exists
        if(file_exists($image_file)){

            //Get the attachment if it already exists
            $attachment_id = $this->MediaFileAlreadyExists($image);

            if(!$attachment_id){

                //Create an image URL to pull the image into the media library
                if(isset($image_url)){
                    echo $image_url." <br>";
                }

                //Set the featured image if we are getting the first image
                $attachment_id = $this->beliefmedia_import_image($image_url, $image);

            }

            return $attachment_id;

        }else{
            echo "<br>File not found for Swatch Image<br>";
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