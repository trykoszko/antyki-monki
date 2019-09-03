<?php
    $theme_url = get_template_directory_uri() . '/front';
?>

</main>

<section class="l-search-modal js-search-modal">
	<div class="l-inner">
		<div class="c-search-modal">
			<button class="c-search-modal__close js-close-search">
                <svg class="ui-icon c-icon c-icon--search">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $theme_url; ?>/static/symbol/svg/sprite.symbol.svg#close"></use>
                </svg>
            </button>
			<h2>Search form here</h2>
		</div>
	</div>
</section>

<?php wp_footer(); ?>

</body>
</html>
