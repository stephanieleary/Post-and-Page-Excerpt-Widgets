<?php 
/*
Plugin Name: Post and Page Excerpt Widgets
Plugin URI: http://www.sillybean.net/code/post-and-page-excerpt-widgets/
Description: Creates widgets that display excerpts from posts or pages in the sidebar. You may use 'more' links and/or link the widget title to the post or page.  Requires <a href="http://blog.ftwr.co.uk/wordpress/page-excerpt/">Page Excerpt</a> or <a href="http://www.laptoptips.ca/projects/wordpress-excerpt-editor/">Excerpt Editor</a> for page excerpts. Supports <a href="http://robsnotebook.com/the-excerpt-reloaded/">The Excerpt Reloaded</a> and <a href="http://sparepencil.com/code/advanced-excerpt/">Advanced Excerpt</a>.
Version: 2.2
Author: Stephanie Leary
Author URI: http://stephanieleary.com/
Text Domain: post-excerpt-widget
*/

function post_excerpt_widgets_init() {
	register_widget('PostExcerptMulti');
	register_widget('PageExcerptMulti');
}

add_action('widgets_init', 'post_excerpt_widgets_init');


// i18n
load_plugin_textdomain( 'post-excerpt-widget', '', plugin_dir_path(__FILE__) . '/languages' );


class PageExcerptMulti extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'page_widget_excerpt_multi', 'description' => __( 'Page Excerpt', 'post-excerpt-widget') );
		parent::__construct('pageexcerptmulti', __('Page Excerpt', 'post-excerpt-widget'), $widget_ops);
	}
	
	
	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Excerpt', 'post-excerpt-widget' ) : $instance['title']);
		
		echo $before_widget;
		if ( $title) {
			if (!empty($instance['postlink']))  {
				$before_title .= '<a href="'.get_permalink($instance['page_ID']).'">';
				$after_title .= '</a>';
			}
			echo $before_title.$title.$after_title;
		}
		
		
		// the Loop
		$page_query = new WP_Query('page_id='.$instance['page_ID']); 
		if ($page_query->have_posts()) :
			echo '<ul class="page-excerpt-widget">';
			while ($page_query->have_posts()) : $page_query->the_post(); 
			// the excerpt of the page
			if (function_exists('the_excerpt_reloaded')) {
				the_excerpt_reloaded( $instance['words'], $instance['tags'], 'content', FALSE, '', '', '1', '' );
				printf('<p class="more" title="%s"><a href="%s">%s</a></p>', __( 'Continue reading', 'post-excerpt-widget' ), get_permalink($instance['page_ID']), esc_html( $instance['more_text'] ) ); // 'more' link
			}
			else {
				the_excerpt(); // this covers Advanced Excerpt as well as the built-in one
			}
			endwhile;
		
			echo '</ul>';
		endif;
		echo $after_widget;
		wp_reset_query();
	}
	
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['page_ID'] = intval( $new_instance['page_ID'] );
		$instance['postlink'] = sanitize_text_field( $new_instance['postlink'] );
		$instance['more_text'] = sanitize_text_field( $new_instance['more_text'] );
		$instance['words'] = intval( $new_instance['words'] );
		$instance['tags'] = $new_instance['tags'];

		return $instance;
	}

	function form( $instance ) {
		//Defaults
			$instance = wp_parse_args( (array) $instance, array( 
					'title' => 'Excerpt', 
					'page_ID' => '',
					'postlink' => false,
					'more_text' => 'more...',
					'words' => '99999',
					'tags' => '<p><div><span><br><img><a><ul><ol><li><blockquote><cite><em><i><strong><b><h2><h3><h4><h5><h6>') );

?>  
      
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'post-excerpt-widget'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
		<p>
				<label for="<?php echo $this->get_field_id('page_ID'); ?>"><?php printf( __( 'Page ID: (<a href="%s">find</a>)', 'post-excerpt-widget' ), 'edit.php?post_type=page' ) ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('page_ID'); ?>" name="<?php echo $this->get_field_name('page_ID'); ?>" type="text" value="<?php echo esc_attr( $instance['page_ID'] ); ?>" />
		</p>
		<p>
				<label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e('"More" link text: ', 'post-excerpt-widget') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" type="text" value="<?php echo esc_attr( $instance['more_text'] ); ?>" />
				<br /><small><?php _e( 'Leave blank to omit "more" link' ) ?></small>
		</p>
		<p>
				<label for="<?php echo $this->get_field_id('postlink'); ?>"><?php _e('Link widget title to page?', 'post-excerpt-widget') ?></label>
				<input id="<?php echo $this->get_field_id('postlink'); ?>" name="<?php echo $this->get_field_name('postlink'); ?>" type="checkbox" value="1" <?php checked( 1, $instance['postlink'] ) ?> />
		</p>
		<?php
		if (function_exists('the_excerpt_reloaded')) { ?>
			<p>
			<label for="<?php echo $this->get_field_id('words'); ?>"><?php _e('Limit excerpt to how many words?', 'post-excerpt-widget') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('words'); ?>" name="<?php echo $this->get_field_name('words'); ?>" type="text" value="<?php echo esc_attr( $instance['words'] ); ?>" />
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Allowed HTML tags:', 'post-excerpt-widget') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text" value="<?php echo htmlspecialchars($instance['tags'], ENT_QUOTES); ?>" />
			<br /><small>E.g.: &lt;p&gt;&lt;div&gt;&lt;span&gt;&lt;br&gt;&lt;img&gt;&lt;a&gt;&lt;ul&gt;&lt;ol&gt;&lt;li&gt;&lt;blockquote&gt;&lt;cite&gt;&lt;em&gt;&lt;i&gt;&lt;strong&gt;&lt;b&gt;&lt;h2&gt;&lt;h3&gt;&lt;h4&gt;&lt;h5&gt;&lt;h6&gt;
			</small></p>
		<?php } 
	}
}


