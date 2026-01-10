<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

namespace CE;

defined('_PS_VERSION_') or die;

require_once _CE_PATH_ . 'classes/CERevision.php';
require_once _CE_PATH_ . 'classes/wrappers/UId.php';
require_once _CE_PATH_ . 'classes/wrappers/Post.php';

class Helper
{
    public static $actions = array();

    public static $filters = array();

    public static $styles = array();

    public static $scripts = array();

    public static $head_styles = array();

    public static $head_scripts = array();

    public static $body_scripts = array();

    public static $enqueue_css = array();

    public static $productHooks = array(
        'displayfooterproduct',
        'displayproductadditionalinfo',
        'displayproductlistreviews',
        'displayproductpriceblock',
        'displayafterproductthumbs',
        'displayleftcolumnproduct',
        'displayrightcolumnproduct',
    );

    public static function getAjaxLink()
    {
        return preg_replace('/\buid=\d+(&footerProduct=\d+)?/', 'ajax=1', $_SERVER['REQUEST_URI']);
    }

    public static function getMediaLink($url, $full = false)
    {
        if ($url && !preg_match('~^(https?:)?//~i', $url)) {
            $url = __PS_BASE_URI__ . $url;

            if (_MEDIA_SERVER_1_ || $full) {
                $url = \Context::getContext()->link->getMediaLink($url);
            }
        }
        return $url;
    }

    public static function getPageLayouts()
    {
        $context = \Context::getContext();

        if (!empty($context->shop->theme)) {
            $theme_settings = $context->shop->theme->get('theme_settings');

            return $theme_settings['layouts'];
        }
        $layouts = array();
        $prefix = _DB_PREFIX_;
        $id_theme = (int) $context->shop->id;
        $pages = \Db::getInstance()->executeS(
            "SELECT m.`page`, tm.`left_column`, tm.`right_column`
            FROM {$prefix}theme_meta as tm
            INNER JOIN {$prefix}meta m ON m.`id_meta` = tm.`id_meta`
            WHERE tm.`id_theme` = $id_theme"
        );
        if (!empty($pages)) {
            $map = array(
                '00' => 'layout-one-column',
                '01' => 'layout-right-column',
                '10' => 'layout-left-column',
                '11' => 'layout-both-columns',
            );
            foreach ($pages as &$page) {
                $layouts[$page['page']] = $map[$page['left_column'] . $page['right_column']];
            }
        }

        return $layouts;
    }

    public static function filterTheContent($content)
    {
        static $editor = null;

        // Edit with CE
        if (null === $editor && $editor = esc_attr(self::getEditorLink())) {
            $title = esc_attr(_x('Edit with Creative Elements', 'backoffice_header', 'elementor'));

            $src = esc_attr(_CE_ASSETS_URL_ . 'js/frontend-edit.js?v=' . _CE_VERSION_);
            $content = "\x3Cscript src=\"$src\" data-ce-editor=\"$editor\" data-ce-title=\"$title\"\x3E\x3C/script\x3E" . $content;

            $href = esc_attr(_CE_ASSETS_URL_ . 'css/frontend-edit.css?v=' . _CE_VERSION_);
            Helper::$enqueue_css[] = "\x3Clink rel=\"stylesheet\" href=\"$href\"\x3E";
        }
        return self::getCleanCss() . $content;
    }

    private static function getCleanCss()
    {
        static $global;
        $css = '';

        if (null === $global) {
            $global = true;
            $scheme_css_file = new GlobalCSSFile();

            ob_start();
            $scheme_css_file->enqueue();
            
            $css = ob_get_clean();
        }
        Plugin::$instance->frontend->printGoogleFonts();
        while (!empty(Helper::$enqueue_css)) {
            $css .= array_pop(Helper::$enqueue_css);
        }
        return $css;
    }

