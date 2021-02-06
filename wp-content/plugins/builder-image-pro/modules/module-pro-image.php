<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Image Pro
 * Description: 
 */

class TB_Image_Pro_Module extends Themify_Builder_Component_Module {

    function __construct() {
	parent::__construct(array(
	    'name' => __('Image Pro', 'builder-image-pro'),
	    'slug' => 'pro-image',
		'category' => array('addon')
	));
    }

    function get_assets() {
		$instance = Builder_Image_Pro::get_instance();
		$url=$instance->url . 'assets/';
		return array(
			'async'=>true,
			'css' => themify_enque($url . 'style.css'),
			'js' => themify_enque($url . 'scripts.js'),
			'url'=>$url,
			'ver' => $instance->version
		);
    }
    
    public function get_icon(){
	return 'gallery';
    }

    public function get_options() {
	$colors = Themify_Builder_Model::get_colors();
	array_shift($colors);
	$colors[] = array('img' => 'transparent', 'value' => 'transparent', 'label' => __('Transparent', 'themify'));
	$colors[] = array('img' => 'white', 'value' => 'white', 'label' => __('White', 'builder-image-pro'));
	$colors[] = array('img' => 'outline', 'value' => 'outline', 'label' => __('Outline', 'builder-image-pro'));

	$options = array(
	    array(
		'id' => 'mod_title_image',
		'type' => 'title'
	    ),
	    self::get_seperator( __('Image', 'builder-image-pro') ),
	    array(
		'id' => 'url_image',
		'type' => 'image',
		'label' => __('Image URL', 'builder-image-pro'),
		'class' => 'fullwidth'
	    ),
	    array(
		'id' => 'link_image_type',
		'type' => 'radio',
		'label' => __('Link Type', 'builder-image-pro'),
		'options' => array(
		    array('value' => 'image_external', 'name' => __('Link', 'builder-image-pro')),
		    array('value' => 'image_lightbox_link', 'name' => __('Lightbox', 'builder-image-pro')),
		    array('value' => 'image_modal', 'name' => __('Text modal', 'builder-image-pro'))
		),
		'wrap_class' => 'tb_compact_radios',
		'help' =>  __('(it will open text content in a lightbox)', 'builder-image-pro'),
		'option_js' => true
	    ),
	    array(
		'id' => 'image_content_modal',
		'type' => 'wp_editor',
		'wrap_class' => 'tb_group_element_image_modal'
	    ),
	    array(
		'id' => 'link_image',
		'type' => 'url',
		'label' => '',
		'before' => __('Image Link', 'builder-image-pro'),
		'class' => 'fullwidth',
		'wrap_class' => 'tb_group_element_image_external tb_group_element_image_lightbox_link',
		'control' => false
	    ),
	    array(
		'id' => 'link_image_new_window',
		'type' => 'toggle_switch',
		'label' => __('New Window', 'builder-image-pro'),
		'options' => 'simple',
		'wrap_class' => 'tb_group_element_image_external',
		'control' => false
	    ),
	    array(
		'type' => 'multi',
		'label' => __('Lightbox Dimension', 'themify'),
		'options' => array(
		    array(
			'id' => 'lightbox_width',
			'type' => 'number',
			'label' => 'w',
			'control' => false
		    ),
		    array(
			'id' => 'lightbox_size_unit_width',
			'type' => 'select',
			'label' => __('Units', 'themify'),
			'options' => array(
			    'pixels' => __('px ', 'themify'),
			    'percents' => __('%', 'themify')
			),
			'control' => false
		    ),
		    array(
			'id' => 'lightbox_height',
			'type' => 'number',
			'label' => 'ht',
			'control' => false
		    ),
		    array(
			'id' => 'lightbox_size_unit_height',
			'type' => 'select',
			'label' => __('Units', 'themify'),
			'options' => array(
			    'pixels' => __('px ', 'themify'),
			    'percents' => __('%', 'themify')
			),
			'control' => false
		    )
		),
		'wrap_class' => 'tb_group_element_image_lightbox_link tb_group_element_image_modal'
	    ),
	    array(
		'id' => 'image_size_image',
		'type' => 'select',
		'label' => __('Image Size', 'builder-image-pro'),
		'hide' => !Themify_Builder_Model::is_img_php_disabled(),
		'image_size' => true
	    ),
	    array(
		'id' => 'width_image',
		'type' => 'number',
		'label' =>'w',
		'after' => 'px'
	    ),
	    array(
		'id' => 'appearance_image',
		'type' => 'checkbox',
		'label'=>'',
		'wrap_class' => 'auto_fullwidth',
		'options' => array(
		    array('name' => 'fullwidth_image', 'value' => __('Auto fullwidth image', 'builder-image-pro'))
		)
	    ),
	    array(
		'id' => 'height_image',
		'type' => 'number',
		'label' => 'ht',
		'after' => 'px'
	    ),
	    array(
		'id' => 'appearance_image2',
		'type' => 'checkbox',
		'label' => __('Appearance', 'builder-image-pro'),
		'options' => array(
		    array('name' => 'rounded', 'value' => __('Rounded', 'builder-image-pro')),
		    array('name' => 'circle', 'value' => __('Circle', 'builder-image-pro'), 'help' => __('Circle style works better for square image ratio.', 'builder-image-pro')),
		)
	    ),
	    array(
		'id' => 'image_filter',
		'type' => 'select',
		'label' => __('Image Filter', 'builder-image-pro'),
		'options' => array(
		    'none' => __('', 'builder-image-pro'),
		    'grayscale' => __('Grayscale', 'builder-image-pro'),
		    'sepia' => __('Sepia', 'builder-image-pro'),
		    'blur' => __('Blur', 'builder-image-pro'),
		)
	    ),
	    array(
		'id' => 'image_effect',
		'type' => 'select',
		'label' => __('Hover Effect', 'builder-image-pro'),
		'options' => array(
		    'none' => '',
		    'grayscale-reverse' => __('Grayscale Reverse', 'builder-image-pro'),
		    'zoomin' => __('Zoom In', 'builder-image-pro'),
		    'zoomout' => __('Zoom Out', 'builder-image-pro'),
		    'rotate' => __('Rotate', 'builder-image-pro'),
		    'shine' => __('Shine', 'builder-image-pro'),
		    'glow' => __('Glow', 'builder-image-pro'),
		)
	    ),
	    array(
		'id' => 'image_alignment',
		'label' => __('Alignment', 'builder-image-pro'),
		'type' => 'icon_radio',
		'aligment2' => true
	    ),
	    self::get_seperator( __('Overlay', 'builder-image-pro') ),
	    array(
		'id' => 'title_image',
		'type' => 'text',
		'label' => __('Image Title', 'builder-image-pro'),
		'class' => 'fullwidth'
	    ),
	    array(
		'id' => 'caption_image',
		'type' => 'textarea',
		'label' => __('Image Caption', 'builder-image-pro'),
		'class' => 'fullwidth'
	    ),
	    array(
		'id' => 'action_button',
		'type' => 'text',
		'label' => __('Action Button', 'builder-image-pro'),
		'class' => 'fullwidth'
	    ),
	    array(
		'id' => 'link_address',
		'type' => 'url',
		'label' => __('Button Link', 'builder-image-pro'),
		'class' => 'fullwidth',
		'binding' => array(
		    'empty' => array(
			'hide' => array('link_type', 'link_new_window')
		    ),
		    'not_empty' => array(
			'show' => array('link_type', 'link_new_window')
		    )
		),
		'wrap_class' => 'tb_group_element_external tb_group_element_lightbox_link'
	    ),
	    array(
		'id' => 'link_type',
		'type' => 'radio',
		'label' => __('Button Link Type', 'builder-image-pro'),
		'options' => array(
		    array('value' => 'external', 'name' => __('Link', 'builder-image-pro')),
		    array('value' => 'lightbox_link', 'name' => __('Lightbox Link', 'builder-image-pro')),
		    array('value' => 'modal', 'name' => __('Text modal', 'builder-image-pro'))
		),
		'help' => sprintf('<span class="tb_group_element_modal">%s</span>', __('(it will open text content in a lightbox)', 'builder-image-pro')),
		'option_js' => true,
		'control' => false
	    ),
	    array(
		'id' => 'link_new_window',
		'type' => 'toggle_switch',
		'label' => __('New Window', 'builder-image-pro'),
		'options' => 'simple',
		'wrap_class' => 'tb_group_element_external',
		'control' => false
	    ),
	    array(
		'id' => 'color_button',
		'type' => 'layout',
		'mode' => 'sprite',
		'class' => 'tb_colors',
		'label' => __('Button Color', 'builder-image-pro'),
		'options' => $colors
	    ),
	    array(
		'id' => 'content_modal',
		'type' => 'wp_editor',
		'wrap_class' => 'tb_group_element_modal'
	    ),
	    array(
		'id' => 'overlay_effect',
		'type' => 'select',
		'label' => __('Overlay Effect', 'builder-image-pro'),
		'options' => array(
		    'none' => __('No Effect', 'builder-image-pro'),
		    'fadeIn' => __('Fade In', 'builder-image-pro'),
		    'partial-overlay' => __('Partial Overlay', 'builder-image-pro'),
		    'flip-horizontal' => __('Horizontal Flip', 'builder-image-pro'),
		    'flip-vertical' => __('Vertical Flip', 'builder-image-pro'),
		    'fadeInUp' => __('fadeInUp', 'builder-image-pro'),
		    'fadeInLeft' => __('fadeInLeft', 'builder-image-pro'),
		    'fadeInRight' => __('fadeInRight', 'builder-image-pro'),
		    'fadeInDown' => __('fadeInDown', 'builder-image-pro'),
		    'zoomInUp' => __('zoomInUp', 'builder-image-pro'),
		    'zoomInLeft' => __('zoomInLeft', 'builder-image-pro'),
		    'zoomInRight' => __('zoomInRight', 'builder-image-pro'),
		    'zoomInDown' => __('zoomInDown', 'builder-image-pro'),
		),
	    ),
	    array(
		'type' => 'multi',
		'label' => __('Overlay', 'builder-image-pro'),
		'options' => array(
		    array(
			'id' => 'overlay_color',
			'type' => 'color',
			'label' => __('Overlay Color', 'builder-image-pro'),
			'class' => 'small'
		    ),
		    array(
			'id' => 'overlay_image',
			'type' => 'image',
			'label' => __('Overlay Image', 'builder-image-pro'),
			'class' => 'xlarge',
		    ),
		)
	    ),
	    array(
		'id' => 'css_image',
		'type' => 'custom_css'
	    ),
	    array('type' => 'custom_css_id')
	);
	return $options;
    }

