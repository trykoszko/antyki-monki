<?php
get_header();
?>

<div class="l-inner l-inner--wide">

    <div class="c-main-grid c-main-grid--empty">
        <h2 class="c-section-title">
            <?php _e('Nie znaleziono produktów.', 'antyki'); ?>
        </h2>
        <a href="#" class="c-section-link" onclick="window.history.go('-1')">
            <span class="c-label">
                <?php _e('&laquo; Powrót do wszystkich produktów', 'antyki'); ?>
            </span>
        </a>
    </div>

</div>

<?php
get_footer();
