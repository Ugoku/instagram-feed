<?php
if (!defined('ABSPATH')) {
    exit;  // Exit if accessed directly
}

function sb_instagram_menu()
{
    add_menu_page(
        __('Instagram Feed', 'instagram-feed'),
        __('Instagram Feed', 'instagram-feed'),
        'manage_options',
        'sb-instagram-feed',
        'sb_instagram_settings_page'
    );
    add_submenu_page(
        'sb-instagram-feed',
        __('Settings', 'instagram-feed'),
        __('Settings', 'instagram-feed'),
        'manage_options',
        'sb-instagram-feed',
        'sb_instagram_settings_page'
    );
}
add_action('admin_menu', 'sb_instagram_menu');

function sb_instagram_settings_page()
{
    //Hidden fields
    $sb_instagram_settings_hidden_field = 'sb_instagram_settings_hidden_field';
    $sb_instagram_configure_hidden_field = 'sb_instagram_configure_hidden_field';
    $sb_instagram_customize_hidden_field = 'sb_instagram_customize_hidden_field';

    //Declare defaults
    $sb_instagram_settings_defaults = [
        'sb_instagram_at' => '',
        'sb_instagram_user_id' => '',
        'sb_instagram_preserve_settings' => '',
        'sb_instagram_num' => '12',
        'sb_instagram_sort' => 'date',
        'sb_instagram_show_header' => true,
    ];
    //Save defaults in an array
    $options = wp_parse_args(get_option('sb_instagram_settings'), $sb_instagram_settings_defaults);
    update_option('sb_instagram_settings', $options, true);

    //Set the page variables
    $sb_instagram_at = $options['sb_instagram_at'];
    $sb_instagram_user_id = $options['sb_instagram_user_id'];
    $sb_instagram_preserve_settings = $options['sb_instagram_preserve_settings'];
    $sb_instagram_num = $options['sb_instagram_num'];
    $sb_instagram_sort = $options['sb_instagram_sort'];
    //Header
    $sb_instagram_show_header = $options['sb_instagram_show_header'];
    $sb_instagram_show_bio = isset($options['sb_instagram_show_bio'] ) ? $options['sb_instagram_show_bio'] : true;


    // Check nonce before saving data
    if (!isset($_POST['sb_instagram_settings_nonce'] ) || ! wp_verify_nonce($_POST['sb_instagram_settings_nonce'], 'sb_instagram_saving_settings')) {
        // Nonce did not verify
        //echo '<p style="color: red">' . __('Nonce not set or invalid', 'instagram-feed') . '</p>';
    } else {
        // See if the user has posted us some information. If they did, this hidden field will be set to 'Y'.
        if (isset($_POST[$sb_instagram_settings_hidden_field]) && $_POST[$sb_instagram_settings_hidden_field] == 'Y') {

            if (isset($_POST[$sb_instagram_configure_hidden_field]) && $_POST[$sb_instagram_configure_hidden_field] == 'Y') {

                $sb_instagram_at = sanitize_text_field($_POST['sb_instagram_at'] );
                $sb_instagram_user_id = sanitize_text_field($_POST['sb_instagram_user_id'] );

                $sb_instagram_preserve_settings = isset($_POST['sb_instagram_preserve_settings']) ? sanitize_text_field($_POST['sb_instagram_preserve_settings'] ) : '';

                $options['sb_instagram_at'] = $sb_instagram_at;
                $options['sb_instagram_user_id'] = $sb_instagram_user_id;
                $options['sb_instagram_preserve_settings'] = $sb_instagram_preserve_settings;
            } //End config tab post

            if (isset($_POST[$sb_instagram_customize_hidden_field]) && $_POST[$sb_instagram_customize_hidden_field] == 'Y') {
                
                //Validate and sanitize number of photos field
                $safe_num = intval( sanitize_text_field($_POST['sb_instagram_num'] ) );
                if (! $safe_num ) $safe_num = '';
                if (strlen($safe_num ) > 4 ) $safe_num = substr($safe_num, 0, 4 );
                $sb_instagram_num = $safe_num;

                $sb_instagram_sort = sanitize_text_field($_POST['sb_instagram_sort'] );
                //Header
                $sb_instagram_show_header = isset($_POST['sb_instagram_show_header']) ? sanitize_text_field($_POST['sb_instagram_show_header']) : '';
                $sb_instagram_show_bio = isset($_POST['sb_instagram_show_bio']) ? sanitize_text_field($_POST['sb_instagram_show_bio']) : '';

                $options['sb_instagram_num'] = $sb_instagram_num;
                $options['sb_instagram_sort'] = $sb_instagram_sort;
                //Header
                $options['sb_instagram_show_header'] = $sb_instagram_show_header;
                $options['sb_instagram_show_bio'] = $sb_instagram_show_bio;
            } //End customize tab post
            
            //Save the settings to the settings array
            wp_cache_delete('alloptions', 'options' );
            $status = update_option('sb_instagram_settings', $options, true);

            if ($status) {
                echo '<div class="updated"><p><strong>' . __('Settings saved.', 'instagram-feed') . '</strong></p></div>';
            } else {
                echo '<p style="color: red">' . __('Saving settings failed.', 'instagram-feed') . '</p>';
            }
        }
    } //End nonce check ?>


    <div id="sbi_admin" class="wrap">

        <div id="header">
            <h1><?php _e('Instagram Feed', 'instagram-feed'); ?></h1>
        </div>
    
        <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $sb_instagram_settings_hidden_field; ?>" value="Y">
            <?php wp_nonce_field('sb_instagram_saving_settings', 'sb_instagram_settings_nonce'); ?>

            <?php $sbi_active_tab = $_GET['tab'] ?? 'configure'; ?>
            <h2 class="nav-tab-wrapper">
                <a href="?page=sb-instagram-feed&amp;tab=configure" class="nav-tab <?= $sbi_active_tab == 'configure' ? 'nav-tab-active' : ''; ?>"><?php _e('1. Configure', 'instagram-feed'); ?></a>
                <a href="?page=sb-instagram-feed&amp;tab=customize" class="nav-tab <?= $sbi_active_tab == 'customize' ? 'nav-tab-active' : ''; ?>"><?php _e('2. Customize', 'instagram-feed'); ?></a>
                <a href="?page=sb-instagram-feed&amp;tab=display"   class="nav-tab <?= $sbi_active_tab == 'display'   ? 'nav-tab-active' : ''; ?>"><?php _e('3. Display Your Feed', 'instagram-feed'); ?></a>
                <a href="?page=sb-instagram-feed&amp;tab=support"   class="nav-tab <?= $sbi_active_tab == 'support'   ? 'nav-tab-active' : ''; ?>"><?php _e('Support', 'instagram-feed'); ?></a>
            </h2>

            <?php if ($sbi_active_tab == 'configure') { //Start Configure tab ?>
            <input type="hidden" name="<?php echo $sb_instagram_configure_hidden_field; ?>" value="Y">

            <table class="form-table">
                <tbody>
                    <h3><?php _e('Configure', 'instagram-feed'); ?></h3>

                    <div id="sbi_config">
                        <a href="https://instagram.com/oauth/authorize/?client_id=3a81a9fa2a064751b8c31385b91cc25c&scope=basic+public_content&redirect_uri=https://smashballoon.com/instagram-feed/instagram-token-plugin/?return_uri=<?php echo admin_url('admin.php?page=sb-instagram-feed'); ?>&response_type=token" class="sbi_admin_btn"><?php _e('Log in and get my Access Token and User ID', 'instagram-feed'); ?></a>
                        <a href="https://smashballoon.com/instagram-feed/token/" target="_blank" style="position: relative; top: 14px; left: 15px;"><?php _e('Button not working?', 'instagram-feed'); ?></a>
                    </div>
                    
                    <tr valign="top">
                        <th scope="row"><label><?php _e('Access Token', 'instagram-feed'); ?></label></th>
                        <td>
                            <input name="sb_instagram_at" id="sb_instagram_at" type="text" value="<?php echo esc_attr($sb_instagram_at ); ?>" size="60" maxlength="60" placeholder="Click button above to get your Access Token">
                           &nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php _e('What is this?', 'instagram-feed'); ?></a>
                            <p class="sbi_tooltip"><?php _e("In order to display your photos you need an Access Token from Instagram. To get yours, simply click the button above and log into Instagram. You can also use the button on <a href='https://smashballoon.com/instagram-feed/token/' target='_blank'>this page</a>.", 'instagram-feed'); ?></p>
                        </td>
                    </tr>

                    <tr valign="top" class="sbi_feed_type">
                        <th scope="row"><label><?php _e('Show Photos From:', 'instagram-feed'); ?></label><code class="sbi_shortcode"> type
                            Eg: type=user id=12986477
                        </code></th>
                        <td>
                            <span>
                                <?php $sb_instagram_type = 'user'; ?>
                                <input type="hidden" name="sb_instagram_type" id="sb_instagram_type_user" value="user">
                                <label class="sbi_radio_label" for="sb_instagram_type_user"><?php _e('User ID(s):', 'instagram-feed'); ?></label>
                                <input name="sb_instagram_user_id" id="sb_instagram_user_id" type="text" value="<?php echo esc_attr($sb_instagram_user_id ); ?>" size="25">
                                &nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php _e('What is this?', 'instagram-feed'); ?></a>
                                <p class="sbi_tooltip"><?php _e("These are the IDs of the Instagram accounts you want to display photos from. To get your ID simply click on the button above and log into Instagram.<br /><br />You can also display photos from other peoples Instagram accounts. To find their User ID you can use <a href='https://smashballoon.com/instagram-feed/find-instagram-user-id/' target='_blank'>this tool</a>. You can separate multiple IDs using commas.", 'instagram-feed'); ?></p><br />
                            </span>

                            <div class="sbi_notice sbi_user_id_error">
                                <?php _e("<p>Please be sure to enter your numeric <b>User ID</b> and not your Username. You can find your User ID by clicking the blue Instagram Login button above, or by entering your username into <a href='https://smashballoon.com/instagram-feed/find-instagram-user-id/' target='_blank'>this tool</a>.</p>", 'instagram-feed'); ?>
                            </div>
                            
                            <span class="sbi_note" style="margin: 10px 0 0 0; display: block;"><?php _e('Separate multiple IDs using commas', 'instagram-feed'); ?></span>
                           
                        </td>
                    </tr>

                    <tr>
                        <th class="bump-left"><label for="sb_instagram_preserve_settings" class="bump-left"><?php _e("Preserve settings when plugin is removed", 'instagram-feed'); ?></label></th>
                        <td>
                            <input name="sb_instagram_preserve_settings" type="checkbox" id="sb_instagram_preserve_settings" <?php if ($sb_instagram_preserve_settings == true) echo "checked"; ?> />
                            <label for="sb_instagram_preserve_settings"><?php _e('Yes', 'instagram-feed'); ?></label>
                            <a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php _e('What does this mean?', 'instagram-feed'); ?></a>
                            <p class="sbi_tooltip"><?php _e('When removing the plugin your settings are automatically erased. Checking this box will prevent any settings from being deleted. This means that you can uninstall and reinstall the plugin without losing your settings.', 'instagram-feed'); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php submit_button(); ?>
        </form>

        <p><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>&nbsp; <?php _e('Next Step: <a href="?page=sb-instagram-feed&tab=customize">Customize your Feed</a>', 'instagram-feed'); ?></p>

        <p><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php _e('Need help setting up the plugin? Check out our <a href="https://smashballoon.com/instagram-feed/free/" target="_blank">setup directions</a>', 'instagram-feed'); ?></p>


    <?php } // End Configure tab ?>



    <?php if ($sbi_active_tab == 'customize') { //Start Configure tab ?>

    <input type="hidden" name="<?php echo $sb_instagram_customize_hidden_field; ?>" value="Y">

        <hr id="photos" />
        <h3><?php _e('Photos', 'instagram-feed'); ?></h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Number of Photos', 'instagram-feed'); ?></label><code class="sbi_shortcode"> num
                        Eg: num=6</code></th>
                    <td>
                        <input name="sb_instagram_num" type="number" value="<?php echo esc_attr($sb_instagram_num ); ?>" min="1" max="33" size="4" maxlength="4">
                        &nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php _e("Using multiple IDs or hashtags?", 'instagram-feed'); ?></a>
                            <p class="sbi_tooltip"><?php _e("If you're displaying photos from multiple User IDs or hashtags then this is the number of photos which will be displayed from each.", 'instagram-feed'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Sort Photos By', 'instagram-feed'); ?></label><code class="sbi_shortcode"> sortby
                        Eg: sortby=random</code></th>
                    <td>
                        <select name="sb_instagram_sort">
                            <option value="date" <?php if ($sb_instagram_sort == "date") echo 'selected="selected"' ?> ><?php _e('Newest to oldest', 'instagram-feed'); ?></option>
                            <option value="random" <?php if ($sb_instagram_sort == "random") echo 'selected="selected"' ?> ><?php _e('Random', 'instagram-feed'); ?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>

        <hr id="headeroptions">
        <h3><?php _e("Header", 'instagram-feed'); ?></h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e("Show the Header", 'instagram-feed'); ?></label><code class="sbi_shortcode"> showheader
                        Eg: showheader=false</code></th>
                    <td>
                        <input type="checkbox" name="sb_instagram_show_header" id="sb_instagram_show_header" <?php if ($sb_instagram_show_header == true) echo 'checked="checked"' ?> />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e("Show Bio Text"); ?></label><code class="sbi_shortcode"> showbio
                        Eg: showbio=false</code></th>
                    <td>
                        <?php $sb_instagram_show_bio = isset($sb_instagram_show_bio ) ? $sb_instagram_show_bio  : true; ?>
                        <input type="checkbox" name="sb_instagram_show_bio" id="sb_instagram_show_bio" <?php if ($sb_instagram_show_bio == true) echo 'checked="checked"' ?> />
                        <span class="sbi_note"><?php _e("This only applies for User IDs with bios"); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>
        </form>

    <p><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>&nbsp; <?php _e('Next Step: <a href="?page=sb-instagram-feed&tab=display">Display your Feed</a>', 'instagram-feed'); ?></p>

    <p><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php _e('Need help setting up the plugin? Check out our <a href="https://smashballoon.com/instagram-feed/free/" target="_blank">setup directions</a>', 'instagram-feed'); ?></p>


    <?php } //End Customize tab ?>



    <?php if ($sbi_active_tab == 'display') { //Start Display tab ?>

        <h3><?php _e('Display your Feed', 'instagram-feed'); ?></h3>
        <p><?php _e("Copy and paste the following shortcode directly into the page, post or widget where you'd like the feed to show up:", 'instagram-feed'); ?></p>
        <input type="text" value="[instagram-feed]" size="16" readonly="readonly" style="text-align: center;" onclick="this.focus();this.select()" title="<?php _e('To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', 'instagram-feed'); ?>" />

        <h3 style="padding-top: 10px;"><?php _e('Multiple Feeds', 'instagram-feed'); ?></h3>
        <p><?php _e("If you'd like to display multiple feeds then you can set different settings directly in the shortcode like so:", 'instagram-feed'); ?>
        <code>[instagram-feed num=9]</code></p>
        <p><?php _e('You can display as many different feeds as you like, on either the same page or on different pages, by just using the shortcode options below. For example:', 'instagram-feed'); ?><br />
        <code>[instagram-feed]</code><br />
        <code>[instagram-feed id="ANOTHER_USER_ID"]</code><br />
        <code>[instagram-feed id="ANOTHER_USER_ID, YET_ANOTHER_USER_ID" num=4]</code>
        </p>
        <p><?php _e("See the table below for a full list of available shortcode options:", 'instagram-feed'); ?></p>

        <table class="sbi_shortcode_table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('Shortcode option', 'instagram-feed'); ?></th>
                    <th scope="row"><?php _e('Description', 'instagram-feed'); ?></th>
                    <th scope="row"><?php _e('Example', 'instagram-feed'); ?></th>
                </tr>

                <tr class="sbi_table_header"><td colspan=3><?php _e("Configure Options", 'instagram-feed'); ?></td></tr>
                <tr>
                    <td>id</td>
                    <td><?php _e('An Instagram User ID. Separate multiple IDs by commas.', 'instagram-feed'); ?></td>
                    <td><code>[instagram-feed id="1234567"]</code></td>
                </tr>

                <tr class="sbi_table_header"><td colspan=3><?php _e("Customize Options", 'instagram-feed'); ?></td></tr>
                <tr>
                    <td>class</td>
                    <td><?php _e("Add a CSS class to the feed container", 'instagram-feed'); ?></td>
                    <td><code>[instagram-feed class=feedOne]</code></td>
                </tr>

                <tr class="sbi_table_header"><td colspan=3><?php _e("Photos Options", 'instagram-feed'); ?></td></tr>
                <tr>
                    <td>sortby</td>
                    <td><?php _e("Sort the posts by Newest to Oldest (date) or Random (random)", 'instagram-feed'); ?></td>
                    <td><code>[instagram-feed sortby=random]</code></td>
                </tr>
                <tr>
                    <td>num</td>
                    <td><?php _e("The number of photos to display initially. Maximum is 33.", 'instagram-feed'); ?></td>
                    <td><code>[instagram-feed num=10]</code></td>
                </tr>

                <tr class="sbi_table_header"><td colspan=3><?php _e("Header Options", 'instagram-feed'); ?></td></tr>
                <tr>
                    <td>showheader</td>
                    <td><?php _e("Whether to show the feed Header. 'true' or 'false'.", 'instagram-feed'); ?></td>
                    <td><code>[instagram-feed showheader=false]</code></td>
                </tr>
                <tr>
                    <td>showbio</td>
                    <td><?php _e("Display the bio in the header. 'true' or 'false'."); ?></td>
                    <td><code>[instagram-feed showbio=true]</code></td>
                </tr>
            </tbody>
        </table>

        <p><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php _e('Need help setting up the plugin? Check out our <a href="https://smashballoon.com/instagram-feed/free/" target="_blank">setup directions</a>', 'instagram-feed'); ?></p>

    <?php } //End Display tab ?>


    <?php if ($sbi_active_tab == 'support') { //Start Support tab ?>

        <div class="sbi_support">

            <br/>
            <h3 style="padding-bottom: 10px;">Need help?</h3>

            <p>
                <span class="sbi-support-title"><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <a
                        href="https://smashballoon.com/instagram-feed/free/"
                        target="_blank"><?php _e('Setup Directions'); ?></a></span>
                <?php _e('A step-by-step guide on how to setup and use the plugin.'); ?>
            </p>

            <p>
                <span class="sbi-support-title"><i class="fa fa-youtube-play" aria-hidden="true"></i>&nbsp; <a
                        href="https://www.youtube.com/embed/V_fJ_vhvQXM" target="_blank"
                        id="sbi-play-support-video"><?php _e('Watch a Video'); ?></a></span>
                <?php _e( "Watch a short video demonstrating how to set up, customize and use the plugin.<br>" ); ?>

                <iframe id="sbi-support-video"
                        src="//www.youtube.com/embed/V_fJ_vhvQXM?theme=light&amp;showinfo=0&amp;controls=2" width="960"
                        height="540" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
            </p>

            <p>
                <span class="sbi-support-title"><i class="fa fa-question-circle" aria-hidden="true"></i>&nbsp; <a
                        href="https://smashballoon.com/instagram-feed/support/faq/"
                        target="_blank"><?php _e('FAQs and Docs'); ?></a></span>
                <?php _e('View our expansive library of FAQs and documentation to help solve your problem as quickly as possible.'); ?>
            </p>

            <div class="sbi-support-faqs">

                <ul>
                    <li><b>FAQs</b></li>
                    <li>&bull;&nbsp; <?php _e('<a href="https://smashballoon.com/instagram-feed/find-instagram-user-id/" target="_blank">How to find an Instagram User ID</a>'); ?></li>
                    <li>&bull;&nbsp; <?php _e('<a href="https://smashballoon.com/my-instagram-access-token-keep-expiring/" target="_blank">My Access Token Keeps Expiring</a>'); ?></li>
                    <li>&bull;&nbsp; <?php _e('<a href="https://smashballoon.com/my-photos-wont-load/" target="_blank">My Instagram Feed Won\'t Load</a>'); ?></li>
                    <li style="margin-top: 8px; font-size: 12px;"><a
                            href="https://smashballoon.com/instagram-feed/support/faq/" target="_blank">See All<i
                                class="fa fa-chevron-right" aria-hidden="true"></i></a></li>
                </ul>

                <ul>
                    <li><b>Documentation</b></li>
                    <li>&bull;&nbsp; <?php _e('<a href="https://smashballoon.com/instagram-feed/free" target="_blank">Installation and Configuration</a>'); ?></li>
                    <li>&bull;&nbsp; <?php _e('<a href="https://smashballoon.com/display-multiple-instagram-feeds/" target="_blank">Displaying multiple feeds</a>'); ?></li>
                    <li>&bull;&nbsp; <?php _e('<a href="https://smashballoon.com/instagram-feed-faq/customization/" target="_blank">Customizing your Feed</a>'); ?></li>
                </ul>
            </div>

            <p>
                <span class="sbi-support-title"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp; <a
                        href="https://smashballoon.com/instagram-feed/support/"
                        target="_blank"><?php _e('Request Support'); ?></a></span>
                <?php _e('Still need help? Submit a ticket and one of our support experts will get back to you as soon as possible.<br /><b>Important:</b> Please include your <b>System Info</b> below with all support requests.'); ?>
            </p>
        </div>

        <hr />

        <h3><?php _e('System Info &nbsp; <i style="color: #666; font-size: 11px; font-weight: normal;">Click the text below to select all</i>'); ?></h3>




        <?php $sbi_options = get_option('sb_instagram_settings'); ?>
        <textarea readonly="readonly" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)." style="width: 100%; max-width: 960px; height: 500px; white-space: pre; font-family: Menlo,Monaco,monospace;">
## SITE/SERVER INFO: ##
Site URL:                 <?php echo site_url() . "\n"; ?>
Home URL:                 <?php echo home_url() . "\n"; ?>
WordPress Version:        <?php echo get_bloginfo('version') . "\n"; ?>
PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

## ACTIVE PLUGINS: ##
<?php
$plugins = get_plugins();
$active_plugins = get_option('active_plugins', [] );

foreach ($plugins as $plugin_path => $plugin) {
    // If the plugin isn't active, don't show it.
    if (! in_array($plugin_path, $active_plugins ) )
        continue;

    echo $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
}
?>

## PLUGIN SETTINGS: ##
sb_instagram_plugin_type => Instagram Feed Free
<?php 
while (list($key, $val) = each($sbi_options)) {
    echo "$key => $val\n";
}
?>

## API RESPONSE: ##
<?php
$url = isset($sbi_options['sb_instagram_at'] ) ? 'https://api.instagram.com/v1/users/self/?access_token=' . $sbi_options['sb_instagram_at'] : 'no_at';
if ($url !== 'no_at') {
    $args = [
        'timeout' => 60,
        'sslverify' => false
    ];
    $result = wp_remote_get($url, $args );

    $data = json_decode($result['body'] );

    if (isset($data->data->id )) {
        echo 'id: ' . $data->data->id . "\n";
        echo 'username: ' . $data->data->username . "\n";
        echo 'posts: ' . $data->data->counts->media . "\n";

        $url = 'https://api.instagram.com/v1/users/13460080?access_token=' . $sbi_options['sb_instagram_at'];
        $args = [
            'timeout' => 60,
            'sslverify' => false
        ];
        $search_result = wp_remote_get($url, $args );
        $search_data = json_decode($search_result['body'] );

        if (isset($data->meta->code )) {
            echo "\n" . 'Instagram Response' . "\n";
            echo 'code: ' . $search_data->meta->code . "\n";
            if (isset($search_data->meta->error_message )) {
                echo 'error_message: ' . $search_data->meta->error_message . "\n";
            }
        }

    } else {
        echo 'No id returned' . "\n";
        echo 'code: ' . $data->meta->code . "\n";
        if (isset($data->meta->error_message )) {
            echo 'error_message: ' . $data->meta->error_message . "\n";
        }
    }

} else {
    echo 'No Access Token';
}?>
        </textarea>

<?php 
} //End Support tab 
?>

</div> <!-- end #sbi_admin -->

<?php } //End Settings page

