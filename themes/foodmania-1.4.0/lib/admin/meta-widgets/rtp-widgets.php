<?php
/**
 * Register Widget
 *
 * @package Foodmania
 */

/**
 * Recent_Posts widget class
 */
class RTP_Widget_Recent_Posts extends WP_Widget {

	/**
	 * Adding description of the widget.
	 * Adding the actions to perform.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'rtp_widget_recent_entries',
			'description' => __( 'foodmania site&#8217;s most recent Posts.', 'foodmania' ),
		);
		parent::__construct( 'rtp-recent-posts', __( 'foodmania Recent Posts', 'foodmania' ), $widget_ops );
		$this->alt_option_name = 'rtp_widget_recent_entries';

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	/**
	 * Display the widget during customizing.
	 *
	 * @param array $args The array with widget information.
	 * @param array $instance The array with title, posts number and show_date.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_recent_posts', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo esc_html( $cache[ $args['widget_id'] ] );
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'foodmania' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5; }
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query(
			apply_filters(
				'widget_posts_args',
				array(
					'posts_per_page'      => $number,
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
				)
			)
		);

		if ( $r->have_posts() ) {

			echo wp_kses_post( $args['before_widget'] );
			if ( $title ) {
				echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
			}
			?>
			<ul>
				<?php
				while ( $r->have_posts() ) {
					$r->the_post();
					?>
					<li>

						<?php
						if ( has_post_thumbnail() ) {
							?>
							<div class="rtp-post-thump">
								<a class="rtp-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
							</div>	
							<div class="rtp-post-desc left-float">
							<?php
						} else {
							?>
							<div class="rtp-post-desc">
							<?php
						}
						?>
								<h6 class="rtp-title"><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a></h6>					

						<?php
						if ( $show_date ) {
							?>
							<span class="post-date"><?php echo get_the_date(); ?></span>
							<?php
						}
						?>
							</div>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
			echo wp_kses_post( $args['after_widget'] );
			// Reset the global $the_post as this query will have stomped on it.
			wp_reset_postdata();

		}

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	/**
	 * When the Block is updated.
	 *
	 * @param array $new_instance The array with new title, new posts number and new show_date.
	 * @param array $old_instance The array with old title, old posts number and old show_date.
	 *
	 * @return array $instance The array with title, posts number and show_date.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = wp_strip_all_tags( $new_instance['title'] );
		$instance['number']    = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['rtp_widget_recent_entries'] ) ) {
			delete_option( 'rtp_widget_recent_entries' ); }

		return $instance;
	}

	/**
	 * Flush the case of the Widget.
	 *
	 * @return void
	 */
	public function flush_widget_cache() {
		wp_cache_delete( 'widget_recent_posts', 'widget' );
	}

	/**
	 * Display the block at the front end.
	 *
	 * @param array $instance The array with title, posts number and show_date.
	 * @return void
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'foodmania' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'foodmania' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'foodmania' ); ?></label></p>
		<?php
	}
}

/**
 * Registers all Custom Widgets
 */
function rtp_custom_register_widgets() {
	register_widget( 'RTP_Widget_Recent_Posts' );
}

add_action( 'widgets_init', 'rtp_custom_register_widgets' );