    private static function getEditorLink()
    {
        static $link;

        if (null === $link) {
            $link = '';

            if (\Configuration::get('elementor_frontend_edit') &&
                ($id_employee = self::getEmployeeId()) &&
                ($dir = glob(_PS_ROOT_DIR_ . '/*/filemanager', GLOB_ONLYDIR))
            ) {
                $tab = 'AdminCEEditor';
                $link = __PS_BASE_URI__ . basename(dirname($dir[0])) . '/index.php?' . http_build_query(array(
                    'controller' => $tab,
                    'token' => \Tools::getAdminToken($tab . (int) \Tab::getIdFromClassName($tab) . $id_employee),
                ));
            }
        }
        return $link;
    }

    private static function getEmployeeId()
    {
        static $id_employee;

        if (null === $id_employee) {
            $lifetime = max((int) \Configuration::get('PS_COOKIE_LIFETIME_BO'), 1);
            $cookie = new \Cookie('psAdmin', '', time() + $lifetime * 3600);
            $id_employee = !empty($cookie->id_employee) ? (int) $cookie->id_employee : 0;
        }
        return $id_employee;
    }

    public static function transError($error)
    {
        return _CE_PS16_
            ? \Tools::displayError($error, false)
            : \Context::getContext()->getTranslator()->trans($error, array(), 'Admin.Notifications.Error')
        ;
    }

    public static function clearCSS()
    {
        \Configuration::deleteFromContext('_elementor_global_css');

        $db = \Db::getInstance();
        $table = _DB_PREFIX_ . 'ce_meta';

        foreach (\Shop::getContextListShopID() as $id_shop) {
            $id = sprintf('%02d', $id_shop);

            $db->execute("DELETE FROM $table WHERE id LIKE '%$id' AND name = '_elementor_css'");

            $css_files = glob(_CE_PATH_ . "views/css/ce/*$id.css", GLOB_NOSORT);

            foreach ($css_files as $css_file) {
                \Tools::deleteFile($css_file);
            }
        }
    }
}

function add_action($tag, $callback, $priority = 10, $accepted_args = 1)
{
    $p = (int) $priority;

    isset(Helper::$actions[$tag]) or Helper::$actions[$tag] = array(10 => array());
    isset(Helper::$actions[$tag][$p]) or Helper::$actions[$tag][$p] = array();

    if (is_string($callback) && '\\' !== $callback[0]) {
        $callback = '\\' . __NAMESPACE__ . '\\' . $callback;
    }
    if (is_array($callback) && !empty($callback[1]) && stripos($callback[1], '_') !== false) {
        $callback[1] = \Tools::toCamelCase($callback[1]);
    }
    Helper::$actions[$tag][$p][] = $callback;
}

function do_action($tag, $arg = '')
{
    if (isset(Helper::$actions[$tag])) {
        $actions = &Helper::$actions[$tag];

        $args = func_get_args();
        array_shift($args);

        $priorities = array_keys($actions);
        sort($priorities, SORT_NUMERIC);

        foreach ($priorities as $p) {
            foreach ($actions[$p] as $callback) {
                call_user_func_array($callback, $args);
            }
        }
    }
}

function do_action_ref_array($tag, array $args = array())
{
    array_unshift($args, $tag);
    call_user_func_array(__NAMESPACE__ . '\do_action', $args);
}

function add_filter($tag, $callback, $priority = 10, $accepted_args = 1)
{
    $p = (int) $priority;

    isset(Helper::$filters[$tag]) or Helper::$filters[$tag] = array(10 => array());
    isset(Helper::$filters[$tag][$p]) or Helper::$filters[$tag][$p] = array();

    if (is_string($callback) && '\\' !== $callback[0]) {
        $callback = '\\' . __NAMESPACE__ . '\\' . $callback;
    }
    if (is_array($callback) && !empty($callback[1]) && stripos($callback[1], '_') !== false) {
        $callback[1] = \Tools::toCamelCase($callback[1]);
    }
    Helper::$filters[$tag][$p][] = $callback;
}

