<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Image Pro
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
$fields_default = array(
    'mod_title_image' => '',
    'style_image' => '',
    'url_image' => '',
    'appearance_image' => '',
    'appearance_image2' => '',
    'image_size_image' => '',
    'width_image' => '',
    'height_image' => '',
    'title_image' => '',
    'param_image' => array(),
    'caption_image' => '',
    'css_image' => '',
    'image_effect' => '',
    'image_filter' => '',
    'image_alignment' => 'center',
    'overlay_color' => '',
    'overlay_image' => '',
    'overlay_effect' => 'fadeIn',
    'action_button' => '',
    'color_button' => '',
    'link_type' => 'external',
    'link_new_window' => 'no',
    'link_address' => '',
    'content_modal' => '',
    'lightbox_width' => '',
    'lightbox_height' => '',
    'lightbox_size_unit_width' => 'pixels',
    'lightbox_size_unit_height' => 'pixels',
    'link_image_type' => 'image_external',
    'link_image' => '',
    'link_image_new_window' => 'no',
    'image_content_modal' => '',
    'animation_effect' => ''
);

if (isset($args['mod_settings']['appearance_image'])) {
    $args['mod_settings']['appearance_image'] = self::get_checkbox_data($args['mod_settings']['appearance_image']);
}
if (isset($args['mod_settings']['appearance_image2'])) {
    $args['mod_settings']['appearance_image2'] = self::get_checkbox_data($args['mod_settings']['appearance_image2']);
}
$fields_args = wp_parse_args($args['mod_settings'], $fields_default);
$element_id =  $args['module_ID'];
unset($args['mod_settings']);
$fields_default=null;
$animation_args = array();
$animation_args[] = isset( $fields_args['custom_parallax_scroll_reverse'] ) && $fields_args['custom_parallax_scroll_reverse'] === 'reverse' ? 'data-parallax-element-reverse="1"' : '';
$animation_args[] = isset( $fields_args['custom_parallax_scroll_speed'] ) ? 'data-parallax-element-speed="' . $fields_args['custom_parallax_scroll_speed'] . '"' : '';
$animation_args[] = isset( $fields_args['custom_parallax_scroll_fade'] ) ? 'data-parallax-fade="1"' : '';

$container_class = apply_filters('themify_builder_module_classes', array(
    'module', 'module-' . $args['mod_name'], $element_id, 'filter-' . $fields_args['image_filter'], 'effect-' . $fields_args['image_effect'], $fields_args['appearance_image'], $fields_args['appearance_image2'], 'tf_text' . $fields_args['image_alignment'][0], $fields_args['style_image'], $fields_args['css_image'], 'entrance-effect-' . $fields_args['overlay_effect']
), $args['mod_name'], $element_id, $fields_args);

if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
    $container_class[] = $fields_args['global_styles'];
}
$title_image = $fields_args['title_image'];
$image_alt = '' !== $title_image ? $title_image : wp_strip_all_tags($fields_args['caption_image']);
$image_size =Themify_Builder_Model::is_img_php_disabled()?($fields_args['image_size_image'] !== '' ? $fields_args['image_size_image'] : themify_builder_get('setting-global_feature_size', 'image_global_size_field')):'';

$lightbox_size_unit_width = $fields_args['lightbox_size_unit_width'] === 'pixels' ? 'px' : '%';
$lightbox_size_unit_height = $fields_args['lightbox_size_unit_height'] === 'pixels' ? 'px' : '%';

$lightbox_data = $fields_args['link_image_type'] !== 'image_external' && (!empty($fields_args['lightbox_width']) || !empty($fields_args['lightbox_height']) ) ? sprintf(' data-zoom-config="%s|%s"'
		, $fields_args['lightbox_width'] . $lightbox_size_unit_width, $fields_args['lightbox_height'] . $lightbox_size_unit_height) : false;


