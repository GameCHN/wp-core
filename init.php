<?php
/**
 * @package Hello_Dolly
 * @version 1.6
 */
/*
Plugin Name: INIT
Plugin URI:
Description: System Init
Author: Mo
Version: 1.1
Author URI: http://mo.sh/
*/

//register_theme_directory(__DIR__);




//add_filter('theme_root',function($theme_root){
//    return __DIR__;
//});

//add_filter('theme_root_uri',function($theme_root_uri = '', $siteurl = '', $stylesheet_or_template = ''){
//    kd($theme_root_uri, $siteurl, $stylesheet_or_template);
//    return content_url();
//});

show_admin_bar(false);


// 上传图片时把绝对地址修改成相对地址(禁用,导致上传文件http错误)
//add_filter('wp_handle_upload', function ($fileInfos){
//    global $blog_id;
//    $path = get_blog_option($blog_id, 'siteurl');
//
//    $fileInfos['url'] = str_replace($path, '', $fileInfos['url']);
//
//    return $fileInfos;
//});

add_action("user_register", function ($user_id) {
    update_user_meta($user_id, 'show_admin_bar_front', false);
    //update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );
}, 10, 1);





//add_filter('plugins_url',function($url, $path, $plugin){
//    //kd([$url,$path,$plugin]);
//});

//kd(get_template_directory());

add_filter('gettext_with_context', 'wpjam_disable_google_fonts', 888, 4);
function wpjam_disable_google_fonts($translations, $text, $context, $domain)
{
    $google_fonts_contexts = array('Open Sans font: on or off', 'Lato font: on or off', 'Source Sans Pro font: on or off', 'Bitter font: on or off');
    if ($text == 'on' && in_array($context, $google_fonts_contexts)) {
        $translations = 'off';
    }

    return $translations;
}



/**
 * @see http://www.wpdaxue.com/set-default-admin-color-scheme-for-new-users.html
 */
//对非管理员移除配色方案设置选项
//if (!current_user_can('manage_options')) {
//    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
//}



//为新用户预设默认的后台配色方案
function set_default_admin_color($user_id)
{
    $args = array(
        'ID'          => $user_id,
        'admin_color' => 'midnight'
    );
    wp_update_user($args);
}

//return;
add_action('user_register', 'set_default_admin_color');

add_filter('upload_dir', function ($uploads) {
    //kd($uploads);
    $upload_path = 'uploads/media';
    $upload_url_path = '/data/uploads/media';

    if (empty($upload_path) || 'wp-content/uploads' == $upload_path) {
        $uploads['basedir'] = WP_CONTENT_DIR . '/uploads';
    } elseif (0 !== strpos($upload_path, WP_CONTENT_DIR)) {
        $uploads['basedir'] = path_join(WP_CONTENT_DIR, $upload_path);
    } else {
        $uploads['basedir'] = $upload_path;
    }

    $uploads['basedir'] = ROOT.'/data/uploads/media';

    $uploads['path'] = $uploads['basedir'] . $uploads['subdir'];

    if ($upload_url_path) {
        $uploads['baseurl'] = $upload_url_path;
        $uploads['url'] = $uploads['baseurl'] . $uploads['subdir'];
    }

    return $uploads;
});

//解决上传文件名中文乱码问题
add_filter('sanitize_file_name', function ($filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $newname = date('YmdHis') . floor(microtime() * 1000) . substr(md5($filename), 16) . ($ext ? ".$ext" : '');
    return $newname;

}, 10);

show_admin_bar(false);



/**
 * @see http://zmingcx.com/wordpress-4-2-edition-problem.html
 */

function init_smilies()
{
    global $wpsmiliestrans;
    $wpsmiliestrans = array(
        ':mrgreen:' => 'icon_mrgreen.gif',
        ':neutral:' => 'icon_neutral.gif',
        ':twisted:' => 'icon_twisted.gif',
        ':arrow:'   => 'icon_arrow.gif',
        ':shock:'   => 'icon_eek.gif',
        ':smile:'   => 'icon_smile.gif',
        ':???:'     => 'icon_confused.gif',
        ':cool:'    => 'icon_cool.gif',
        ':evil:'    => 'icon_evil.gif',
        ':grin:'    => 'icon_biggrin.gif',
        ':idea:'    => 'icon_idea.gif',
        ':oops:'    => 'icon_redface.gif',
        ':razz:'    => 'icon_razz.gif',
        ':roll:'    => 'icon_rolleyes.gif',
        ':wink:'    => 'icon_wink.gif',
        ':cry:'     => 'icon_cry.gif',
        ':eek:'     => 'icon_surprised.gif',
        ':lol:'     => 'icon_lol.gif',
        ':mad:'     => 'icon_mad.gif',
        ':sad:'     => 'icon_sad.gif',
        '8-)'       => 'icon_cool.gif',
        '8-O'       => 'icon_eek.gif',
        ':-('       => 'icon_sad.gif',
        ':-)'       => 'icon_smile.gif',
        ':-?'       => 'icon_confused.gif',
        ':-D'       => 'icon_biggrin.gif',
        ':-P'       => 'icon_razz.gif',
        ':-o'       => 'icon_surprised.gif',
        ':-x'       => 'icon_mad.gif',
        ':-|'       => 'icon_neutral.gif',
        ';-)'       => 'icon_wink.gif',
        '8O'        => 'icon_eek.gif',
        ':('        => 'icon_sad.gif',
        ':)'        => 'icon_smile.gif',
        ':?'        => 'icon_confused.gif',
        ':D'        => 'icon_biggrin.gif',
        ':P'        => 'icon_razz.gif',
        ':o'        => 'icon_surprised.gif',
        ':x'        => 'icon_mad.gif',
        ':|'        => 'icon_neutral.gif',
        ';)'        => 'icon_wink.gif',
        ':!:'       => 'icon_exclaim.gif',
        ':?:'       => 'icon_question.gif',
    );
}

add_action('init', 'init_smilies', 5);

//require __DIR__.'/extra/remove-category-parents.php';


/** @type \YCMS\Modules\Module $module */
foreach (\Modules::all() as $module) {
    \register_theme_directory(dirname($module->getPath()));
}
View::addLocation(realpath(get_template_directory(). '/Resources/views') ?: get_template_directory());
