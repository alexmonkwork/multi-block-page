<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://delay-delo.com
 * @since      1.0.0
 *
 * @package    Multiblock_Page
 * @subpackage Multiblock_Page/admin/partials
 */
?>

<form method="post" name="<?php echo $this->plugin_name ?>" action="options.php">

    <?php

    /** Download all form element values */
    $options = get_option($this->plugin_name);

    /** Сurrent status of options */
    $enable_gutenberg = $options['enable_gutenberg'];
    $custom_css       = $options['custom_css'];
    $custom_template  = $options['custom_template'];

    /** Displays hidden form fields on the settings page */
    settings_fields( $this->plugin_name );
    do_settings_sections( $this->plugin_name );

    ?>

    <h2><?php echo esc_attr_e( 'Settings Page' ); ?></h2>

    <hr>

    <fieldset>
        <legend class="screen-reader-text">
            <span><?php _e('Enable gutenberg editor in blocks', $this->plugin_name);?>
            </span>
        </legend>
        <label for="<?php echo $this->plugin_name;?>-enable_gutenberg">
            <span><?php esc_attr_e('Enable gutenberg editor in blocks', $this->plugin_name);?></span>
        </label>
        <p>Includes Gutenberg editor support in blocks</p>
        <input type="checkbox" id="<?php echo $this->plugin_name;?>-enable_gutenberg"
               name="<?php echo $this->plugin_name;?>[enable_gutenberg]"
               value="1" <?php checked( $enable_gutenberg, 1 ); ?>
        />
     </fieldset>

    <hr>

    <fieldset>
        <legend class="screen-reader-text">
            <span>
                <?php _e('Custom CSS', $this->plugin_name);?>
            </span>
        </legend>
        <label for="<?php echo $this->plugin_name;?>-footer_text">
            <span>
                <?php esc_attr_e('Custom CSS', $this->plugin_name);?>
            </span>
        </label>
        <p>You can add your own styles. Function in development</p>
        <textarea name="<?php echo $this->plugin_name;?>[custom_css]" rows="4" cols="53" disabled>
            <?php if(!empty($custom_css)) esc_attr_e($custom_css, $this->plugin_name);?>
        </textarea>
    <fieldset>

    <hr>

    <fieldset>
        <legend class="screen-reader-text">
            <span><?php _e('Custom template', $this->plugin_name);?></span>
        </legend>
        <label for="<?php echo $this->plugin_name;?>-custom_template">
            <span>
                <?php esc_attr_e('Custom template', $this->plugin_name);?>
            </span>
        </label>
        <p>You can add your own template. Function in development</p>
        <textarea name="<?php echo $this->plugin_name;?>[custom_template]" rows="4" cols="53" disabled>
            <?php if(!empty($custom_template)) esc_attr_e($custom_template, $this->plugin_name);?>
        </textarea>
    <fieldset>

    <?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>

</form>