class PostExcerptMulti extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'post_widget_excerpt_multi', 'description' => __( 'Post Excerpt', 'post-excerpt-widget' ) );
		parent::__construct('postexcerptmulti', __('Post Excerpt', 'post-excerpt-widget'), $widget_ops);
	}	
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Excerpt', 'post-excerpt-widget' ) : $instance['title']);

		echo $before_widget;
		if ( $title) {
			if (!empty($instance['postlink']))  {
				$before_title .= '<a href="'.get_permalink($instance['post_ID']).'">';
				$after_title .= '</a>';
			}
			echo $before_title.$title.$after_title;
		}
						
		// the Loop
		$post_query = new WP_Query('p='.$instance['post_ID']); 

		if ($post_query->have_posts()) :
			echo '<ul class="post-excerpt-widget">';
			
			while ($post_query->have_posts()) : $post_query->the_post(); 
			// the excerpt of the post
			if (function_exists('the_excerpt_reloaded')) {
				the_excerpt_reloaded($instance['words'], $instance['tags'], 'content', FALSE, '', '', '1', '');
				printf('<p class="more" title="%s"><a href="%s">%s</a></p>', __( 'Continue reading', 'post-excerpt-widget' ), get_permalink($instance['post_ID']), esc_html( $instance['more_text'] ) ); // 'more' link
			}
			else {
				the_excerpt();  // this covers Advanced Excerpt as well as the built-in one
			}
			endwhile;
			echo '</ul>';
		endif;
		echo $after_widget;
		wp_reset_query();
	}
	
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['post_ID'] = intval( $new_instance['post_ID'] );
		$instance['postlink'] = sanitize_text_field( $new_instance['postlink'] );
		$instance['more_text'] = sanitize_text_field( $new_instance['more_text'] );
		$instance['words'] = intval( $new_instance['words'] );
		$instance['tags'] = $new_instance['tags'];

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 
				'title' => 'Excerpt', 
				'post_ID' => '',
				'postlink' => false,
				'more_text' => 'more...',
				'words' => '99999',
				'tags' => '<p><div><span><br><img><a><ul><ol><li><blockquote><cite><em><i><strong><b><h2><h3><h4><h5><h6>') );
?>
  
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'post-excerpt-widget'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
		<p>
				<label for="<?php echo $this->get_field_id('post_ID'); ?>"><?php printf( __( 'Post ID: (<a href="%s">find</a>)', 'post-excerpt-widget' ), 'edit.php' ) ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('post_ID'); ?>" name="<?php echo $this->get_field_name('post_ID'); ?>" type="text" value="<?php echo esc_attr( $instance['post_ID'] ); ?>" />
		</p>
		<p>
				<label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e('"More" link text: ', 'post-excerpt-widget') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" type="text" value="<?php echo esc_attr( $instance['more_text'] ); ?>" />
				<br /><small><?php _e( 'Leave blank to omit "more" link' ) ?></small>
		</p>
		<p>
				<label for="<?php echo $this->get_field_id('postlink'); ?>"><?php _e('Link widget title to post?', 'post-excerpt-widget') ?></label>
				<input id="<?php echo $this->get_field_id('postlink'); ?>" name="<?php echo $this->get_field_name('postlink'); ?>" type="checkbox" value="1" <?php checked( 1, $instance['postlink'] ) ?> />
		</p>
		<?php
		if (function_exists('the_excerpt_reloaded')) { ?>
			<p>
			<label for="<?php echo $this->get_field_id('words'); ?>"><?php _e('Limit excerpt to how many words?', 'post-excerpt-widget') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('words'); ?>" name="<?php echo $this->get_field_name('words'); ?>" type="text" value="<?php echo esc_attr( $instance['words'] ); ?>" />
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Allowed HTML tags:', 'post-excerpt-widget') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text" value="<?php echo htmlspecialchars($instance['tags'], ENT_QUOTES); ?>" />
			<br /><small>E.g.: &lt;p&gt;&lt;div&gt;&lt;span&gt;&lt;br&gt;&lt;img&gt;&lt;a&gt;&lt;ul&gt;&lt;ol&gt;&lt;li&gt;&lt;blockquote&gt;&lt;cite&gt;&lt;em&gt;&lt;i&gt;&lt;strong&gt;&lt;b&gt;&lt;h2&gt;&lt;h3&gt;&lt;h4&gt;&lt;h5&gt;&lt;h6&gt;
			</small></p>
		<?php } 
	}
}