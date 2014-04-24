<?php
/**
 * Plugin Name: GMO Ads Master
 * Plugin URI:  
 * Description: GMO Ads Master is the ad banner plugin which enables you to place ad contents to the desired locations such as inside article, sidebar and footer. In addition to that, using this plugin let you setup Google Analytics tracking code and sitemap tool settings, and sitemap can be easily generated without playing with PHP files.
 * Version:     1.0
 * Author:      WP Shop byGMO
 * Author URI:  http://www.wpshop.com
 * License:     GPLv2
 * Text Domain: gmoadsmaster
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2014 WP Shop byGMO (http://www.wpshop.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


define('GMOADSMASTER_URL',  plugins_url('', __FILE__));
define('GMOADSMASTER_PATH', dirname(__FILE__));
define('GMOADSMASTER_SITEMAP_ENDPOINT', 'sitemap');

register_activation_hook( __FILE__ , 'gmoadsmaster_activation' );
register_deactivation_hook( __FILE__ , 'gmoadsmaster_deactivation' );

add_action( 'delete_option', 'gmoadsmaster_delete_option', 10, 1 );

function gmoadsmaster_activation(){
    update_option( 'gmoadsmaster_activation', true );
    flush_rewrite_rules();
}

function gmoadsmaster_deactivation(){
    delete_option( 'gmoadsmaster_activation' );
    flush_rewrite_rules();
}

// delete_optionフックのコールバック関数
function gmoadsmaster_delete_option($option){
    if ( 'rewrite_rules' === $option && get_option('gmoadsmaster_activation') ) {
        add_rewrite_endpoint( GMOADSMASTER_SITEMAP_ENDPOINT, EP_ROOT );
    }
}


$gmoadsmaster = new GMO_Ads_Master();
$gmoadsmaster->register();

class GMO_Ads_Master {

private $version = '';
private $langs   = '';
private $num_ads = 3;

function __construct()
{
    $data = get_file_data(
        __FILE__,
        array('ver' => 'Version', 'langs' => 'Domain Path')
    );
    $this->version = $data['ver'];
    $this->langs   = $data['langs'];
}

public function register()
{
    add_action('plugins_loaded', array($this, 'plugins_loaded'));

    register_sidebar(array(
        'name'          => __('Before Contents', 'gmoadsmaster'),
        'id'            => 'before-contents',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('After Contents', 'gmoadsmaster'),
        'id'            => 'after-contents',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ));
}

public function admin_init()
{
    if (isset($_POST['gmoadsmaster']) && $_POST['gmoadsmaster']){
        if (check_admin_referer('gmoadsmaster', 'gmoadsmaster')){
            $this->save();
            wp_redirect('options-general.php?page=gmoadsmaster');
        }
    }
}

public function plugins_loaded()
{
    load_plugin_textdomain(
        'gmoadsmaster',
        false,
        dirname(plugin_basename(__FILE__)).$this->langs
    );

    add_action('admin_menu', array($this, 'admin_menu'));
    add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    add_action('admin_init', array($this, 'admin_init'));
    add_action('wp_head', array($this, 'wp_head'));
    add_action('widgets_init', array($this, 'widgets_init'));
    add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
    add_action('template_redirect', array($this, 'template_redirect'));
    add_action('init', array($this, 'init'));
    add_filter('query_vars', array($this, 'query_vars'));
    add_filter('robots_txt', array($this, 'robots_txt'));

    add_filter('the_content', array($this, 'the_content'));
}

public function robots_txt($text)
{
    $text .= "Sitemap: ".home_url('sitemap/');
    return $text;
}

public function init()
{
    add_rewrite_endpoint(GMOADSMASTER_SITEMAP_ENDPOINT, EP_ROOT);
}

public function template_redirect()
{
    global $wp_query;
    if (isset($wp_query->query[GMOADSMASTER_SITEMAP_ENDPOINT])) {
        $posts = get_posts(array(
            'post_type' => 'any',
            'post_status' => 'publish',
            'nopaging' => true,
        ));

        header('Content-Type: text/xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        global $post;
        foreach ($posts as $post) {
            setup_postdata($post);
            echo '<url>';
            echo '<loc>'.esc_url(get_permalink($post->ID)).'</loc>';
            echo '<lastmod>'.esc_html(get_the_modified_date('Y-m-d\TH:i:s+00:00')).'</lastmod>';
            echo '<priority>1.0</priority>';
            echo '</url>'."\n";
        }
        wp_reset_postdata();
        echo '</urlset>';
        exit;
    }
}

public function query_vars($vars)
{
    $vars[] = GMOADSMASTER_SITEMAP_ENDPOINT;
    return $vars;
}

public function wp_enqueue_scripts()
{
    wp_enqueue_style(
        'gmo-ads-master-style',
        plugins_url('css/gmo-ads-master.min.css', __FILE__),
        array(),
        $this->version,
        'all'
    );
}

public function the_content($contents)
{
    if (!is_singular()) {
        return $contents; // exclude when not singular
    }

    $html = '';

    $html .= '<aside id="gmoadsmaster-before-content" class="gmoadsmaster"><ul>';
    $html .= $this->get_sidebar('before-contents');
    $html .= '</ul></aside>';

    $html .= $contents . "\n";

    $html .= '<aside id="gmoadsmaster-after-content" class="gmoadsmaster"><ul>';
    $html .= $this->get_sidebar('after-contents');
    $html .= '</ul></aside>';


    return $html;
}

public function get_sidebar($index)
{
    ob_start();
    dynamic_sidebar($index);
    $sidebar_contents = ob_get_contents();
    ob_end_clean();

    return $sidebar_contents;
}

public function widgets_init()
{
    require_once(dirname(__FILE__).'/includes/gmo_ad_widget.php');
    register_widget('GMO_Ad_Widget');
}

public function wp_head()
{
    if ($this->get_option('gmoadsmaster_verification')) {
        printf(
            '<meta name="%s" content="%s" />'."\n",
            'google-site-verification',
            esc_attr($this->get_option('gmoadsmaster_verification'))
        );
    }

    if (!is_user_logged_in()) {
        if ($this->get_option('gmoadsmaster_analytics')) {
            echo $this->get_option('gmoadsmaster_analytics')."\n";
        }
    }
}

public function admin_menu()
{
    add_options_page(
        __('GMO Ads Master', 'gmoadsmaster'),
        __('GMO Ads Master', 'gmoadsmaster'),
        'publish_posts',
        'gmoadsmaster',
        array($this, 'options_page')
    );
}

public function options_page()
{
?>
<div id="gmoadsmaster" class="wrap">
<form method="post" action="<?php esc_attr($_SERVER['REQUEST_URI']); ?>">
<?php wp_nonce_field('gmoadsmaster', 'gmoadsmaster'); ?>

<h2>GMO Ads Master</h2>

<h3>Stats Settings</h3>

<table class="form-table">
<tbody>
    <tr>
        <th><?php _e('Your sitemap URL.', 'gmoadsmaster'); ?></th>
        <td>
<?php
    if ( get_option('permalink_structure') ) {
        $sitemap = home_url('sitemap');
    } else {
        $sitemap = home_url('?sitemap=1');
    }

    printf('<a href="%1$s">%1$s</a>', $sitemap);
?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Verification', 'gmoadsmaster'); ?></th>
        <td><input type="text" name="gmoadsmaster_verification" value="<?php echo esc_attr($this->get_option('gmoadsmaster_verification')); ?>" style="width:100%;"><br /><?php _e('Enter your meta key "content" value to verify your blog with <a href="https://www.google.com/webmasters/tools/home?hl=ja">Google Webmaster Tools</a>.', 'gmoadsmaster'); ?></td>
    </tr>
    <tr>
        <th><?php _e('Google Analytics Code', 'gmoadsmaster'); ?></th>
        <td><textarea name="gmoadsmaster_analytics" style="width:100%;height:10em;"><?php echo esc_textarea($this->get_option('gmoadsmaster_analytics')); ?></textarea></td>
    </tr>
</tbody>
</table>

<h3>Advertising Codes</h3>

<div class="gmoadsmaster-adcodes">
<?php
    $ads = $this->get_option('gmoadsmaster_adcodes', array());
?>
<?php for ($i = 0; $i < $this->get_num_ads(); $i++): ?>
    <?php
        if (!isset($ads[$i])) {
            $ads[$i]['name'] = '';
            $ads[$i]['html'] = '';
        }
    ?>
    <div class="gmoadsmaster-adcode" style="width: <?php echo floor(100/$this->get_num_ads()); ?>%">
        <div class="gmoadsmaster-adcode-wrap">
            <div class="gmoadsmaster-adcode-title">
                <h4><?php _e('Advertising Code', 'gmoadsmaster'); ?> (<?php echo $i + 1; ?>)</h4>
            </div>
            <div class="gmoadsmaster-adcode-code">
                <h5><?php _e('Advertising Name', 'gmoadsmaster'); ?></h5>
                <input type="text" name="gmoadsmaster_adcodes[<?php echo $i; ?>][name]" value="<?php echo esc_attr($ads[$i]['name']); ?>">
                <h5><?php _e('Advertising Code', 'gmoadsmaster'); ?></h5>
                <textarea name="gmoadsmaster_adcodes[<?php echo $i; ?>][html]"><?php echo esc_textarea($ads[$i]['html']); ?></textarea>
            </div>
        </div>
    </div>
<?php endfor; ?>
</div><!-- #gmoadsmaster_adcodes -->

<p><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e("Save Changes", "gmoadsmaster"); ?>"></p>

</form>
</div><!-- #gmoadsmaster -->
<?php
}

public function admin_enqueue_scripts()
{
    if (isset($_GET['page']) && $_GET['page'] === 'gmoadsmaster') {
        wp_enqueue_style(
            'admin-gmo-ads-master-style',
            plugins_url('css/admin-gmo-ads-master.min.css', __FILE__),
            array(),
            $this->version,
            'all'
        );
    }
}

private function get_num_ads()
{
    return apply_filters(
        'gmoadsmaster_num_ads',
        $this->num_ads
    );
}

private function save()
{
    update_option('gmoadsmaster_verification', $_POST['gmoadsmaster_verification']);
    update_option('gmoadsmaster_analytics', $_POST['gmoadsmaster_analytics']);
    update_option('gmoadsmaster_adcodes', $_POST['gmoadsmaster_adcodes']);
}

public function get_option($option, $default = null)
{
    if (is_array(get_option($option))) {
        return array_map(array($this, 'filters'), get_option($option, $default));
    } else {
        return $this->filters(get_option($option, $default));
    }
}

private function filters($value)
{
    if (is_array($value)) {
        return array_map(array($this, 'filters'), $value);
    } else {
        $value = stripslashes($value);
        $value = trim($value);
        return $value;
    }
}

} // end TestPlugin

// EOF