function has_filter($tag, $function_to_check = false)
{
    if ($function_to_check) {
        throw new \RuntimeException('TODO');
    }
    return isset(Helper::$filters[$tag]);
}

function apply_filters($tag, $value)
{
    if (isset(Helper::$filters[$tag])) {
        $filters = &Helper::$filters[$tag];

        $args = func_get_args();
        array_shift($args);

        $priorities = array_keys($filters);
        sort($priorities, SORT_NUMERIC);

        foreach ($priorities as $p) {
            foreach ($filters[$p] as $callback) {
                $args[0] = call_user_func_array($callback, $args);
            }
        }
        return $args[0];
    }
    return $value;
}

function clean($cnt)
{
    return $cnt;
}

add_action('smarty/before_fetch', function ($smarty) {
    $GLOBALS['_esc'] = $smarty->escape_html;
    $smarty->escape_html = false;
    $smarty->registered_plugins['modifier']['cleanHtml'][0] = 'CE\clean';
}, 0);

add_action('smarty/after_fetch', function ($smarty) {
    $smarty->escape_html = $GLOBALS['_esc'];
    $smarty->registered_plugins['modifier']['cleanHtml'][0] = 'smartyCleanHtml';
}, 0);

add_action('smarty/before_call', function ($smarty) {
    $smarty->registered_plugins['modifier']['cleanHtml'][0] = 'CE\clean';
}, 0);

add_action('smarty/after_call', function ($smarty) {
    $smarty->registered_plugins['modifier']['cleanHtml'][0] = 'smartyCleanHtml';
}, 0);

function wp_register_style($handle, $src, $deps = array(), $ver = false, $media = 'all')
{
    if (!isset(Helper::$styles[$handle])) {
        Helper::$styles[$handle] = array(
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'media' => $media,
        );
    }
    return true;
}

function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all')
{
    empty($src) or wp_register_style($handle, $src, $deps, $ver, $media);

    if (!empty(Helper::$styles[$handle]) && empty(Helper::$head_styles[$handle])) {
        foreach (Helper::$styles[$handle]['deps'] as $dep) {
            wp_enqueue_style($dep);
        }

        Helper::$head_styles[$handle] = &Helper::$styles[$handle];
        unset(Helper::$styles[$handle]);
    }
}

function wp_register_script($handle, $src, $deps = array(), $ver = false, $in_footer = false)
{
    if (!isset(Helper::$scripts[$handle])) {
        Helper::$scripts[$handle] = array(
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'head' => !$in_footer,
            'l10n' => array(),
        );
    }
    return true;
}

function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false)
{
    empty($src) or wp_register_script($handle, $src, $deps, $ver, $in_footer);

    if (!empty(Helper::$scripts[$handle]) && empty(Helper::$head_scripts[$handle]) && empty(Helper::$body_scripts[$handle])) {
        foreach (Helper::$scripts[$handle]['deps'] as $dep) {
            wp_enqueue_script($dep);
        }

        if (Helper::$scripts[$handle]['head']) {
            Helper::$head_scripts[$handle] = &Helper::$scripts[$handle];
        } else {
            Helper::$body_scripts[$handle] = &Helper::$scripts[$handle];
        }
        unset(Helper::$scripts[$handle]);
    }
}

function wp_localize_script($handle, $object_name, $l10n)
{
    if (isset(Helper::$scripts[$handle])) {
        Helper::$scripts[$handle]['l10n'][$object_name] = $l10n;
    } elseif (isset(Helper::$head_scripts[$handle])) {
        Helper::$head_scripts[$handle]['l10n'][$object_name] = $l10n;
    } elseif (isset(Helper::$body_scripts[$handle])) {
        Helper::$body_scripts[$handle]['l10n'][$object_name] = $l10n;
    }
}

