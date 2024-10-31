<?php

if ( ! defined('ABSPATH')) {
    die;
}

function ordto_config_view()
{
    $api_key   = "";
    $site_url  = "";
    $view_mode = "";

    ordto_post_config();
    ordto_config_values($api_key, $site_url, $view_mode);

    if (empty($api_key)) {
        ?>
        <div class="ordto-banner ordto-attention-banner">
            If you do not have an account at ord.to,
            then you can create one
            <a href="https://cloud.ord.to/register" target="_blank">here</a>
        </div>
        <?php
    }
    ?>
    <div class="ordto-banner ordto-info-banner">
        Here you can change all your configuration settings at one time,
        change your API key only,
        change your site URL and view mode only
    </div>

    <form method='post'>
        <label for="ordto-inp_api">
            <p> Input your API from ord.to -> Integrations -> API -> API key for this company:</p>
        </label>
        <input type="text" id="ordto-inp_api" name='ordto-api_key' size="40"
            <?php if (!empty($api_key)){
            ?> value="<?php echo esc_attr($api_key) ?>" <?php
               }else { ?>placeholder="API key" <?php } ?>autofocus>
        <br>
        <label for="ordto-url_site">
            <p> Input your site URL from ord.to -> Go to your page:</p>
        </label>
        <input type="text" id="ordto-url_site" name='ordto-site_url' size="40"
            <?php if (!empty($site_url)) {
                ?> value="<?php echo esc_attr($site_url) ?>" <?php
            } else { ?>placeholder="Site URL"<?php } ?> >
        <br>
        <p> Select a view mode:</p>
        <input id="ordto-menu" type="radio" name="ordto-menu/widget" value="menu"
            <?php if (!empty($view_mode)) {
                if ($view_mode == 'menu') {
                    ?> checked <?php
                }
            } ?> > <label for="ordto-menu"> Menu</label>
        <br>
        <input id="ordto-widget" type="radio" name="ordto-menu/widget" value="widget"
            <?php if (!empty($view_mode)) {
                if ($view_mode == 'widget') {
                    ?> checked <?php
                }
            } ?> > <label for="ordto-widget"> Widget</label>
        <br>
        <br>
        <input class="ordto-but ordto-reset-but" type='reset' name='res1' value="Reset">
        <input class='ordto-but ordto-save-but' type='submit' name='sub1' value="Save">
    </form>
    <?php
}

function ordto_post_config()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        global $wpdb;

        $wpdb->insert($wpdb->options, ['option_name' => 'ordto_api_key']);
        $wpdb->insert($wpdb->options, ['option_name' => 'ordto_site_url']);
        $wpdb->insert($wpdb->options, ['option_name' => 'ordto_view_mode']);
        $wpdb->insert($wpdb->options, ['option_name' => 'ordto_page_number_products']);
        $wpdb->insert($wpdb->options, ['option_name' => 'ordto_page_number_orders']);

        if ( ! empty($_POST['ordto-api_key']) && ! empty($_POST['ordto-site_url']) && ! empty($_POST['ordto-menu/widget'])) {
            if(preg_match('/^[a-z0-9]{32}+$/', $_POST['ordto-api_key']) && preg_match('%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?(/)$%i',$_POST['ordto-site_url'])){
                $wpdb->update($wpdb->options, ['option_value' => sanitize_key($_POST['ordto-api_key'])], ['option_name' => 'ordto_api_key']);
                $wpdb->update($wpdb->options, ['option_value' => esc_url_raw($_POST['ordto-site_url'])], ['option_name' => 'ordto_site_url']);
                $wpdb->update($wpdb->options, ['option_value' => sanitize_text_field($_POST['ordto-menu/widget'])], ['option_name' => 'ordto_view_mode']);

                echo "<h3 class='ordto-h3'>All configuration added successfully!</h3>";

            }elseif(!preg_match('/^[a-z0-9]{32}$/', $_POST['ordto-api_key']) && !preg_match('%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?(/)$%i',$_POST['ordto-site_url'])){
                echo "<h3 class='ordto-h3'>Invalid configuration!</h3>";

            }elseif(!preg_match('%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?(/)$%i',$_POST['ordto-site_url'])){
                echo "<h3 class='ordto-h3'>Invalid site URL!</h3>";

            }elseif (!preg_match('/^[a-z0-9]{32}$/', $_POST['ordto-api_key'])){
                echo "<h3 class='ordto-h3'>Invalid API key!</h3>";
            }

        } elseif (empty($_POST['ordto-api_key']) && ! empty($_POST['ordto-site_url']) && ! empty($_POST['ordto-menu/widget'])) {
            if(preg_match('%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?(/)$%i',$_POST['ordto-site_url'])){
                $wpdb->update($wpdb->options, ['option_value' => esc_url_raw($_POST['ordto-site_url'])], ['option_name' => 'ordto_site_url']);
                $wpdb->update($wpdb->options, ['option_value' => sanitize_text_field($_POST['ordto-menu/widget'])], ['option_name' => 'ordto_view_mode']);

                echo "<h3 class='ordto-h3'>Site configuration added successfully!</h3>";

            }elseif(!preg_match('%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?(/)$%i',$_POST['ordto-site_url'])) {
                echo "<h3 class='ordto-h3'>Invalid site URL!</h3>";
            }

        } elseif ( ! empty($_POST['ordto-api_key']) && empty($_POST['ordto-site_url']) && empty($_POST['ordto-menu/widget'])) {
                if(preg_match('/^[a-z0-9]{32}$/', $_POST['ordto-api_key'])){
                    $wpdb->update($wpdb->options, ['option_value' => sanitize_key($_POST['ordto-api_key'])], ['option_name' => 'ordto_api_key']);
                    echo "<h3 class='ordto-h3'>API key added successfully!</h3>";

                }elseif (!preg_match('/^[a-z0-9]{32}$/', $_POST['ordto-api_key'])){
                    echo "<h3 class='ordto-h3'>Invalid API key!</h3>";
                }
        } else {

            return;
        }
    }
}

function ordto_config_values(&$api, &$url, &$wm)
{
    global $wpdb;

    $api = $wpdb->get_var("select option_value from $wpdb->options where option_name = 'ordto_api_key'");
    $url = $wpdb->get_var("select option_value from $wpdb->options where option_name = 'ordto_site_url'");
    $wm  = $wpdb->get_var("select option_value from $wpdb->options where option_name = 'ordto_view_mode'");
}

?>