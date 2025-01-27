<?php
/*
Plugin Name: 分享工具
Plugin URI: http://wordpress.org/extend/plugins/jiathis/
Description: <a href="http://www.jiathis.com/" target="_blank">JiaThis</a>是中国最大的社会化分享按钮及工具提供商，已经有超过28万家网站正在使用JiaThis高效稳定的服务。通过JiaThis分享工具，你网站的用户可以便捷的将您网站内容分享到各大社会化媒体网站，从而为网站带来更多的社会化流量。JiaThis支持多达110个国内外流行的社会化媒体网站，并且提供了强大的用户自定义接口。JiaThis不仅具有全面的数据分析功能，而且还会根据用户喜好，将用户最常用的社会化媒体排在最前面。<a href="options-general.php?page=jiathis-share.php">启用插件后，可以点击这里进行配置</a>。
Version: 1.0.2
Author: share tools.
Author URI: http://www.jiathis.com
*/

load_plugin_textdomain('jiathis');
$jiathis_share_code['code']='<!-- JiaThis Button BEGIN -->
<div id="jiathis_style_32x32">
	<a class="jiathis_button_qzone"></a>
	<a class="jiathis_button_tsina"></a>
	<a class="jiathis_button_tqq"></a>
	<a class="jiathis_button_renren"></a>
	<a class="jiathis_button_kaixin001"></a>
	<a href="http://www.jiathis.com/share/" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
	<a class="jiathis_counter_style"></a>
</div>
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- JiaThis Button END -->';

$jiathis_share_code['position'] = 2;
$jiathis_share_code['area'] = 'left';
$new_jiathis_share_code = implode('|', $jiathis_share_code);
$jiathis_code = get_option('jiathis_code');
if($jiathis_code == '') {
	update_option('jiathis_code', $new_jiathis_share_code);
}
add_filter('the_content', 'jiathis');

function jiathis($content) {
    if(is_single() || is_page()) {
		$arr1 = array('|1|left', '|1|right', '|2|left', '|2|right');
		$jiathis_code = str_replace($arr1, '', htmlspecialchars_decode(get_option('jiathis_code')));
		$arr2 = explode('|', get_option('jiathis_code'));
		if($arr2[1] == 2) {
			$content = $content.'<br />'."<div style='float:$arr2[2]'>".$jiathis_code.'</div><br /><br /><br />';
		} else {
			$content = '<br />'."<div style='float:$arr2[2]'>".$jiathis_code.'</div><br /><br /><br />'.$content;
		}
	}
	return $content;
}

add_action('plugins_loaded', 'widget_sidebar_jiathis');
function widget_sidebar_jiathis() {
    function widget_jiathis($args) {
        if(is_single() || is_page()) return;
        extract($args);
        echo $before_widget;
        echo $before_title . __('JiaThis分享工具', 'jiathis') . $after_title;
	    echo '<div style="margin:10px 0">';
	    echo htmlspecialchars_decode(get_option("jiathis_code")) . '</div>';
        echo $after_widget;
    }
    register_sidebar_widget(__('JiaThis分享工具', 'jiathis'), 'widget_jiathis');
}

add_action('admin_menu', 'jiathis_menu');
function jiathis_menu() {
    add_options_page(__('jiathis选项', 'jiathis'), __('JiaThis分享工具', 'jiathis'), 8, basename(__FILE__), 'jiathis_options');
}
function jiathis_options() {
	$updated = false;
    if($_POST['jiathis_code'] != '') {
		if($_POST['share_pos'] != '') {
			$jiathis_share_code['code'] = stripslashes_deep($_POST['jiathis_code']);
			$arr = explode('|', $_POST['share_pos']);
			$jiathis_share_code['position'] = $arr[0];
			$jiathis_share_code['area'] = $arr[1];
			$new_jiathis_share_code = implode('|', $jiathis_share_code);
			update_option('jiathis_code', $new_jiathis_share_code);
			$updated = true;
		}
    }
	$arr1 = array('|1|left', '|1|right', '|2|left', '|2|right');
	$arr2 = explode('|', get_option('jiathis_code'));
	$jiathis_code = str_replace($arr1, '', get_option('jiathis_code'));
    echo '<div class="wrap">';
    echo '<form name="jiathis_form" method="post" action="">';
    echo '<p style="font-weight:bold;">jiaThis分享代码（请从<a href="http://www.jiathis.com/" target="_blank">JiaThis官网</a>获取）:</p>';
    echo '<p><span style="color:#000">JiaThis分享按钮主要分为：侧栏式、按钮式、工具式和图标式。默认嵌入的是"大图标"代码，显示在文章内容页的下面。<a href="http://www.jiathis.com/getcode/" target="_blank"><u>如果您想更换按钮风格，请点击这里到JiaThis官网获取新代码</u></a>。如果您需要对网站的分享数据进行追踪与分析，只需要到JiaThis<a href="http://www.jiathis.com/register" target="_blank"><u>免费注册</u></a>并重新获取代码嵌入这里即可。</span></p>';
    if($updated) {
		echo '<div class="updated settings-error" id="setting-error-settings_updated"><p><strong>JiaThis分享代码已经成功保存。</strong></p></div>';
    }
    echo '<p><textarea style="height:250px;width:700px" name="jiathis_code">' . $jiathis_code . '</textarea></p>';
	echo '文章头部 ：&nbsp;&nbsp;&nbsp;';
	echo '<input type="radio" name="share_pos" value="1|left" ' . ($arr2[2] == 'left' && $arr2[1] == 1 ? 'checked="checked"' : '') . ' /> 居左&nbsp;&nbsp;';
	echo '<input type="radio" name="share_pos" value="1|right" ' . ($arr2[2] == 'right' && $arr2[1] == 1 ? 'checked="checked"' : '') . ' /> 居右&nbsp;';
	echo '<br /><br />';
	echo '文章尾部 ：&nbsp;&nbsp;&nbsp;';
	echo '<input type="radio" name="share_pos" value="2|left" ' . ($arr2[2] == 'left' && $arr2[1] == 2 ? 'checked="checked"' : '') . ' /> 居左&nbsp;&nbsp;';
	echo '<input type="radio" name="share_pos" value="2|right" ' . ($arr2[2] == 'right' && $arr2[1] == 2 ? 'checked="checked"' : '') . ' /> 居右&nbsp;';
	echo '<p class="submit"><input type="submit" value="确认提交"/>';
	echo '<input type="button" value="返回" onclick="window.location.href=\'plugins.php\';" /></p>';
	echo '</form>';
	echo '</div>';
}