function wp_enqueue_scripts()
{
    wp_enqueue_script('jquery', _PS_JS_DIR_ . 'jquery/jquery-1.11.0.min.js');
    wp_enqueue_script('jquery-ui', _CE_ASSETS_URL_ . 'lib/jquery/jquery-ui.min.js', array('jquery'), '1.11.4', true);

    wp_register_script('underscore', _CE_ASSETS_URL_ . 'lib/underscore/underscore.min.js', array(), '1.8.3', true);
    wp_register_script('backbone', _CE_ASSETS_URL_ . 'lib/backbone/backbone.min.js', array('jquery', 'underscore'), '1.4.0', true);

    do_action('wp_enqueue_scripts');
}

function wp_print_styles()
{
    while ($args = array_shift(Helper::$head_styles)) {
        if ($args['ver']) {
            $args['src'] .= (\Tools::strpos($args['src'], '?') === false ? '?' : '&') . 'v=' . $args['ver'];
        }
        echo "\x3Clink rel=\"stylesheet\" href=\"{$args['src']}\" media=\"{$args['media']}\" /\x3E\n";
    }
}

function wp_print_head_scripts()
{
    while ($args = array_shift(Helper::$head_scripts)) {
        _print_script($args);
    }
}

function wp_print_footer_scripts()
{
    while ($args = array_shift(Helper::$body_scripts)) {
        _print_script($args);
    }
}

function _print_script(&$args)
{
    if (!empty($args['l10n'])) {
        echo "\x3Cscript\x3E\n";
        foreach ($args['l10n'] as $key => &$value) {
            $json = json_encode($value);

            if ('ElementorConfig' === $key) {
                // fix for line too long
                echo "var $key = " . str_replace('}},"', "}},\n\"", $json) . ";\n";
            } else {
                echo "var $key = $json;\n";
            }
        }
        echo "\x3C/script\x3E\n";
    }
    if (!empty($args['ver'])) {
        $args['src'] .= (\Tools::strpos($args['src'], '?') === false ? '?' : '&') . 'v=' . $args['ver'];
    }
    if (!empty($args['src'])) {
        echo "\x3Cscript src=\"{$args['src']}\"\x3E\x3C/script\x3E\n";
    }
}

function set_transient($transient, $value, $expiration = 0)
{
    $expiration = (int) $expiration;
    $tr_timeout = '_tr_to_' . $transient;
    $tr_option = '_tr_' . $transient;
    $id_shop = \Context::getContext()->shop->id;

    if (false === get_post_meta($id_shop, $tr_option, true)) {
        if ($expiration) {
            update_post_meta($id_shop, $tr_timeout, time() + $expiration);
        }
        $result = update_post_meta($id_shop, $tr_option, $value);
    } else {
        $update = true;
        if ($expiration) {
            if (false === get_post_meta($id_shop, $tr_timeout, true)) {
                update_post_meta($id_shop, $tr_timeout, time() + $expiration);
                $result = update_post_meta($id_shop, $tr_option, $value);
                $update = false;
            } else {
                update_post_meta($id_shop, $tr_timeout, time() + $expiration);
            }
        }
        if ($update) {
            $result = update_post_meta($id_shop, $tr_option, $value);
        }
    }

    return $result;
}

function get_transient($transient)
{
    $tr_option = '_tr_' . $transient;
    $tr_timeout = '_tr_to_' . $transient;
    $id_shop = \Context::getContext()->shop->id;
    $timeout = get_post_meta($id_shop, $tr_timeout, true);

    if (false !== $timeout && $timeout < time()) {
        delete_option($tr_option);
        delete_option($tr_timeout);
        return false;
    }
    return get_post_meta($id_shop, $tr_option, true);
}

define('_CE_ENGLISH_', \Tools::getIsset('en'));

function _e($text, $domain = 'elementor')
{
    echo translate($text, $domain);
}

function __($text, $domain = 'elementor')
{
    return translate($text);
}

