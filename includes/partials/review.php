<div class="uix-field-wrapper">

	<ul class="ui-tab-nav">
		<li><a href="#ui-general" class="active"><?php esc_html_e('General','to-reviews'); ?></a></li>
		<?php if(class_exists('LSX_TO_Search')) { ?>
			<li><a href="#ui-search"><?php esc_html_e('Search','to-reviews'); ?></a></li>
		<?php } ?>
		<li><a href="#ui-placeholders"><?php esc_html_e('Placeholders','to-reviews'); ?></a></li>
		<li><a href="#ui-archives"><?php esc_html_e('Archives','to-reviews'); ?></a></li>
		<li><a href="#ui-single"><?php esc_html_e('Single','to-reviews'); ?></a></li>
	</ul>

	<div id="ui-general" class="ui-tab active">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_review_tab_content','review','general'); ?>
			</tbody>
		</table>
	</div>

	<?php if(class_exists('LSX_TO_Search')) { ?>
		<div id="ui-search" class="ui-tab">
			<table class="form-table">
				<tbody>
				<?php do_action('lsx_to_framework_review_tab_content','review','search'); ?>
				</tbody>
			</table>
		</div>
	<?php } ?>

	<div id="ui-placeholders" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_review_tab_content','review','placeholders'); ?>
			</tbody>
		</table>
	</div>

	<div id="ui-archives" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_review_tab_content','review','archives'); ?>
			</tbody>
		</table>
	</div>

	<div id="ui-single" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_review_tab_content','review','single'); ?>
			</tbody>
		</table>
	</div>
	<?php do_action('lsx_to_framework_review_tab_bottom','review'); ?>
</div>