$out_effect = array(
    'none' => '',
    'partial-overlay' => '',
    'flip-horizontal' => '',
    'flip-vertical' => '',
    'fadeInUp' => 'fadeOutDown',
    'fadeIn' => 'fadeOut',
    'fadeInLeft' => 'fadeOutLeft',
    'fadeInRight' => 'fadeOutRight',
    'fadeInDown' => 'fadeOutUp',
    'zoomInUp' => 'zoomOutDown',
    'zoomInLeft' => 'zoomOutLeft',
    'zoomInRight' => 'zoomOutRight',
    'zoomInDown' => 'zoomOutUp',
);
if(method_exists('Themify_Builder_Model','load_color_css')){
	    $instance = Builder_Image_Pro::get_instance();
	    $url=$instance->url . 'assets/modules/';
		$v=$instance->version;
		$k='tb_'.$args['mod_name'];
	    if($fields_args['appearance_image']!==''){
		    Themify_Builder_Model::loadCssModules($k.'_fullwidth_image',$url.'appearance/fullwidth_image.css',$v);
	    }
	    if($fields_args['appearance_image2']!==''){
		    if(strpos('rounded',$fields_args['appearance_image2'])!==false){
			    Themify_Builder_Model::loadCssModules($k.'_rounded',$url.'appearance/rounded.css',$v);
		    }
		    if(strpos('circle',$fields_args['appearance_image2'])!==false){
			    Themify_Builder_Model::loadCssModules($k.'_circle',$url.'appearance/circle.css',$v);
		    }
	    }
	    if ($fields_args['color_button'] !== '' && $fields_args['action_button'] !== '' && $fields_args['color_button']!=='outline') {
		    Themify_Builder_Model::load_color_css($fields_args['color_button']);
	    }
}
    $container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
	    'class' => implode(' ', $container_class),
    )), $fields_args, $args['mod_name'], $args['module_ID']);
    if(Themify_Builder::$frontedit_active===false){
	    $container_props['data-lazy']=1;
}
?>
<!-- module image pro -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props,$fields_args)); ?> data-entrance-effect="<?php echo $fields_args['overlay_effect']; ?>" data-exit-effect="<?php echo $out_effect[$fields_args['overlay_effect']]; ?>" <?php echo implode( ' ', $animation_args ); ?>>
    <?php $container_props=$container_class=null;?>
    <?php if ($fields_args['mod_title_image'] !== ''): ?>
	<?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title_image'], $fields_args) . $fields_args['after_title']; ?>
    <?php endif; ?>

    <?php do_action('themify_builder_before_template_content_render'); ?>

    <div class="image-pro-wrap tf_rel tf_overflow tf_inline_b">
	<?php if (!empty($fields_args['link_image']) || 'image_modal' === $fields_args['link_image_type']):
		$image_modal_id = TB_Image_Pro_Module::modal_id( $args['module_ID'] );
	?>
	    <a class="image-pro-external<?php if ($fields_args['link_image_type'] !== 'image_external'): ?> themify_lightbox<?php endif; ?> tf_abs" href="<?php echo 'image_modal' !== $fields_args['link_image_type'] ? esc_url($fields_args['link_image']) : '#' . $image_modal_id ?>" <?php if ($fields_args['link_image_new_window'] === 'yes') : ?>target="_blank"<?php
	    endif;
	    echo $lightbox_data;
	    ?>></a>
	   <?php endif; ?>
	<div class="image-pro-flip-box-wrap tf_rel tf_w tf_overflow">
	    <div class="image-pro-flip-box tf_rel">

		<?php echo themify_get_image(array(
				'src'=>esc_url($fields_args['url_image']),
				'w'=>$fields_args['width_image'],
				'h'=>$fields_args['height_image'],
				'image_size'=>$image_size,
				'alt'=>$image_alt,
				'title'=>$title_image
			)); ?>

		<div class="image-pro-overlay<?php echo ( 'none' === $fields_args['overlay_effect'] ) ? ' none' : ''; ?> tf_abs tf_hidden">

		    <?php if ($fields_args['overlay_color'] !== '') : ?>
			<div class="image-pro-color-overlay tf_abs" style="background-color:<?php echo Themify_Builder_Stylesheet::get_rgba_color($fields_args['overlay_color']); ?>"></div>
		    <?php endif; ?>

		    <div class="image-pro-overlay-inner">

			<?php if ($title_image !== '') : ?>
			    <h4 class="image-pro-entity image-pro-title"><?php echo $title_image; ?></h4>
			<?php endif; ?>

			<?php if ($fields_args['caption_image'] !== '') : ?>
			    <div class="image-pro-entity image-pro-caption"><?php echo do_shortcode($fields_args['caption_image']); ?></div>
			<?php endif; ?>

			<?php if ($fields_args['action_button'] !== '') : ?>
			    <a class="ui image-pro-entity builder_button image-pro-action-button <?php echo $fields_args['color_button']; ?> <?php if ($fields_args['link_type'] === 'lightbox_link' || $fields_args['link_type'] === 'modal') echo 'themify_lightbox' ?>" href="<?php
			    if ($fields_args['link_type'] === 'modal') {
					$modal_id = TB_Image_Pro_Module::modal_id( $args['module_ID'] );
					echo '#' . $modal_id;
			    } else {
				echo $fields_args['link_address'];
			    }
			    ?>" <?php if ($fields_args['link_new_window'] === 'yes') : ?>target="_blank"<?php
			       endif;
			       echo $lightbox_data;
			       ?>>
				   <?php echo $fields_args['action_button']; ?>
			    </a>
			<?php endif; ?>

		    </div>
		</div><!-- .image-pro-overlay -->

		<a class="image-pro-flip-button tf_hide"></a>

	    </div>
	</div>

    </div><!-- .image-pro-wrap -->

    <?php if ( 'modal' === $fields_args['link_type'] ) : ?>
		<div id="<?php echo $modal_id; ?>" class="tf_hide">
			<?php echo apply_filters('themify_builder_module_content', $fields_args['content_modal']); ?>
		</div>
    <?php endif; ?>

    <?php if ( 'image_modal' === $fields_args['link_image_type'] ) : ?>
		<div id="<?php echo $image_modal_id; ?>" class="tf_hide">
			<?php echo apply_filters('themify_builder_module_content', $fields_args['image_content_modal']); ?>
		</div>
    <?php endif; ?>

    <?php do_action('themify_builder_after_template_content_render'); ?>

    <?php if ( $fields_args['overlay_image'] !== '' ) : ?>
	<style>
	    .<?php echo $element_id ?> .image-pro-overlay {
			background-image:url('<?php echo $fields_args['overlay_image']?>')
		}
	</style>
    <?php endif;?>

</div>
<!-- /module image pro -->