function _x($text, $ctx, $domain = 'elementor')
{
    return translate($text, $domain, $ctx);
}

function _n($single, $plural, $number, $domain = 'elementor')
{
    return translate($number > 1 ? $plural : $single, $domain);
}

function translate($text, $domain = 'elementor', $ctx = '')
{
    $mod = 'elementor' === $domain ? 'creativeelements' : $domain;
    $src = $ctx ? str_replace(' ', '_', \Tools::strtolower($ctx)) : '';

    return _CE_ENGLISH_ ? $text : call_user_func('strip' . 'slashes', \Translate::getModuleTranslation($mod, $text, $src, null, true));
}

function esc_attr_e($text, $domain = 'elementor')
{
    $mod = 'elementor' === $domain ? 'creativeelements' : $domain;

    echo _CE_ENGLISH_ ? \Tools::safeOutput($text) : \Translate::getModuleTranslation($mod, $text, '');
}

function esc_attr($text)
{
    return \Tools::safeOutput($text);
}

function esc_url($url, $protocols = null, $_context = 'display')
{
    if ('' == $url) {
        return $url;
    }
    $url = str_replace(' ', '%20', $url);
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', $url);
    if ('' === $url) {
        return $url;
    }
    $url = str_replace(';//', '://', $url);
    if (strpos($url, ':') === false && !in_array($url[0], array('/', '#', '?')) &&
        !preg_match('/^[a-z0-9-]+?\.php/i', $url)) {
        $url = 'http://' . $url;
    }
    return $url;
}

function wp_send_json($response/*, $status_code = null*/)
{
    headers_sent() or header('Content-Type: application/json; charset=utf-8');

    die(json_encode($response));
}

function wp_send_json_success($data = null)
{
    $response = array('success' => true);
    if (isset($data)) {
        $response['data'] = $data;
    }
    wp_send_json($response);
}

function wp_send_json_error($data = null)
{
    $response = array('success' => false);
    if (isset($data)) {
        $response['data'] = $data;
    }
    wp_send_json($response);
}

function is_rtl()
{
    return !empty(\Context::getContext()->language->is_rtl);
}

function is_admin()
{
    return !empty(\Context::getContext()->employee->id);
}

function is_customize_preview()
{
    return \Context::getContext()->controller instanceof \CreativeElementsPreviewModuleFrontController;
}

function get_option($option, $default = false)
{
    if (false === $res = \Configuration::get($option)) {
        return $default;
    }
    return isset($res[0]) && ('{' == $res[0] || '[' == $res[0]) ? json_decode($res, true) : $res;
}

function update_option($option, $value)
{
    if (is_array($value) || is_object($value)) {
        $value = json_encode($value);
    }
    $purify = \Configuration::get('PS_USE_HTMLPURIFIER');
    empty($purify) or \Configuration::set('PS_USE_HTMLPURIFIER', 0);

    $res = \Configuration::updateValue($option, array($value), true);

    if (\Shop::CONTEXT_SHOP !== $shop_ctx = \Shop::getContext()) {
        $groups = \Shop::CONTEXT_ALL === $shop_ctx ? new \stdClass() : false;

        foreach (\Shop::getContextListShopID() as $id_shop) {
            $id_shop_group = \Shop::getGroupFromShop($id_shop);

            if ($groups && empty($groups->$id_shop_group)) {
                $groups->$id_shop_group = true;

                $res &= \Configuration::updateValue($option, array($value), true, $id_shop_group);
            }
            $res &= \Configuration::updateValue($option, array($value), true, $id_shop_group, $id_shop);
        }
    }
    empty($purify) or \Configuration::set('PS_USE_HTMLPURIFIER', 1);

    return $res;
}

function delete_option($option)
{
    return \Configuration::deleteByName($option);
}

function get_current_user_id()
{
    $ctx = \Context::getContext();
    return empty($ctx->employee->id) ? 0 : (int) $ctx->employee->id;
}

