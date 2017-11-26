<?php

add_action( 'widgets_init', create_function( '', 'return register_widget( "GA_POP_Widget" );' ) );
class GA_POP_Widget extends WP_Widget {

	function __construct() {
		parent::__construct( false, $name = 'Google Analytics POP Posts' );
	}

	function widget( $args, $instance ) {

		extract( $args );

		$widget_title = $instance[ 'widget_title' ];

		echo $before_widget;

		if( $widget_title != '' ) {
			echo $before_title . '<span>' . $widget_title . '</span>' . $after_title;
		}

		?>

		<?php get_pop_posts([$instance[ 'show_list' ]]); ?>

		<?php
		echo $after_widget;

	}

	// 設定を保存するメソッド
	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	// 設定フォームを出力するメソッド
	function form( $instance ) {

		$defaults = array( 'widget_title' => 'Popular Posts');

		$instance = wp_parse_args( (array) $instance, $defaults );

		$widget_title = esc_attr( $instance[ 'widget_title' ] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>">
				<strong>Title（If you don't show anything, empty the textbox）</strong>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" value="<?php echo $widget_title; ?>" />
		</p>

		<?php
	}

}
