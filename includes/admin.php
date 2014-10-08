<?php $plugin_file_url = plugins_url() . '/'; ?>
<div id="gmofontagent" class="wrap">

<h2>GMO Font Agent</h2>

<div id="gmoplugLeft">

<form method="post" action="<?php esc_attr($_SERVER['REQUEST_URI']); ?>">
<?php wp_nonce_field('gmofontagent', 'gmofontagent'); ?>

<!--?php if (get_option('gmofontagent_')): ?-->

<div id="tabs">

<ul>
    <li><a href="#tabs-1">Font Settings</a></li>
    <li><a href="#tabs-2">Google Fonts API key</a></li>
</ul>

<div id="tabs-1">

    <?php foreach ($this->get_default_tags() as $tag => $caption): ?>
    <?php if ($tag === 'body') $tag = '.body'; ?>
    <?php if ($tag === '.body'): ?>
    <h3>body</h3>
    <?php else: ?>
    <h3><?php echo esc_html($caption); ?></h3>
    <?php endif; ?>
    <div id="tag-<?php echo preg_replace("/^(\.|\#)/", "_", $tag); ?>" class="tag-block">
        <div class="alpha">
            <ul>
                <li>
                    <select id="fontcat-<?php echo preg_replace("/^(\.|\#)/", "_", $tag); ?>" class="fontcat" name="styles[<?php echo $tag; ?>][fontcat]" data-tag="<?php echo $tag; ?>">
                        <option value="">Select category ...</option>
                    </select>
                </li>
                <li>
                    <select id="fontname-<?php echo preg_replace("/^(\.|\#)/", "_", $tag); ?>" class="fontname" name="styles[<?php echo $tag; ?>][fontname]" data-tag="<?php echo $tag; ?>">
                        <option value="">Select font name ...</option>
                    </select>
                </li>
                <li>
                    <input id="font-size-<?php echo preg_replace("/^(\.|\#)/", "_", $tag); ?>" class="font-size" type="text" name="styles[<?php echo $tag; ?>][font-size]"
                            value="<?php $font_size = intval($this->get_style($tag, 'font-size')); if ($font_size) echo $font_size; ?>" size=4 data-tag="<?php echo $tag; ?>"> px
                </li>
                <li>
        			<p><input type="checkbox" name="styles[<?php echo $tag; ?>][font-flag]" value="true" <?php $font_flag = $this->get_style($tag, 'font-flag'); if ($font_flag) echo 'checked'; ?>>color</p>
			        <div id="color_picker_<?php echo preg_replace("/^(\.|\#)/", "_", $tag); ?>"> </div>
                    <p><input id="font-color-<?php echo preg_replace("/^(\.|\#)/", "_", $tag); ?>" class="font-color" type="text" name="styles[<?php echo $tag; ?>][font-color]" value="<?php $font_color = $this->get_style($tag, 'font-color'); if ($font_color) echo $font_color; ?>" size=10 data-tag="<?php echo $tag; ?>"></p>
                </li>
            </ul>
        </div>
        <div class="beta" data-tag="<?php echo $tag; ?>">
            <?php if (preg_match("/^\./", $tag)): ?>
            <div class="<?php echo preg_replace("/^\./", "", $tag); ?>"><?php echo $this->sample_text; ?></div>
            <?php else: ?>
            <<?php echo $tag; ?>><?php echo $this->sample_text; ?></<?php echo $tag; ?>>
            <?php endif; ?>
        </div>
    </div>
<br clear="all" />
    <?php endforeach; ?>
</div><!-- tabs-1 -->

<div id="tabs-2">

    <table class="form-table">
        <tr>
            <th scope="row">Google Fonts API key</th>
            <td><input type="text" name="apikey" value="<?php echo esc_attr(get_option('gmofontagent-apikey')); ?>">

<h4>To acquire an API key:</h4>

<ol>
  <li>Go to the <a href="https://cloud.google.com/console">Google Cloud Console</a>.</li>
  <li>Select a project.</li>
  <li>In the sidebar on the left, select <b>APIs &amp; auth</b>.

    In the displayed list of APIs, make sure the Google Fonts Developer API status is set to <b>ON</b>.

</li>
  <li>In the sidebar on the left, select <b>Registered apps</b>.</li>
  <li>Select an application.</li>
  <li>Expand the


        Browser Key or Server Key sections.


  </li>
</ol>

</td>
        </tr>
    </table>

</div><!-- tabs-2 -->

</div><!-- #tabs -->

<p>
    <input type="submit" name="submit" id="submit"
        class="button button-primary" value="<?php _e("Save Changes", "gmofontagent"); ?>">
</p>
</form>

</div><!-- #gmoplugLeft -->

<div id="gmoplugRight">
<h3>How to Use</h3>
<ul>
<li><a href="http://support.wpshop.com/?p=508" target="_blank">How to use the GMO Font Agent</a></li>
</ul>
<h3>WordPress Themes</h3>
<ul>
<li><a href="https://wordpress.org/themes/kotenhanagara" target="_blank">Kotehanagara</a></li>
<li><a href="https://wordpress.org/themes/madeini" target="_blank">Madeini</a></li>
<li><a href="https://wordpress.org/themes/azabu-juban" target="_blank">Azabu Juban</a></li>
<li><a href="http://wordpress.org/themes/de-naani" target="_blank">de naani</a></li>
</ul>
<a href="http://wpshop.com/themes?=vn_wps_fontagent" target="_blank"><img src="<?php echo ($plugin_file_url.'gmo-font-agent/images/'.'wpshop_bnr_themes.png'); ?>" alt="WPShop by GMO WordPress Themes for Everyone!"></a>
<ul><li class="bnrlink"><a href="http://wpshop.com/themes?=wps_fontagent" target="_blank">Visit WP Shop Themes</a></li></ul>
<h3>WordPress Plugins</h3>
<ul>
<li><a href="http://wordpress.org/plugins/gmo-showtime/" target="_blank">GMO Showtime</a></li>
<li><a href="http://wordpress.org/plugins/gmo-font-agent/" target="_blank">GMO Font Agent</a></li>
<li><a href="http://wordpress.org/plugins/gmo-share-connection/" target="_blank">GMO Share Connection</a></li>
<li><a href="http://wordpress.org/plugins/gmo-ads-master/" target="_blank">GMO Ads Master</a></li>
<li><a href="http://wordpress.org/plugins/gmo-page-transitions/" target="_blank">GMO Page Trasitions</a></li>
<li><a href="http://wordpress.org/plugins/gmo-go-to-top/" target="_blank">GMO Go to Top</a></li>
</ul>
<a href="http://wpshop.com/plugins?=vn_wps_fontagent" target="_blank"><img src="<?php echo ($plugin_file_url.'gmo-font-agent/images/'.'wpshop_bnr_plugins.png'); ?>" alt="WPShop by GMO WordPress Plugins for Everyone!"></a>
<ul><li class="bnrlink"><a href="http://wpshop.com/plugins?=wps_fontagent" target="_blank">Visit WP Shop Plugins</a></li></ul>
<h3>Contact Us</h3>
<a href="http://support.wpshop.com/?page_id=15" target="_blank"><img src="<?php echo ($plugin_file_url.'gmo-font-agent/images/'.'wpshop_logo.png'); ?>" alt="WPShop by GMO"></a>
</div><!-- #gmoplugRight -->

</div><!-- #gmofontagent -->

<script type="text/javascript">
var url = '<?php echo admin_url('admin-ajax.php?action=fonts'); ?>';
var settings = <?php echo json_encode(get_option('gmofontagent-styles', array())); ?>;
</script>