function wp_get_current_user()
{
    $user = \Context::getContext()->employee;
    return (object) array(
        'ID' => $user->id,
        'display_name' => $user->firstname . ' ' . $user->lastname,
        'roles' => array(),
    );
}

function get_user_by($field, $value)
{
    if ('id' != $field) {
        throw new \RuntimeException('TODO');
    }
    if (!\Validate::isLoadedObject($user = new \Employee($value))) {
        return false;
    }
    return (object) array(
        'ID' => $user->id,
        'display_name' => $user->firstname . ' ' . $user->lastname,
        'roles' => array(),
    );
}

function wp_set_post_lock($post_id)
{
    if (!$user_id = get_current_user_id()) {
        return false;
    }
    $now = time();

    update_post_meta($post_id, '_edit_lock', "$now:$user_id");

    return array($now, $user_id);
}

function wp_check_post_lock($post_id)
{
    if (!$lock = get_post_meta($post_id, '_edit_lock', true)) {
        return false;
    }
    list($time, $user) = explode(':', $lock);

    if (empty($user)) {
        return false;
    }
    $time_window = apply_filters('wp_check_post_lock_window', 150);

    if ($time && $time > time() - $time_window && $user != get_current_user_id()) {
        return (int) $user;
    }
    return false;
}

function wp_remote_post($url, array $args = array())
{
    $http = array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'user_agent' => $_SERVER['SERVER_SOFTWARE'],
        'content' => empty($args['body']) ? '' : http_build_query($args['body']),
        'max_redirects' => 5,
        'timeout' => empty($args['timeout']) ? 5 : $args['timeout'],
    );

    if (ini_get('allow_url_fopen')) {
        return \Tools::file_get_contents($url, false, stream_context_create(array('http' => $http)), $http['timeout']);
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_POST => 1,
        CURLOPT_HTTPHEADER => (array) $http['header'],
        CURLOPT_USERAGENT => $http['user_agent'],
        CURLOPT_POSTFIELDS => $http['content'],
        CURLOPT_MAXREDIRS => $http['max_redirects'],
        CURLOPT_TIMEOUT => $http['timeout'],
        CURLOPT_RETURNTRANSFER => 1,
    ));
    $resp = curl_exec($ch);
    curl_close($ch);

    return $resp;
}

function wp_remote_get($url, array $args = array())
{
    $http = array(
        'method' => 'GET',
        'user_agent' => $_SERVER['SERVER_SOFTWARE'],
        'max_redirects' => 5,
        'timeout' => empty($args['timeout']) ? 5 : $args['timeout'],
    );

    if (!empty($args['body'])) {
        $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($args['body']);
    }

    if (ini_get('allow_url_fopen')) {
        return \Tools::file_get_contents($url, false, stream_context_create(array('http' => $http)), $http['timeout']);
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_USERAGENT => $http['user_agent'],
        CURLOPT_MAXREDIRS => $http['max_redirects'],
        CURLOPT_TIMEOUT => $http['timeout'],
        CURLOPT_RETURNTRANSFER => 1,
    ));
    $resp = curl_exec($ch);
    curl_close($ch);

    return $resp;
}

function _doing_it_wrong($function, $message = '', $version = '')
{
    die(\Tools::displayError($function . ' was called incorrectly. ' . $message . ' ' . $version));
}

function is_wp_error($error)
{
    return $error instanceof \PrestaShopException;
}

const MINUTE_IN_SECONDS = 60;
const HOUR_IN_SECONDS = 3600;
const DAY_IN_SECONDS = 86400;
const WEEK_IN_SECONDS = 604800;
// MONTH_IN_SECONDS = 30 * DAY_IN_SECONDS
const MONTH_IN_SECONDS = 2592000;
// YEAR_IN_SECONDS = 365 * DAY_IN_SECONDS
const YEAR_IN_SECONDS = 31536000;

