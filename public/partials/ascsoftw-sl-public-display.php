<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://github.com/ascsoftw
 * @since      1.0.0
 *
 * @package    Ascsoftw_Sl
 * @subpackage Ascsoftw_Sl/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
?>

<div class="ascsoftw_sl_store_holder">

	<div class="ascsoftw_sl_store_header">
		<div class="ascsoftw_sl_row">
			<input id="ascsoftw_sl_searchtext" type="text" class="ascsoftw_sl_search-text controls" placeholder="<?php echo esc_html__( 'Enter the search location to find Nearest Store', 'ascsoftw-store-locator' ); ?>">
			<input type="hidden" value="<?php echo esc_html( $this->options->search_form->distance_unit ); ?>" id="ascsoftw_sl_distance_unit">
			<input type="hidden" value="<?php echo esc_html( get_admin_url() ); ?>" id="ascsoftw_admin_url">
			<input type="hidden" name="ascsoftw_sl_ajax_nonce" id="ascsoftw_sl_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'ascsoftw_sl_ajax_nonce' ) ); ?>" >
			<input type="hidden" value="<?php echo esc_html( $first_latitude ); ?>" id="ascsoftw_sl_f_lat">
			<input type="hidden" value="<?php echo esc_html( $first_longitude ); ?>" id="ascsoftw_sl_f_long">
			<span class="ascsoftw_sl_spinner"><img src="<?php echo ASCSOFTW_SL_PLUGINS_URL; ?>public/img/ajax-loader.gif"></span>
		</div>

		<div class="ascsoftw_sl_row">
			<?php if ( 'yes' === $this->options->search_form->max_dropdown ) : ?>
				<div class="ascosftw_sl_element">
					<label><?php echo esc_html__( 'Max Results', 'ascsoftw-store-locator' ); ?></label>
					<select id="ascsoftw_sl_max" class="ascsoftw_sl_search-text controls" onchange="ascsoftw_get_stores(); ">
						<?php foreach ( $max_results_options as $k => $v ) { ?>
							<option value="<?php echo esc_html( $k ); ?>" 
								<?php echo ( $k === $max_results_selected ) ? 'selected="selected"' : ''; ?>>
								<?php echo esc_html( $v ); ?>
							</option>
							<?php
						}
						?>
					</select>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $this->options->search_form->radius_dropdown ) : ?>
				<div class="ascosftw_sl_element">
					<label><?php echo esc_html__( 'Radius', 'ascsoftw-store-locator' ); ?></label>
					<select id="ascsoftw_sl_radius" class="ascsoftw_sl_search-text controls" onchange="ascsoftw_get_stores(); ">
						<option value="0"><?php echo esc_html__( '--Any--', 'ascsoftw-store-locator' ); ?></option>
						<?php foreach ( $distance_options as $k => $v ) { ?>
							<option value="<?php echo esc_html( $k ); ?>">
								<?php echo esc_html( $v ) . ' ' . esc_html( $this->options->search_form->distance_unit ); ?>
							</option>
							<?php
						}
						?>
					</select>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $this->options->search_form->category_dropdown ) : ?>
				<div class="ascosftw_sl_element">
					<label><?php echo esc_html__( 'Categories', 'ascsoftw-store-locator' ); ?></label>
					<select id="ascsoftw_sl_cats" class="" onchange="ascsoftw_get_stores()">
						<option value="0"><?php echo esc_html__( '--Any--', 'ascsoftw-store-locator' ); ?></option>
						<?php foreach ( $terms as $t ) : ?>
							<option value="<?php echo esc_attr( $t->term_id ); ?>"><?php echo esc_html( $t->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( 1 === $this->options->search_form->show_summary ) : ?>
			<div class="ascsoftw_sl_row ascsoftw_sl_ss_row">
				<?php echo esc_html__( 'Showing', 'ascsoftw-store-locator' ); ?> <span id="ascsoftw_sl_sfss"></span> <?php echo esc_html__( 'Results', 'ascsoftw-store-locator' ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php
	if ( $this->options->result_format->show ) {
		if ( 'right' === $this->options->result_format->position ) {
			$store_listing_class = 'ascsoftw_sl_right';
			$store_map_class     = 'ascsoftw_sl_left';
		} else {
			$store_listing_class = 'ascsoftw_sl_left';
			$store_map_class     = 'ascsoftw_sl_right';
		}
	} else {
		$store_map_class = '';
	}
	?>

	<div class="ascsoftw_sl_store_map <?php echo esc_html( $store_map_class ); ?>">
		<div id="ascsoftw_sl_map" style="height:<?php echo esc_html( $this->options->map_settings->height ); ?>px"></div>
	</div>

	<?php

	if ( $this->options->result_format->show ) :
		?>
		<div id="ascsoftw_sl_result_listing" class="ascsoftw_sl_store_results <?php echo esc_html( $store_listing_class ); ?>" style="height:<?php echo esc_html( $this->options->map_settings->height ); ?>px;overflow-y:scroll">
			<span id="ascsoftw_sl_no_results"><?php echo esc_html__( 'No Results Found', 'ascsoftw-store-locator' ); ?></span>
			<ul id="ascsoftw_sl_list"></ul>
		</div>
	<?php endif; ?>
</div>
