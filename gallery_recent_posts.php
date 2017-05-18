<?php
/**
 * @package   GalleryRecentPosts
 * @author    kamena
 * @license   GPL-2.0+
 * @copyright 2017 Kamena
 *
 * @wordpress-plugin
 * Plugin Name:       Gallery Recent Posts
 * Description:       Displays recent posts thumbnails as the masonry grid gallery.
 * Version:           1.0.0
 * Author:            kamena
 * Text Domain:       gallery-recent-posts
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

class GalleryRecentPosts extends WP_Widget {
     
    function __construct() {
    	parent::__construct(      
	        'gallery_recent_posts',
	        __('Gallery Recent Posts', 'gallery-recent-posts' ),
	        array ('description' => __( 'Shows the image from some posts.', 'gallery-recent-posts' ))     
	    );
    }
     
    function form( $instance ) {
		$title = isset( $instance['kd_title'] ) ? esc_attr( $instance['kd_title'] ) : '';
		$number_posts_pic = isset( $instance['kd_number_posts_pic'] ) ? absint( $instance['kd_number_posts_pic'] ) : 3;
		$number_columns = isset( $instance['kd_number_columns'] ) ? absint( $instance['kd_number_columns'] ) : 3;
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'kd_title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'kd_title' ); ?>" name="<?php echo $this->get_field_name( 'kd_title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'kd_number_posts_pic' ); ?>"><?php _e( 'Number of pictures to show:' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'kd_number_posts_pic' ); ?>" name="<?php echo $this->get_field_name( 'kd_number_posts_pic' ); ?>" type="number" value="<?php echo $number_posts_pic; ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'kd_number_columns' ); ?>"><?php _e( 'Number of columns:' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'kd_number_columns' ); ?>" name="<?php echo $this->get_field_name( 'kd_number_columns' ); ?>" type="number" value="<?php echo $number_columns; ?>" size="3" />
		</p>		             
<?php
    }
     
    function update( $new_instance, $old_instance ) {  
    	$instance = $old_instance;
    	$instance[ 'kd_title' ] = strip_tags($new_instance[ 'kd_title' ]);
    	$instance[ 'kd_number_posts_pic' ] = strip_tags( $new_instance[ 'kd_number_posts_pic' ] );
    	$instance[ 'kd_number_columns' ] = strip_tags( $new_instance[ 'kd_number_columns' ] );

   		return $instance;     
    }
     
    function widget( $args, $instance ) {
    	extract($args);
		$title = ( ! empty( $instance['kd_title'] ) ) ? $instance['kd_title'] : __( '' );
		$number_posts_pic = ( ! empty( $instance['kd_number_posts_pic'] ) ) ? $instance['kd_number_posts_pic'] : 3;
		$number_columns = ( ! empty( $instance['kd_number_columns'] ) ) ? $instance['kd_number_columns'] : 3;

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number_posts_pic,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'meta_query' => array( 
		        array(
		            'key' => '_thumbnail_id'
		        ) 
		    )
		) ) );

		if ($r->have_posts()) :

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>

			<style type="text/css">
			.gallery.masonry {
				-webkit-column-count: <?php echo $number_columns;?> ;
			    -moz-column-count: <?php echo $number_columns;?>;
			    column-count: <?php echo $number_columns;?>;
				-webkit-column-gap: 5px;
				-moz-column-gap: 5px;
				column-gap: 5px;
				display: block;
			}
			.gallery.masonry .column {
				-webkit-transition: all .2s ease;
				transition: all .2s ease;
				display: inline-block;
				margin: 3px;
				padding: 0;
			}

			.gallery.masonry [class*=column]+[class*=column]:last-child {
			    float: left;
			}
			</style>

			<div class="gallery masonry">
			<?php while ( $r->have_posts()) : $r->the_post();	?>
					<div class="column"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></div>
			<?php endwhile; ?>
			</div>

			<?php echo $args['after_widget'];

			wp_reset_postdata();

		endif;
    }
     
}


function gallery_recent_posts_register() {
    register_widget( 'GalleryRecentPosts' );
}
add_action( 'widgets_init', 'gallery_recent_posts_register' );

?>