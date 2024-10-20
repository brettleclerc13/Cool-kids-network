<?php
/*
 * Title: Custom-Hero
 * Slug: coolkidstheme/hero
 * Categories: banners
 */
?>
<!-- wp:columns -->
<div id="hero-section" class="wp-block-columns">
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:spacer {"height":100} -->
		<div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:heading -->
		<h1 class="wp-block-heading">Where the cool kids hang out</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p><?php echo esc_html_x( 'Come connect with your friends and meet the coolest kids on the block.', 'Content of the hero section', 'coolkidstheme' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button -->
			<div class="wp-block-button">
				<a class="wp-block-button__link wp-element-button">Get started</a>
			</div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:image -->
		<figure class="wp-block-image">
			<img src="http://localhost:8080/wp-content/uploads/2024/10/hero-image-1.png" alt="" class="wp-image-34"/>
		</figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
