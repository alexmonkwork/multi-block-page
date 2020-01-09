<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://delay-delo.com
 * @since      1.0.0
 *
 * @package    Multiblock_Page
 * @subpackage Multiblock_Page/admin
 */
class Multiblock_Page_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
    {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

	    add_shortcode( 'mbp',  array( $this, 'mbp_shortcode_show') );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
    {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/multiblock-page-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
    {
	    wp_enqueue_script( 'wp-color-picker' );
	    wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/multiblock-page-admin.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Create menu link to the settings page
     *
	 */
	public function register_sub_menu()
	{
		add_submenu_page('edit.php?post_type=' . $this->plugin_name,'Option', __('Settings', $this->plugin_name),'manage_options','submenu-page', array($this,'display_plugin_setup_page'));
	}

	/**
	 * Set template for settings page
     *
	 */
	public function display_plugin_setup_page()
    {
        include_once( 'partials/multiblock-page-admin-display.php' );
    }

    /**
     * Validate options
     * @param array $input
     * @return array
     */
    public function mbp_validate_options_fields($input)
    {
        $valid = array();

        $valid['enable_gutenberg'] = (isset($input['enable_gutenberg']) && !empty($input['enable_gutenberg'])) ? 1 : 0;
	    $valid['custom_css'] = (isset($input['custom_css']) && !empty($input['custom_css'])) ? sanitize_text_field($input['custom_css']) : '';
	    $valid['custom_template'] = (isset($input['custom_template']) && !empty($input['custom_template'])) ? sanitize_text_field($input['custom_template']) : '';

        return $valid;
    }

    /**
     * Update all options
     *
     */
    public function mbp_options_update()
    {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'mbp_validate_options_fields'));
    }

    public function mbp_set_allow_gutenberg()
    {
        $options = get_option($this->plugin_name);
        $status = $options['enable_gutenberg'] == 1 ? true : false;

        return $status;
    }

    /**
     * Create new post type - multiblock-page
     *
     */
	public function create_multi_blocks_type()
	{
		register_post_type(
			$this->plugin_name,
			array(
				'labels' => array(
					'name'               => __(ucwords($this->plugin_name)),
					'singular_name'      => __(ucwords($this->plugin_name)),
					'name_admin_bar'     => __(ucwords($this->plugin_name)),
					'add_new'            => __( 'Add New ' . ucwords($this->plugin_name)),
					'add_new_item'       => __( 'Add New ' . $this->plugin_name),
					'new_item'           => __( 'New ' . $this->plugin_name),
					'edit_item'          => __( 'Edit ' . $this->plugin_name),
					'view_item'          => __( 'View ' . $this->plugin_name),
					'all_items'          => __( 'All ' . ucwords($this->plugin_name)),
					'search_items'       => __( 'Search ' . $this->plugin_name),
					'parent_item_colon'  => __( 'Parent : ' . $this->plugin_name),
					'not_found'          => __( 'No ' . $this->plugin_name . ' found.'),
					'not_found_in_trash' => __( 'No ' . $this->plugin_name . ' found in Trash.')
				),
				'public' 				=> false,
				'publicly_queryable' 	=> true,
				'show_ui'               => true,
				'supports'              => array( 'title', 'editor', 'revisions' ),
				'has_archive'           => false,
				'hierarchical'          => false,
				'capability_type' 		=> 'post',
				'show_in_rest'          => $this->mbp_set_allow_gutenberg(),
				'menu_position'         => 20,
				'show_in_admin_bar'     => true,
				'rewrite'               => false,
				'menu_icon'             => 'dashicons-tagcloud',
			)
		);
	}

	/**
	 * Create columns list
	 *
	 * @param array $columns
	 * @return array
	 * @since    1.0.0
	 */
	function mbp_columns_list($columns)
	{
		$columns = [
			'cb'        => $columns['cb'],
			'title'     => __( 'Title', $this->plugin_name ),
			'shortcode' => __( 'Shortcode', $this->plugin_name)
		];

		return $columns;
	}

	/**
	 * Column output shotcode
	 *
	 * @param array $column
	 * @var object $post
	 */
	function mbp_data_columns( $column )
	{
		$post = get_post();

		if(	$column == "shortcode")
		{
			echo  '<span class="copy">[mbp id="' .$post->ID. '"]</span>';
		}

	}

	/**
	 * Add meta box color picker and backgroung image
	 *
	 * @var array $screen
	 */
	public function mbp_add_meta_box()
	{
		$screens = ['post', 'multiblock-page'];
		foreach ($screens as $screen) {
			add_meta_box(
				'color_box_id',
				'Block background',
				[ $this, 'mbp_output_html'],
				$screen,
				'side'
			);
			add_meta_box(
				'image_box_id',
				'Backgroung image',
				[ $this, 'mbp_image_output_html' ],
				$screen,
				'side'
			);
		}
	}

	/**
	 * The output of the background selection block in html
	 *
	 * @param $post
	 */
	public function mbp_output_html($post)
	{
		$color = get_post_meta($post->ID, '_color_meta_key', true);
		?>
		<div class="pagebox">
			<p class="separator">
				<input class="color-field" type="hidden" name="color_field" value="<?php  esc_attr_e($color); ?>"/>
			</p>
		</div>
		<?php
	}

	/**
	 * Upload image and return template for button
	 *
	 * @param string $name
	 * @var string $image
	 * @var string $image_size
	 * @var mixed $image_attributes
	 * @var string $display
	 * @var string $html
	 * @return string
	 */
	public function mbp_upload_image($name, $value = '')
	{
		$image = ' button">'. __('Upload image');
		$image_size = 'full';
		$display = 'none';

		if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) )
		{
			$image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
			$display = 'inline-block';
		}

		$html = '<div> 
                    <a href="#" class="mbp_upload_image' . $image . '</a> 
                    <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
                    <a href="#" class="mbp_remove_image" style="display:' . $display . '">' . __('Remove image') . '</a>
                 </div>';
		return $html;
	}

	/**
	 * Returns a template with processed data
	 *
	 * @var string $meta_key - metabox name
	 * @param $post
	 */
	public function mbp_image_output_html($post)
	{
		$meta_key = 'background_img';
		echo $this->mbp_upload_image($meta_key, get_post_meta($post->ID, $meta_key, true));
	}

	/**
	 * Save meta box data
	 *
	 * @param $post_id - current post id
	 * @return mixed
	 */
	public function mbp_save_meta_box($post_id)
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if (array_key_exists('color_field', $_POST))
		{
			update_post_meta(
				$post_id,
				'_color_meta_key',
				sanitize_text_field( $_POST['color_field'] )
			);
		}

		if (array_key_exists('background_img', $_POST))
		{
			update_post_meta(
				$post_id,
				'background_img',
				sanitize_text_field( $_POST['background_img'] )
			);
		}
	}

	/**
	 * Attribute check. Is the integer and publication status obtained
	 *
	 * @param int $id
	 * @return int|string
	 */
	public function mbp_validate_shortcode_atrribytes($id)
	{
		$id = is_numeric($id) && get_post_status( $id ) === 'publish' ? $id : NULL ;

		return $id;
	}

	/**
	 * Returns a block template in html
	 *
	 * @param mixed $content
	 * @param string $backgroundColor
	 * @param string $image
	 * @param int $id
	 * @return string
	 */
	public function mbp_set_template_shortcode($content, $backgroundColor, $image, $id)
	{
		$template  = '';
		$template .= '<section id="section-'. $id .'" style="background: ' . $backgroundColor . ' ' . $image . '">';
		$template .= $content;
		$template .= '</section>';

		return $template;
	}

	/**
	 * Returns url of background image in html wrapper
	 * not specified by default
	 * @param int $id
	 * @return string
	 */
	public function mbp_return_background_image($id)
	{
		$imageId = get_post_meta( $id, 'background_img', TRUE );
		$image = wp_get_attachment_image_url( $imageId , 'full');
		$imageUrl = isset($image) ? 'url(' . $image . ') no-repeat' : '';

		return $imageUrl;
	}

	/**
	 * Returns url color code as hex
	 * not specified by default
	 * @param $id
	 * @return mixed|string
	 */
	public function mbp_return_background_color($id)
	{
		$colorHex = get_post_meta( $id, '_color_meta_key', TRUE );
		$backgroundColor = !empty($colorHex) ? $colorHex : '';

		return $backgroundColor;
	}

	/**
	 * Show shortcode with parameters
	 *
	 * @var int $id - current post id
	 * @var string $backgroundColor - color hex code
	 * @var string $backgroundImage  - gets url in the form of html
	 * @return string
	 */
	public function mbp_shortcode_show( $atts )
	{
		$param = shortcode_atts( [
			'id' => '1',
		], $atts );

		$id = $this->mbp_validate_shortcode_atrribytes($param['id']);
		$backgroundColor = $this->mbp_return_background_color($id);
		$backgroundImage = $this->mbp_return_background_image($id);

		if ( !is_null( $id ) )
		{
			$block_id= get_post( $id );
			$content = apply_filters( 'the_content', $block_id->post_content );
			$template = $this->mbp_set_template_shortcode( $content, $backgroundColor, $backgroundImage, $id );

		} else {
			$template = '';
		}

		return $template;
	}

}
