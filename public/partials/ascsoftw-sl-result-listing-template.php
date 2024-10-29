<?php
/**
 * Provide a Template for the Result Listing
 *
 * This file provides the Template for Result Listing.
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
<script type="text/html" id="tmpl-ascsoftw-sl-result">

<li class="ascsoftw_sl_result" id="ascsoftw_sl_loc_{{data.id}}" onclick="ascsoftw_show_location_in_map(this, {{data.id}})">
	<h4>
		<?php if ( 1 === $this->options->result_format->url ) : ?>
			<# if ( data.attributes.url ) { #>
				<a href="{{data.attributes.url}}" target="_blank">{{data.attributes.title}}</a>
			<# } else { #>
				{{data.attributes.title}}
			<# } #>
		<?php else: ?>
			{{data.attributes.title}}
		<?php endif; ?>
	</h4>
	<div class="ascsoftw_sl_detail">
		<div class="ascsoftw_sl_detail_wrap_main">
			{{data.attributes.address}}
		</div>
		<?php if ( 1 === $this->options->result_format->address2 ) : ?>
			<# if ( data.attributes.address_2 ) { #>
				<div class="ascsoftw_sl_detail_wrap">
					{{data.attributes.address_2}}
				</div>
			<# } #>
		<?php endif; ?>

		<div class="ascsoftw_sl_detail_wrap">
			{{data.attributes.city}}
		</div>

		<?php if ( 1 === $this->options->result_format->state ) : ?>
			<# if ( data.attributes.state ) { #>
				<div class="ascsoftw_sl_detail_wrap">
					{{data.attributes.state}}
				</div>
			<# } #>
		<?php endif; ?>

		<?php if ( 1 === $this->options->result_format->zip ) : ?>
			<# if ( data.attributes.zip ) { #>
				<div class="ascsoftw_sl_detail_wrap">
					{{data.attributes.zip}}
				</div>
			<# } #>
		<?php endif; ?>

		<?php if ( 1 === $this->options->result_format->country ) : ?>
			<# if ( data.attributes.country ) { #>
				<div class="ascsoftw_sl_detail_wrap">
					{{data.attributes.country}}
				</div>
			<# } #>
		<?php endif; ?>

		<div class="ascsoftw_sl_detail_wrap">
			{{data.attributes.phone}}
		</div>
		<div class="ascsoftw_sl_detail_wrap">
			{{data.attributes.email}}
		</div>

	</div>
	<div><a title="<?php echo esc_html__( 'Get Directions', 'ascsoftw-store-locator' ); ?> <?php echo esc_html__( 'for', 'ascsoftw-store-locator' ); ?> {{data.attributes.title}} {{data.attributes.city}}" href="#" onclick="ascsoftw_get_directions({{data.id}}); return false;"><?php echo esc_html__( 'Get Directions', 'ascsoftw-store-locator' ); ?></a></div>
</li>

</script>