    public function get_live_default() {
	return array(
	    'overlay_effect' => 'fadeIn',
	    'image_alignment' => '',
	    'link_image_new_window'=>'yes',
	    'color_button'=>'black'
	);
    }

    public function get_styling() {
	/*START temp solution when the addon is new,the FW is old 09.03.19*/
	if(version_compare(THEMIFY_VERSION, '4.5', '<')){
	    return array(); 
	}
	$general = array(
	    //bacground
	    self::get_expand('bg', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_color('', 'background_color', 'bg_c', 'background-color')
			)
		    ),
		    'h' => array(
			'options' => array(
			   self::get_color('', 'bg_c', 'bg_c', 'background-color', 'h')
			)
		    )
		))
	    )),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(array(' .image-pro-caption', ' .image-pro-title', ' .image-pro-action-button')),
			    self::get_color_type(array(' .image-pro-title', ' .image-pro-caption', ' .image-pro-action-button')),
			    self::get_font_size(),
			    self::get_line_height(),
			    self::get_text_align(),
				self::get_text_shadow(array(' .image-pro-caption', ' .image-pro-title', ' .image-pro-action-button')),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family(array(' .image-pro-caption', ' .image-pro-title', ' .image-pro-action-button'),'f_f','h'),
			    self::get_color_type(array(' .image-pro-title', ' .image-pro-caption', ' .image-pro-action-button'),'h'),
			    self::get_font_size('', 'f_s', '', 'h'),
			    self::get_line_height('', 'l_h', 'h'),
			    self::get_text_align('', 't_a', 'h'),
				self::get_text_shadow(array(' .image-pro-caption', ' .image-pro-title', ' .image-pro-action-button'),'t_sh','h'),
			)
		    )
		))
	    )),
	    // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding(),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding('', 'p', 'h')
			)
		    )
		))
	    )),
            // Margin
	    self::get_expand('m', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_margin()
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_margin('', 'm', 'h'),
			)
		    )
		))
	    )),
            // Border
	    self::get_expand('b', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_border()
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_border('', 'b', 'h')
			)
		    )
		))
	    )),
		// Width
		self::get_expand('w', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_width('', 'w')
					)
				),
				'h' => array(
					'options' => array(
						self::get_width('', 'w', 'h')
					)
				)
			))
		)),
		// Height & Min Height
		self::get_expand('ht', array(
				self::get_height(),
				self::get_min_height(),
				self::get_max_height()
			)
		),
		// Rounded Corners
		self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius()
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('', 'r_c', 'h')
						)
					)
	    ))
			)
		),
		// Shadow
		self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow()
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('', 'sh', 'h')
						)
					)
				))
			)
		),
		// Display
		self::get_expand('disp', self::get_display())
	);

	$image_title = array(
	    
	     // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family('.module .image-pro-title', 'f_f_i_t'),
			    self::get_color('.module .image-pro-title', 'f_c_i_t'),
			    self::get_font_size('.module .image-pro-title', 'f_s_i_t'),
			    self::get_line_height('.module .image-pro-title', 'l_h_i_t'),
			    self::get_letter_spacing('.module .image-pro-title', 'l_s_i_t'),
			    self::get_text_align('.module .image-pro-title', 't_a_i_t'),
			    self::get_text_transform('.module .image-pro-title', 't_t_i_t'),
			    self::get_font_style('.module .image-pro-title', 'f_sy_i_t', 'f_t_b'),
				self::get_text_shadow('.module .image-pro-title', 't_sh_t'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family('.module .image-pro-title', 'f_f_i_t','h'),
			    self::get_color('.module .image-pro-title', 'f_c_i_t',null,null,'h'),
			    self::get_font_size('.module .image-pro-title', 'f_s_i_t','','h'),
			    self::get_line_height('.module .image-pro-title', 'l_h_i_t','h'),
			    self::get_letter_spacing('.module .image-pro-title', 'l_s_i_t','h'),
			    self::get_text_align('.module .image-pro-title', 't_a_i_t','h'),
			    self::get_text_transform('.module .image-pro-title', 't_t_i_t','h'),
			    self::get_font_style('.module .image-pro-title', 'f_sy_i_t', 'f_t_b','h'),
				self::get_text_shadow('.module .image-pro-title', 't_sh_t','h'),
			)
		    )
		))
	    )),
	     // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding('.module .image-pro-title', 'i_t_p')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding('.module .image-pro-title', 'i_t_p', 'h')
			)
		    )
		))
	    )),
            // Margin
	    self::get_expand('m', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			   self::get_margin('.module .image-pro-title', 'i_t_m'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_margin('.module .image-pro-title', 'i_t_m', 'h'),
			)
		    )
		))
	    )),
            // Border
	    self::get_expand('b', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_border('.module .image-pro-title', 'i_t_b')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_border('.module .image-pro-title', 'i_t_b','h')
			)
		    )
		))
	    ))
	);

	$image_caption = array(
	     // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(' .image-pro-caption', 'f_f_i_c'),
			    self::get_color(' .image-pro-caption', 'f_c_i_c'),
			    self::get_font_size(' .image-pro-caption', 'f_s_i_c'),
			    self::get_line_height(' .image-pro-caption', 'l_h_i_c'),
			    self::get_letter_spacing(' .image-pro-caption', 'l_s_i_c'),
			    self::get_text_align(' .image-pro-caption', 't_a_i_c'),
			    self::get_text_transform(' .image-pro-caption', 't_t_i_c'),
			    self::get_font_style(' .image-pro-caption', 'f_sy_i_c', 'f_c_b'),
				self::get_text_shadow(' .image-pro-caption', 't_sh_c'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family(' .image-pro-caption', 'f_f_i_c','h'),
			    self::get_color(' .image-pro-caption', 'f_c_i_c',null,null,'h'),
			    self::get_font_size(' .image-pro-caption', 'f_s_i_c','','h'),
			    self::get_line_height(' .image-pro-caption', 'l_h_i_c','h'),
			    self::get_letter_spacing(' .image-pro-caption', 'l_s_i_c','h'),
			    self::get_text_align(' .image-pro-caption', 't_a_i_c','h'),
			    self::get_text_transform(' .image-pro-caption', 't_t_i_c','h'),
			    self::get_font_style(' .image-pro-caption', 'f_sy_i_c', 'f_c_b','h'),
				self::get_text_shadow(' .image-pro-caption', 't_sh_c','h'),
			    
			)
		    )
		))
	    )),
	     // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding(' .image-pro-caption', 'i_c_p')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding(' .image-pro-caption', 'i_c_p', 'h')
			)
		    )
		))
	    )),
            // Margin
	    self::get_expand('m', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			   self::get_margin(' .image-pro-caption', 'i_c_m')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_margin(' .image-pro-caption', 'i_c_m', 'h')
			)
		    )
		))
	    )),
            // Border
	    self::get_expand('b', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			   self::get_border(' .image-pro-caption', 'i_c_b')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_border(' .image-pro-caption', 'i_c_b','h')
			)
		    )
		))
	    ))
	);

	$action_button = array(
		 //bacground
	    self::get_expand('bg', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_color('.module-pro-image .image-pro-wrap .image-pro-action-button', 'ac_background_color', 'bg_c', 'background-color')
			)
		    ),
		    'h' => array(
			'options' => array(
			   self::get_color('.module-pro-image .image-pro-wrap .image-pro-action-button:hover', 'ac_bg_c', 'bg_c', 'background-color', 'h')
			)
		    )
		))
	    )),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(' .image-pro-action-button', 'f_f_a_b'),
			    self::get_color(' .image-pro-action-button', 'f_c_a_b'),
			    self::get_font_size(' .image-pro-action-button', 'f_s_a_b'),
			    self::get_line_height(' .image-pro-action-button', 'l_h_a_b'),
			    self::get_letter_spacing(' .image-pro-action-button', 'l_s_a_b'),
			    self::get_text_align(' .image-pro-action-button', 't_a_a_b'),
			    self::get_text_transform(' .image-pro-action-button', 't_t_a_b'),
			    self::get_font_style(' .image-pro-action-button', 'f_sy_a_b', 'f_b_b'),
				self::get_text_shadow(' .image-pro-action-button', 't_sh_b'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family(' .image-pro-action-button', 'f_f_a_b','h'),
			    self::get_color(' .image-pro-action-button', 'f_c_a_b',null,null,'h'),
			    self::get_font_size(' .image-pro-action-button', 'f_s_a_b','','h'),
			    self::get_line_height(' .image-pro-action-button', 'l_h_a_b','h'),
			    self::get_letter_spacing(' .image-pro-action-button', 'l_s_a_b','h'),
			    self::get_text_align(' .image-pro-action-button', 't_a_a_b','h'),
			    self::get_text_transform(' .image-pro-action-button', 't_t_a_b','h'),
			    self::get_font_style(' .image-pro-action-button', 'f_sy_a_b', 'f_b_b','h'),
				self::get_text_shadow(' .image-pro-action-button', 't_sh_b','h'),
			    
			)
		    )
		))
	    )),
	     // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding(' .image-pro-action-button', 'a_b_p')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding(' .image-pro-action-button', 'a_b_p','h')
			)
		    )
		))
	    )),
            // Margin
	    self::get_expand('m', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_margin(' .image-pro-action-button', 'a_b_m')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_margin(' .image-pro-action-button', 'a_b_m','h')
			)
		    )
		))
	    )),
            // Border
	    self::get_expand('b', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			   self::get_border(' .image-pro-action-button', 'a_b_b')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_border(' .image-pro-action-button', 'a_b_b','h')
			)
		    )
		))
	    )),
		// Rounded Corners
		self::get_expand('r_c', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_border_radius(' .image-pro-action-button', 'a_b_r_c')
					)
				),
				'h' => array(
					'options' => array(
						self::get_border_radius(' .image-pro-action-button', 'a_b_r_c', 'h')
					)
				)
			))
		)),
		// Shadow
		self::get_expand('sh', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_box_shadow(' .image-pro-action-button', 'a_b_b_sh')
					)
				),
				'h' => array(
					'options' => array(
						self::get_box_shadow(' .image-pro-action-button', 'a_b_b_sh', 'h')
					)
				)
			))
		))
	);

	return array(
	    'type' => 'tabs',
	    'options' => array(
		'g' => array(
		    'options' => $general
		),
		'm_t' => array(
		    'options' => $this->module_title_custom_style()
		),
		'i_m' => array(
		    'label' => __('Image Title', 'themify'),
		    'options' => $image_title
		),
		'i_c' => array(
		    'label' => __('Image Caption', 'themify'),
		    'options' => $image_caption
		),
		'a_b' => array(
		    'label' => __('Action Button', 'themify'),
		    'options' => $action_button
		)
	    )
	);
    }

    protected function _visual_template() {
	$module_args = self::get_module_args();
	?>
	<#
	var moduleSettings = '',
	out = {'none' : '',
	'partial-overlay' : '',
	'flip-horizontal' : '',
	'flip-vertical' : '',
	'fadeInUp' : 'fadeOutDown',
	'fadeIn' : 'fadeOut',
	'fadeInLeft' : 'fadeOutLeft',
	'fadeInRight' : 'fadeOutRight',
	'fadeInDown' : 'fadeOutUp',
	'zoomInUp' : 'zoomOutDown',
	'zoomInLeft' : 'zoomOutLeft',
	'zoomInRight' : 'zoomOutRight',
	'zoomInDown' : 'zoomOutUp'
	},
	alignment=data.image_alignment?data.image_alignment:'center';
	if(alignment=='left' || alignment=='right' || alignment=='center'){
	    moduleSettings= 'tf_text'+alignment[0];
	}
	moduleSettings += data.image_filter ? ' filter-' + data.image_filter : '';
	moduleSettings += data.image_effect ? ' effect-' + data.image_effect : '';
	moduleSettings += data.appearance_image ? ' ' + data.appearance_image : '';			
	moduleSettings += data.appearance_image2 ? ' ' + data.appearance_image2.split('|').join(' ') : '';
	moduleSettings += data.style_image ? ' ' + data.style_image : '';
	moduleSettings += data.css_image ? ' ' + data.css_image : '';
	moduleSettings += data.animation_effect ? ' ' + data.animation_effect : '';
	moduleSettings += data.overlay_effect ? ' entrance-effect-' + data.overlay_effect : '';
	#>

	<div class="module module-<?php echo $this->slug; ?> {{ moduleSettings }}" data-entrance-effect="{{ data.overlay_effect }}" data-exit-effect="{{out[data.overlay_effect]}}">
	    <# if( data.mod_title_image ) { #>
	<?php echo $module_args['before_title']; ?>
	    {{{ data.mod_title_image }}}
	<?php echo $module_args['after_title']; ?>
	    <# } #>

	    <div class="image-pro-wrap tf_rel tf_overflow tf_inline_b">
		<# if( data.link_image || data.link_image_type == 'image_modal' ) { #>
		<a class="tf_abs image-pro-external" href="#"></a>
		<# } #>
		<div class="image-pro-flip-box-wrap tf_rel tf_w tf_overflow">
		    <div class="image-pro-flip-box tf_rel">

			<# if( data.url_image ) {
			var style='';
			style = 'width:' + ( data.width_image ? data.width_image + 'px;' : 'auto;' );
			style += 'height:' + ( data.height_image ? data.height_image + 'px;' : 'auto;' );
	                #>
			<img src="{{ data.url_image }}" width="{{ data.width_image }}" height="{{ data.height_image }} " style="{{ style }}">
			<# } #>

			<div class="image-pro-overlay<# 'none' == data.overlay_effect && print( ' none' ) #> tf_abs tf_hidden" <# data.overlay_image && print( 'style="background:url(' + data.overlay_image + ')"' ) #>>

			     <# if( data.overlay_color ) { #>
			     <div class="image-pro-color-overlay tf_abs" style="background-color:<# print( tb_app.Utils.toRGBA( data.overlay_color ) ) #>"></div>
			    <# } #>

			    <div class="image-pro-overlay-inner">

				<# if( data.title_image ) { #>
				<h4 class="image-pro-entity image-pro-title">{{{ data.title_image }}}</h4>
				<# }
				if( data.caption_image ) { #>
				<div class="image-pro-entity image-pro-caption">{{{ data.caption_image }}}</div>
				<# }
				if( data.action_button ) { #>
				<a class="ui image-pro-entity builder_button image-pro-action-button {{ data.color_button }}" href="#">
				    {{{ data.action_button }}}
				</a>
				<# } #>
			    </div>
			</div><!-- .image-pro-overlay -->

		    </div>
		</div>

	    </div><!-- .image-pro-wrap -->
	</div>
	<?php
    }

	/**
	 * Returns a unique ID to be used for the modal lightbox
	 * Same module may be repeated on a page so $module_id alone is not reliable.
	 *
	 * @return string
	 */
	public static function modal_id( $module_id ) {
		static $id = 1;
		return 'modal-' . $module_id . '-' . $id++;
	}
}

Themify_Builder_Model::register_module('TB_Image_Pro_Module');
