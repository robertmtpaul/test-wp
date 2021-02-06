<?php
/**
 * Template Products
 * 
 * Access original fields: $args['mod_settings']
 */
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

if (themify_is_woocommerce_active()):
	global $paged, $post;
	$fields_default = array(
		'mod_title_products' => '',
		'query_products' => 'all',
		'template_products' => 'list',
		'hide_free_products' => 'no',
		'hide_outofstock_products' => 'no',
		'layout_products' => 'grid3',
		'query_type' => 'category',
		'category_products' => '',
		'tag_products' => '',
		'hide_child_products' => false,
		'post_per_page_products' => 6,
		'offset_products' => 0,
		'order_products' => 'ASC',
		'orderby_products' => 'title',
		'description_products' => 'none',
		'hide_feat_img_products' => 'no',
		'hover_image' => false,
		'image_size_products' => '',
		'img_width_products' => '',
		'img_height_products' => '',
		'unlink_feat_img_products' => 'no',
		'hide_post_title_products' => 'no',
		'show_product_categories' => 'no',
		'show_product_tags' => 'no',
		'unlink_post_title_products' => 'no',
		'hide_price_products' => 'no',
		'hide_add_to_cart_products' => 'no',
		'hide_rating_products' => 'no',
		'show_empty_rating' => 'no',
		'hide_sales_badge' => 'no',
		// slider settings
		'layout_slider' => '',
		'visible_opt_slider' => '',
		'mob_visible_opt_slider' => '',
		'auto_scroll_opt_slider' => 0,
		'scroll_opt_slider' => '',
		'speed_opt_slider' => '',
		'effect_slider' => 'scroll',
		'pause_on_hover_slider' => 'resume',
		'play_pause_control' => 'no',
		'pagination' => 'yes',
		'wrap_slider' => 'yes',
		'show_nav_slider' => 'yes',
		'show_arrow_slider' => 'yes',
		'show_arrow_buttons_vertical' => '',
		'left_margin_slider' => '',
		'right_margin_slider' => '',
		'height_slider' => 'variable',
		'hide_page_nav_products' => 'yes',
		'animation_effect' => '',
		'css_products' => '',
	);
	$fields_args = wp_parse_args($args['mod_settings'], $fields_default);
	$terms_id = $fields_args['query_type'] . '_products';
	if (isset($args['mod_settings'][$terms_id])) {
		$fields_args[$terms_id] = self::get_param_value($args['mod_settings'][$terms_id]);
	}
	unset($args['mod_settings']);
	$fields_default = null;
	$temp_terms = explode(',', $fields_args[$terms_id]);
	$terms = array();
	$terms_exclude = array();
	$is_string = false;
	foreach ($temp_terms as $t) {
		$t = trim($t);
		$is_string = !is_numeric($t);
		if ('' !== $t) {
			if ($is_string === false && $t < 0) {
				$terms_exclude[] = (-1) * $t;
			} else {
				$terms[] = $t;
			}
		}
	}
	$tax_field = $is_string ? 'slug' : 'id';

	$query_args = array(
		'post_type' => 'product',
		'posts_per_page' => $fields_args['post_per_page_products'],
		'order' => $fields_args['order_products'],
		'tf_wc_query' => 1, // flag, used by Themify Product Filter plugin
	);
	$paged = self::get_paged_query();
	$query_args['offset'] = ( ( $paged - 1 ) * $fields_args['post_per_page_products'] ) + $fields_args['offset_products'];

	$query_args['meta_query'][] = WC()->query->stock_status_meta_query();
	$query_args['meta_query'] = array_filter($query_args['meta_query']);
	$taxonomy = 'tag' === $fields_args['query_type'] ? 'product_tag' : 'product_cat';
	if (!empty($terms_exclude)) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field' => $tax_field,
				'terms' => $terms_exclude,
				'include_children' => $fields_args['hide_child_products'] !== 'yes',
				'operator' => 'NOT IN'
			)
		);
	} elseif (!empty($terms) && !in_array('0', $terms)) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field' => $tax_field,
				'terms' => $terms,
				'include_children' => $fields_args['hide_child_products'] !== 'yes'
			)
		);
	}

	if ($fields_args['query_products'] === 'onsale') {
		$product_ids_on_sale = wc_get_product_ids_on_sale();
		$product_ids_on_sale[] = 0;
		$query_args['post__in'] = $product_ids_on_sale;
	} elseif ($fields_args['query_products'] === 'featured') {
		$query_args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field' => 'name',
			'terms' => 'featured',
			'operator' => 'IN'
		);
	} elseif ($fields_args['query_products'] === 'toprated') {
		$query_args['orderby']['top_rated'] = 'DESC';
		$query_args['meta_query']['top_rated'] = array(
			'key' => '_wc_average_rating',
			'type' => 'NUMERIC'
		);
	}

	switch ($fields_args['orderby_products']) {
		case 'price' :
			$query_args['meta_query'][$fields_args['orderby_products']] = array(
				'key' => '_price',
				'type' => 'NUMERIC'
			);
			break;
		case 'sales' :
			$query_args['meta_query'][$fields_args['orderby_products']] = array(
				'key' => 'total_sales',
				'type' => 'NUMERIC'
			);
			break;
		case 'sku' :
			$query_args['meta_query'][$fields_args['orderby_products']] = array(
				'key' => '_sku'
			);
			break;
	}
	$query_args['orderby'][$fields_args['orderby_products']] = $fields_args['order_products'];
	if ($fields_args['hide_free_products'] === 'yes') {
		$query_args['meta_query'][] = array(
			'key' => '_price',
			'value' => 0,
			'compare' => '>',
			'type' => 'DECIMAL'
		);
	}
	if ($fields_args['hide_outofstock_products'] === 'yes') {
		$query_args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field' => 'name',
			'terms' => array('exclude-from-catalog', 'outofstock'),
			'operator' => 'NOT IN'
		);
	}
	$container_class = apply_filters('themify_builder_module_classes', array(
		'module', 'woocommerce', 'module-' . $args['mod_name'], $args['module_ID'], $fields_args['css_products']
			), $args['mod_name'], $args['module_ID'], $fields_args);

	if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
		$container_class[] = $fields_args['global_styles'];
	}
	$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
				'id' => $args['module_ID'],
				'class' => implode(' ', $container_class),
			)), $fields_args, $args['mod_name'], $args['module_ID']);

	if (Themify_Builder::$frontedit_active === false) {
		$container_props['data-lazy'] = 1;
	}
	?>

	<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
		<?php $container_props = $container_class = null; ?>

		<?php if ($fields_args['mod_title_products'] !== ''): ?>
			<?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title_products'], $fields_args) . $fields_args['after_title']; ?>
		<?php endif; ?>

		<?php
		do_action('themify_builder_before_template_content_render');
		$query = new WP_Query($query_args);
		unset($query_args);
		if ($query->have_posts()) {

			global $ThemifyBuilder;

			$titleClasses = esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title'));

			$isLoop = $ThemifyBuilder->in_the_loop === true;
			$ThemifyBuilder->in_the_loop = true;
			$isSlider = $fields_args['template_products'] === 'slider';
			$layout = $fields_args['layout_products'];
			$class = array('loops-wrapper', 'products', 'wc-products');
			if ($isSlider === true) {
				$margin='';
				if($fields_args['left_margin_slider']!==''){
					$margin='margin-left:'.$fields_args['left_margin_slider'].'px;';
				}
				if($fields_args['right_margin_slider']!==''){
					$margin.='margin-right:'.$fields_args['right_margin_slider'].'px';
				}
				$container_inner = array(
					'data-visible' => $fields_args['visible_opt_slider'],
					'data-tbreakpoints' => themify_get_breakpoints('tablet_landscape')[1],
					'data-mob-visible' => $fields_args['mob_visible_opt_slider'],
					'data-mbreakpoints' => themify_get_breakpoints('mobile'),
					'data-scroll' => $fields_args['scroll_opt_slider'],
					'data-speed' => $fields_args['speed_opt_slider'] === 'slow' ? 4 : ($fields_args['speed_opt_slider'] === 'fast' ? '.5' : 1),
					'data-wrapvar' => $fields_args['wrap_slider'] !== 'no' ? 1 : 0,
					'data-slider_nav' => $fields_args['show_arrow_slider'] === 'yes' ? 1 : 0,
					'data-pager' => $fields_args['show_nav_slider'] === 'yes' ? 1 : 0,
					'data-effect' => $fields_args['effect_slider'],
					'data-height' => $fields_args['height_slider'],
					'data-css_url' => Builder_Woocommerce::get_instance()->url . 'assets/modules/slider.css'
				);
				$layout = $fields_args['layout_slider'];
				$class[] = 'swiper-container tf_carousel tf_rel tf_overflow';
				if ($container_inner['data-slider_nav'] === 1 && $fields_args['show_arrow_buttons_vertical'] === 'vertical') {
					$container_inner['data-nav_out'] = 1;
					$class[] = ' themify_builder_slider_vertical';
				}
				if ($fields_args['auto_scroll_opt_slider'] && $fields_args['auto_scroll_opt_slider'] !== 'off') {
					$container_inner['data-auto'] = $fields_args['auto_scroll_opt_slider']*1000;
					$container_inner['data-pause_hover'] = $fields_args['pause_on_hover_slider'] === 'resume' ? 1 : 0;
					$container_inner['data-controller'] = $fields_args['play_pause_control'] === 'yes' ? 1 : 0;
				}
				$itemsClass = 'tf_lazy swiper-slide slide-wrap';
				$productWrapTag = 'div';
			} else {
				$container_inner = array();
				$itemsClass = '';
				$productWrapTag = 'ul';
			}
			$class[] = apply_filters('themify_builder_module_loops_wrapper', $layout, $fields_args, 'products'); //deprecated backward compatibility
			$container_inner['class'] = apply_filters( 'themify_loops_wrapper_class', $class,'product',$layout,'builder',$fields_args, $args['mod_name'] );
			$container_inner['class'][] = 'clearfix';
			$class=null;
			$isThemifyTheme = themify_is_themify_theme();
			if (Themify_Builder::$frontedit_active === false) {
				$container_inner['data-lazy'] = 1;
			}
			global $themify;
			if (isset($themify)) {
				$themify_save = clone $themify;
				$themify->hide_title = $fields_args['hide_post_title_products'];
				$themify->unlink_title = $fields_args['unlink_post_title_products'];
				$themify->hide_image = $fields_args['hide_feat_img_products'];
				$themify->unlink_image = $fields_args['unlink_feat_img_products'];
				$themify->display_content = $fields_args['description_products'] === 'short' ? 'excerpt' : $fields_args['description_products'];
				$themify->hide_meta_tag = $fields_args['show_product_tags'] === 'yes' ? 'no' : 'yes';
				$themify->hide_meta_category = $fields_args['show_product_categories'] === 'yes' ? 'no' : 'yes';
				$themify->post_layout = $layout;
				$themify->width = $fields_args['img_width_products'];
				$themify->height = $fields_args['img_height_products'];
				$themify->image_size = $fields_args['image_size_products'];
				if (isset($themify->products_hover_image) && $fields_args['hover_image'] === 'yes') {
					$themify->products_hover_image = 'first_image';
				}
			}
			TB_Products_Module::set_filters($fields_args);
			$container_inner = apply_filters('themify_builder_blog_container_props', $container_inner, 'product', $layout, $fields_args, $args['mod_name']);
			$container_inner['class'] = implode( ' ', $container_inner['class'] );
			?>
			<<?php echo $productWrapTag, ' ', self::get_element_attributes($container_inner) ?>>
			<?php if ($isSlider === true): ?>
				<ul class="swiper-wrapper tf_lazy tf_rel tf_w tf_h">
				<?php endif; ?>
				<?php
				$container_inner = null;

				global $product;
				if ( is_object( $post ) )
					$saved_post = clone $post;

				while ($query->have_posts()) {
					$query->the_post();
					$product=wc_get_product(get_the_ID());
					do_action('woocommerce_shop_loop');
					if (!empty($product) && $product->is_visible()) {
						$class = wc_get_product_class($itemsClass, $product);
						$index = array_search('first', $class, true);
						if ($index !== false) {
							unset($class[$index]);
						} else {
							$index = array_search('last', $class, true);
							if ($index !== false) {
								unset($class[$index]);
							}
						}
						?>
						<li class="post <?php echo implode(' ', $class) ?>">
							<?php if ($isSlider === true): ?>
								<div class="slide-inner-wrap"<?php if ($margin!== ''): ?> style="<?php echo $margin; ?>"<?php endif; ?>>
							<?php endif;?>
								<?php do_action('woocommerce_before_shop_loop_item'); ?>

								<?php if ($fields_args['hide_feat_img_products'] !== 'yes'): ?>
									<?php woocommerce_template_loop_product_thumbnail(); ?>
								<?php endif; ?>

								<div class="post-content">
									<?php do_action('woocommerce_before_shop_loop_item_title'); ?>
									<?php if ($fields_args['show_product_categories'] === 'yes'): ?>
										<?php echo wc_get_product_category_list($product->get_id(), ',', '<div class="product-category-link">' . __('Category', 'themify') . ': ', '</div>'); ?>
									<?php endif; ?>

									<?php if ($fields_args['show_product_tags'] === 'yes'): ?>
										<?php echo wc_get_product_tag_list($product->get_id(), ',', '<div class="product-tag-link">' . __('Tag', 'themify') . ': ', '</div>'); ?>
									<?php endif; ?>
									<?php if ($fields_args['hide_post_title_products'] !== 'yes') : ?>
										<h3 class="<?php echo $titleClasses ?>">
											<?php if ($fields_args['unlink_post_title_products'] !== 'yes') : ?>
												<a href="<?php echo the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
												<?php endif; //unlink product title ?>

												<?php the_title(); ?>

												<?php if ($fields_args['unlink_post_title_products'] !== 'yes') : ?>
												</a>
											<?php endif; //unlink product title  ?>
										</h3>
									<?php endif; //product title  ?>  

									<?php
									do_action('woocommerce_after_shop_loop_item_title');

									// product rating
									if ($fields_args['hide_rating_products'] !== 'yes') {

										woocommerce_template_loop_rating();
									}

									// product price
									if ($fields_args['hide_price_products'] !== 'yes') {
										woocommerce_template_loop_price();
									}

									// product description
									if ($fields_args['description_products'] === 'short') {
										the_excerpt();
									} elseif ($fields_args['description_products'] !== 'none') {
										the_content();
									}

									// product add to cart
									if ($fields_args['hide_add_to_cart_products'] !== 'yes') {
										echo '<p class="add-to-cart-button">';
										woocommerce_template_loop_add_to_cart();
										echo '</p>';
									}

									do_action('woocommerce_after_shop_loop_item');
									if ($isThemifyTheme === false) {
										themify_edit_link();
									}
									?>
								</div>
						<?php if ($isSlider === true): ?>
							</div>
						<?php endif;?>
						</li>
						<?php
					}
				}
				?>
				<?php if ($isSlider === true): ?>
				</ul>
			<?php endif; ?>
			</<?php echo $productWrapTag ?>>
			<?php
			if ( isset( $saved_post ) && is_object( $saved_post ) ) {
				$post = $saved_post;
				/**
				 * WooCommerce plugin resets the global $product on the_post hook,
				 * call setup_postdata on the original $post object to prevent fatal error from WC
				 */
				setup_postdata( $saved_post );
			}

			if ($isSlider === false && 'yes' !== $fields_args['hide_page_nav_products']) {
				echo self::get_pagenav('', '', $query, $fields_args['offset_products']);
			}
			$ThemifyBuilder->in_the_loop = $isLoop;
			TB_Products_Module::revert_filters();
			if (isset($themify)) {
				$themify = clone $themify_save;
				unset($themify_save);
			}
		}

		do_action('themify_builder_after_template_content_render');
		?>
	</div>
	<?php

	
endif;
