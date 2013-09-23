<div class="wrap">
    <h2>WordPressIgniter</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('wp_igniter-group'); ?>
        <?php @do_settings_fields('wp_igniter-group'); ?>

        <?php do_settings_sections('wp_igniter'); ?>

        <?php @submit_button(); ?>
    </form>
</div>