function sb_instagram_admin_style() {
        wp_register_style('sb_instagram_admin_css', plugins_url('css/sb-instagram-admin.css', __FILE__), [], SBIVER );
        wp_enqueue_style('sb_instagram_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
        wp_enqueue_style('sb_instagram_admin_css');
        wp_enqueue_style('wp-color-picker');
}
add_action('admin_enqueue_scripts', 'sb_instagram_admin_style');

function sb_instagram_admin_scripts() {
    wp_enqueue_script('sb_instagram_admin_js', plugins_url('js/sb-instagram-admin.js' , __FILE__ ), [], SBIVER );
    wp_localize_script('sb_instagram_admin_js', 'sbiA', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'sbi_nonce' => wp_create_nonce('sbi-smash-balloon')
        ]
    );
    if (!wp_script_is('jquery-ui-draggable')) {
        wp_enqueue_script([
            'jquery',
            'jquery-ui-core',
            'jquery-ui-draggable'
        ]);
    }
    wp_enqueue_script(['hoverIntent']);
}
add_action('admin_enqueue_scripts', 'sb_instagram_admin_scripts');

/**
 * Called via ajax to automatically save access token and access token secret
 * retrieved with the big blue button
 */
function sbi_auto_save_tokens() {
    if (current_user_can('edit_posts')) {
        wp_cache_delete ('alloptions', 'options');

        $options = get_option('sb_instagram_settings', []);
        $options['sb_instagram_at'] = isset($_POST['access_token'] ) ? sanitize_text_field($_POST['access_token'] ) : '';

        update_option('sb_instagram_settings', $options, true);
        echo $_POST['access_token'];
    }
    die();
}
add_action('wp_ajax_sbi_auto_save_tokens', 'sbi_auto_save_tokens');
