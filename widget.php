<?php

// require_once __DIR__ . '/ga-pop-posts.php';

add_action( 'widgets_init', create_function( '', 'return register_widget( "WidgetItemClass" );' ) );
class WidgetItemClass extends WP_Widget {

	// コンストラクタ
	function WidgetItemClass() {
		parent::WP_Widget( false, $name = 'Google Analytics POP Posts' );
	}

	// 公開ページで出力するメソッド
	function widget( $args, $instance ) {

		// ウィジェットエリアで設定したデータの連想配列を展開
		// $before_widget ウィジェット前に出力すべきタグ
		// $after_widget ウィジェット後に出力すべきタグ（基本的には$before_widgetの閉じタグ）
		// $before_title タイトル前に出力すべきタグ
		// $after_title タイトル後に出力すべきタグ（基本的には$after_titleの閉じタグ）
		extract( $args );

		// 設定からタイトルを取得
		$widget_title = $instance[ 'widget_title' ];

		echo $before_widget;

		if( $widget_title != '' ) {
			echo $before_title . '<span class="widget-title">' . $widget_title . '</span>' . $after_title;
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

		// 標準値を設定
		$defaults = array( 'widget_title' => 'Popular Posts', 'show_list' => 5 );

		// 配列をパース
		$instance = wp_parse_args( (array) $instance, $defaults );

		// 設定済みのデータを取得
		$widget_title = esc_attr( $instance[ 'widget_title' ] );
		$show_list = esc_attr( $instance[ 'show_list' ] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>">
				<strong>Title（If you don't show anything, empty the textbox）</strong>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" value="<?php echo $widget_title; ?>" />
			<label for="<?php echo $this->get_field_id( 'show_list' ); ?>">
				<strong>Show List</strong>
			</label>
			<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'show_list' ); ?>" name="<?php echo $this->get_field_name( 'show_list' ); ?>" type="text" value="<?php echo $show_list; ?>" />
		</p>

		<?php
	}

}
