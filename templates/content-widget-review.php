<?php
/**
 * Reviews Widget Content Part
 *
 * @package 	tour-operators
 * @category	review
 * @subpackage	widget
 */

global $disable_placeholder, $disable_text, $post;

$has_single = ! lsx_to_is_single_disabled();
$permalink = '';

if ( $has_single ) {
	$permalink = get_the_permalink();
} elseif ( ! is_post_type_archive( 'review' ) ) {
	$has_single = true;
	$permalink = get_post_type_archive_link( 'review' ) . '#review-' . $post->post_name;
}
?>
<article <?php post_class(); ?>>
	<?php if ( empty( $disable_placeholder ) ) { ?>
		<div class="lsx-to-widget-thumb">
			<?php if ( $has_single ) { ?><a href="<?php echo esc_url( $permalink ); ?>"><?php } ?>
				<?php lsx_thumbnail( 'lsx-thumbnail-wide' ); ?>
			<?php if ( $has_single ) { ?></a><?php } ?>
		</div>
	<?php } ?>

	<h4 class="lsx-to-widget-title text-center">
		<?php if ( $has_single ) { ?><a href="<?php echo esc_url( $permalink ); ?>"><?php } ?>
			<?php the_title(); ?>
		<?php if ( $has_single ) { ?></a><?php } ?>
	</h4>

	<?php if ( empty( $disable_text ) ) { ?>
		<blockquote class="lsx-to-widget-blockquote">
			<?php the_excerpt(); ?>
		</blockquote>
	<?php } ?>

	<div class="lsx-to-widget-meta-data">
		<?php
			$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

			lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'to-reviews' ) . ':</span> ', '</span>' );
			lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'to-reviews' ) . ':</span> ', '</span>' );
			lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Destinations', 'to-reviews' ) . ':</span> ', '</span>' );
			lsx_to_connected_team( '<span class="' . $meta_class . 'team"><span class="lsx-to-meta-data-key">' . __( 'Advised by', 'to-reviews' ) . ':</span> ', '</span>' );
		?>
	</div>

</article>