function human_time_diff($from, $to = '')
{
    empty($to) && $to = time();
    $diff = (int) abs($to - $from);

    if ($diff < MINUTE_IN_SECONDS) {
        $secs = $diff;
        if ($secs <= 1) {
            $secs = 1;
        }
        $since = sprintf(_n('%s sec', '%s secs', $secs), $secs);
    } elseif ($diff < HOUR_IN_SECONDS) {
        $mins = round($diff / MINUTE_IN_SECONDS);
        if ($mins <= 1) {
            $mins = 1;
        }
        $since = sprintf(_n('%s min', '%s mins', $mins), $mins);
    } elseif ($diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS) {
        $hours = round($diff / HOUR_IN_SECONDS);
        if ($hours <= 1) {
            $hours = 1;
        }
        $since = sprintf(_n('%s hour', '%s hours', $hours), $hours);
    } elseif ($diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS) {
        $days = round($diff / DAY_IN_SECONDS);
        if ($days <= 1) {
            $days = 1;
        }
        $since = sprintf(_n('%s day', '%s days', $days), $days);
    } elseif ($diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS) {
        $weeks = round($diff / WEEK_IN_SECONDS);
        if ($weeks <= 1) {
            $weeks = 1;
        }
        $since = sprintf(_n('%s week', '%s weeks', $weeks), $weeks);
    } elseif ($diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS) {
        $months = round($diff / MONTH_IN_SECONDS);
        if ($months <= 1) {
            $months = 1;
        }
        $since = sprintf(_n('%s month', '%s months', $months), $months);
    } elseif ($diff >= YEAR_IN_SECONDS) {
        $years = round($diff / YEAR_IN_SECONDS);
        if ($years <= 1) {
            $years = 1;
        }
        $since = sprintf(_n('%s year', '%s years', $years), $years);
    }
    return $since;
}

function add_query_arg(array $args, $url)
{
    return $url . (stripos($url, '?') === false ? '?' : '&') . http_build_query($args);
}

function do_shortcode($content)
{
    if (false === stripos($content, '{')) {
        return $content;
    }
    return preg_replace_callback('~(<[pP]>\s*)?\{(hook|widget)[^\}]+\}(\s*</[pP]>)?~', 'CE\parse_shortcode', $content);
}

function parse_shortcode($match)
{
    if (!preg_match_all('~\s+(\w+)\s*=\s*(\w+|".*?"|\'.*?\'|\[.*?\])~', $match[0], $args, PREG_SET_ORDER)) {
        return $match[0];
    }
    $func = 'smarty' . $match[2];
    $params = array();
    $smarty = null;
    isset($match[3]) or $match[3] = '';

    foreach ($args as $arg) {
        if ('[' === $arg[2][0]) {
            $array = array();
            $count = preg_match_all(
                '~\s*,\s*(?:(\w+|".*?"|\'.*?\')\s*=>\s*)?(\w+|".*?"|\'.*?\')~',
                ',' . trim($arg[2], '[]'),
                $elems,
                PREG_SET_ORDER
            );
            if ($count) {
                foreach ($elems as $elem) {
                    $val = parse_native($elem[2]);

                    if ($elem[1]) {
                        $key = parse_native($elem[1]);

                        $array[$key] = $val;
                    } else {
                        $array[] = $val;
                    }
                }
            }
            $params[$arg[1]] = $array;
        } else {
            $params[$arg[1]] = parse_native($arg[2]);
        }
    }
    $result = $func($params, $smarty);

    return $match[1] && $match[3] ? $result : $match[1] . $result . $match[3];
}

function parse_native($native)
{
    if ("'" === $native[0]) {
        return str_replace('\\\\', '\\', trim($native, "'"));
    }
    $result = json_decode($native);

    return json_last_error() === JSON_ERROR_NONE ? $result : $native;
}
