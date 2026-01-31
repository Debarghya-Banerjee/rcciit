<?php
/**
 * WooCommerce Related Functions.
 *
 * @package Foodmania
 */

/**
 * Mini Cart In Header
 */
function rtp_shopping_minicart() {

	/* Check if WooCommerce is active */
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		?>
		<div class="rtp-minicart-wrapper hide-for-small">
			<?php
			rtp_cart_icon_header();
			rtp_cart_product_list();
			?>
		</div>
		<?php
	}
}

add_action( 'foodmania_nav_end', 'rtp_shopping_minicart', 11 );

/**
 * Handle cart in header fragment for ajax add to cart
 */
function woocommerce_output_related_products() {

	$args = array(
		'posts_per_page' => 12,
		'columns'        => 12,
		'orderby'        => 'rand', // phpcs:ignore WordPressVIPMinimum
	);

	woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
}

/**
 * Handle cart in header fragment for ajax add to cart.
 *
 * @param mixed $fragments Fragmenets.
 *
 * @return mixed
 */
function rtp_woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<div class="rtp-minicart-wrapper hide-for-small">
		<?php
		rtp_cart_icon_header();
		rtp_cart_product_list();
		?>
	</div>
	<?php
	$fragments['.rtp-minicart-wrapper'] = ob_get_clean();

	return $fragments;
}

add_filter( 'add_to_cart_fragments', 'rtp_woocommerce_header_add_to_cart_fragment' );

/**
 * Header Cart Icon HTML
 */
function rtp_cart_icon_header() {
	global $woocommerce;
	?>
	<a class="rtp-cart-contents-count" data-dropdown="rtp-minicart" aria-controls="rtp-minicart" aria-expanded="false">
		<span class="dashicons dashicons-cart"></span>
		<?php
		$cart_count = $woocommerce->cart->cart_contents_count;
		if ( 0 < $cart_count ) {
			?>
			<span class="rtp-count"><?php echo esc_html( $woocommerce->cart->cart_contents_count ); ?></span>
			<?php
		}
		?>
	</a>
	<?php
}

/**
 * Cart Item list
 */
function rtp_cart_product_list() {
	global $woocommerce;

	if ( count( $woocommerce->cart->cart_contents ) > 0 ) {
		?>
		<div id="rtp-minicart" class="f-dropdown content rtp-minicart-container clearfix small" data-dropdown-content aria-hidden="true">
			<ul class="rtp-cart-list">
				<?php rtp_cart_list_items(); ?>
			</ul>
			<div class="rtp-minicart-total-checkout">
				<span class="rtp-total"><?php esc_html_e( 'Total: ', 'foodmania' ); ?></span>
				<?php echo wp_kses_post( $woocommerce->cart->get_cart_total() ); ?>
			</div>
			<div class="rtp-button-wrap clearfix">
				<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="rtp-button small rtp-minicart-cart-button alignleft"><?php esc_html_e( 'View Cart', 'foodmania' ); ?></a>
				<a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url() ); ?>" class="rtp-button small rtp-minicart-checkout-button alignright"><?php esc_html_e( 'Checkout', 'foodmania' ); ?></a>
			</div>
		</div>
		<?php
	} else {
		echo '<div id="rtp-minicart" data-dropdown-content class="f-dropdown content rtp-minicart-container clearfix small" aria-hidden="true">' . esc_html__( 'No products in the shopping cart.', 'foodmania' ) . '</div>';
	}
}

/**
 * Cart List Items
 */
function rtp_cart_list_items() {
	global $woocommerce;

	foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {

		$_product = $cart_item['data'];

		if ( $_product->exists() && $cart_item['quantity'] > 0 ) {

			echo '<li class="rtp-cart-list-product">';
			printf(
				'<a class="rtp-cart-list-product-img" href="%s">%s</a>',
				esc_url( get_permalink( $cart_item['product_id'] ) ),
				wp_kses_post( $_product->get_image( array( 80, 80 ) ) )
			);

			echo '<div class="rtp-cart-list-product-title">';
			$rtp_product_title = $_product->get_title();

			printf(
				'<a href="%s">%s</a>',
				esc_url( get_permalink( $cart_item['product_id'] ) ),
				wp_kses_post( apply_filters( 'woocommerce_cart_widget_product_title', $rtp_product_title, $_product ) )
			);

			echo '</div>';
			printf(
				'<div class="rtp-cart-list-product-quantity">%s %s</div>',
				esc_html__( 'Quantity:', 'foodmania' ),
				wp_kses_post( $cart_item['quantity'] )
			);

			printf(
				'<div class="rtp-cart-list-product-price">%s</div>',
				wp_kses_post( woocommerce_price( $_product->get_price() ) )
			);

			echo wp_kses_post(
				apply_filters( 
					'woocommerce_cart_item_remove_link',
					sprintf(
						'<a href="%1$s" class="rtp-remove" title="%2$s">&times;</a>',
						esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ),
						esc_attr__( 'Remove this item', 'foodmania' ) 
					),
					$cart_item_key 
				) 
			);
			echo '</li>';
		}
	}
}
