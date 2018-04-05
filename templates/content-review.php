<?php
/**
 * Review Content Part
 *
 * @package  tour-operator
 * @category review
 */

global $lsx_to_archive, $post;

if ( 1 !== $lsx_to_archive ) {
	$lsx_to_archive = false;
}
?>

<?php lsx_entry_before(); ?>

<article id="review-<?php echo esc_attr( $post->post_name ); ?>" <?php post_class( 'lsx-to-archive-container' ); ?>>
	<?php lsx_entry_top(); ?>

	<?php if ( is_single() && false === $lsx_to_archive ) { ?>

		<div <?php lsx_to_entry_class( 'entry-content' ); ?>>
			<div class="lsx-to-section-inner">
				<h3 class="lsx-to-summary-title"><?php the_title(); ?></h3>
				<div class="lsx-to-review-content"><?php the_content(); ?></div>

				<h3 class="lsx-to-summary-title"><?php esc_html_e( 'Trip Details', 'to-reviews' ); ?></h3>
				<?php lsx_to_fast_facts(); ?>
			</div>

			<?php lsx_to_sharing(); ?>
		</div>

	<?php } elseif ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_text'] ) ) { ?>

		<div <?php lsx_to_entry_class( 'entry-content' ); ?>><?php
			lsx_to_entry_content_top();
			the_excerpt();
			lsx_to_entry_content_bottom();
		?></div>

	<?php } ?>

	<?php lsx_entry_bottom(); ?>

</article>

<?php lsx_entry_after();
