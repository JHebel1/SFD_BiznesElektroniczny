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

/**
 * Unique Identifier
 */
class UId
{
    const REVISION = 0;
    const TEMPLATE = 1;
    const CONTENT = 2;
    const PRODUCT = 3;
    const CATEGORY = 4;
    const MANUFACTURER = 5;
    const SUPPLIER = 6;
    const CMS = 7;
    const CMS_CATEGORY = 8;
    const YBC_BLOG_POST = 9;
    const XIPBLOG_POST = 10;
    const STBLOG_POST = 11;
    const ADVANCEBLOG_POST = 12;
    const PRESTABLOG_POST = 13;
    const SIMPLEBLOG_POST = 14;
    const PSBLOG_POST = 15;
    const HIBLOG_POST = 16;

    public $id;
    public $id_type;
    public $id_lang;
    public $id_shop;

    private static $models = array(
        'CERevision',
        'CETemplate',
        'CEContent',
        'Product',
        'Category',
        'Manufacturer',
        'Supplier',
        'CMS',
        'CMSCategory',
        'Ybc_blog_post_class',
        'XipPostsClass',
        'StBlogClass',
        'BlogPosts',
        'NewsClass',
        'SimpleBlogPost',
        'PsBlogBlog',
        'HiBlogPost',
    );
    private static $admins = array(
        'AdminCEEditor',
        'AdminCETemplates',
        'AdminCEContent',
        'AdminProducts',
        'AdminCategories',
        'AdminManufacturers',
        'AdminSuppliers',
        'AdminCmsContent',
        'AdminCmsContent',
        'AdminModules',
        'AdminXipPost',
        'AdminStBlog',
        'AdminBlogPosts',
        'AdminModules',
        'AdminSimpleBlogPosts',
        'AdminPsblogBlogs',
        'AdminModules',
    );
    private static $modules = array(
        self::YBC_BLOG_POST => 'ybc_blog',
        self::XIPBLOG_POST => 'xipblog',
        self::STBLOG_POST => 'stblog',
        self::ADVANCEBLOG_POST => 'advanceblog',
        self::PRESTABLOG_POST => 'prestablog',
        self::SIMPLEBLOG_POST => 'ph_simpleblog',
        self::PSBLOG_POST => 'psblog',
        self::HIBLOG_POST => 'hiblog',
    );
    private static $shop_ids = array();

    public static $_ID;

    public function __construct($id, $id_type, $id_lang = null, $id_shop = null)
    {
        $this->id = abs((int) $id);
        $this->id_type = abs($id_type % 100);

        if ($this->id_type <= self::TEMPLATE) {
            $this->id_lang = 0;
            $this->id_shop = 0;
        } else {
            is_null($id_lang) && $id_lang = \Context::getContext()->language->id;

            $this->id_lang = abs($id_lang % 100);
            $this->id_shop = $id_shop ? abs($id_shop % 100) : 0;
        }
    }

    public function getModel()
    {
        if (empty(self::$models[$this->id_type])) {
            throw new \RuntimeException('Unknown ObjectModel');
        }
        return self::$models[$this->id_type];
    }

    public function getAdminController()
    {
        if (empty(self::$admins[$this->id_type])) {
            throw new \RuntimeException('Unknown AdminController');
        }
        if ((int) \Tools::getValue('footerProduct')) {
            return self::$admins[self::PRODUCT];
        }
        return self::$admins[$this->id_type];
    }

    public function getModule()
    {
        return isset(self::$modules[$this->id_type]) ? self::$modules[$this->id_type] : '';
    }

    /**
     * Get shop ID list where the object is allowed
     *
     * @param bool $all     Get all or just by shop context
     *
     * @return array
     */
    public function getShopIdList($all = false)
    {
        if ($this->id_type <= self::TEMPLATE) {
            return array(0);
        }
        if (isset(self::$shop_ids[$this->id_type][$this->id])) {
            return self::$shop_ids[$this->id_type][$this->id];
        }
        isset(self::$shop_ids[$this->id_type]) or self::$shop_ids[$this->id_type] = array();

        $ids = array();
        $model = $this->getModel();
        $def = &$model::${'definition'};
        $db = \Db::getInstance();
        $table = $db->escape(_DB_PREFIX_ . $def['table'] . '_shop');
        $primary = $db->escape($def['primary']);
        $id = (int) $this->id;
        $ctx_ids = implode(', ', $all ? \Shop::getShops(true, null, true) : \Shop::getContextListShopID());
        $rows = $db->executeS(
            "SELECT id_shop FROM $table WHERE $primary = $id AND id_shop IN ($ctx_ids)"
        );
        if ($rows) {
            foreach ($rows as &$row) {
                $ids[] = $row['id_shop'];
            }
        }
        return self::$shop_ids[$this->id_type][$this->id] = $ids;
    }

    public function getDefaultShopId()
    {
        return ($ids = $this->getShopIdList()) ? $ids[0] : 0;
    }

    /**
     * Get UId list by shop context
     *
     * @param bool $strict  Collect only from allowed shops
     *
     * @return array
     */
    public function getListByShopContext($strict = false)
    {
        if ($this->id_shop || $this->id_type <= self::TEMPLATE) {
            return array("$this");
        }
        $list = array();
        $ids = $strict ? $this->getShopIdList() : \Shop::getContextListShopID();

        foreach ($ids as $id_shop) {
            $this->id_shop = $id_shop;
            $list[] = "$this";
        }
        $this->id_shop = 0;

        return $list;
    }

    /**
     * Get Language ID list of CE built contents
     *
     * @return array
     */
    public function getBuiltLangIdList()
    {
        $ids = array();

        if (self::TEMPLATE == $this->id_type) {
            $ids[] = 0;
        } elseif (self::CONTENT == $this->id_type) {
            foreach (\Language::getLanguages(false) as $lang) {
                $ids[] = (int) $lang['id_lang'];
            }
        } else {
            $id_shop = $this->id_shop ? $this->id_shop : $this->getDefaultShopId();
            $uids = self::getBuiltList($this->id, $this->id_type, $id_shop);

            empty($uids[$id_shop]) or $ids = array_keys($uids[$id_shop]);
        }
        return $ids;
    }

    public function toDefault()
    {
        $id_shop = $this->id_shop ? $this->id_shop : $this->getDefaultShopId();

        return sprintf('%d%02d%02d%02d', $this->id, $this->id_type, $this->id_lang, $id_shop);
    }

    public function __toString()
    {
        return sprintf('%d%02d%02d%02d', $this->id, $this->id_type, $this->id_lang, $this->id_shop);
    }

    public static function parse($id)
    {
        if ($id instanceof UId) {
            return $id;
        }
        if (!is_numeric($id) || \Tools::strlen($id) <= 6) {
            return false;
        }
        return new self(
            \Tools::substr($id, 0, -6),
            \Tools::substr($id, -6, 2),
            \Tools::substr($id, -4, 2),
            \Tools::substr($id, -2)
        );
    }

    public static function getTypeId($model)
    {
        return array_search(\Tools::strtolower($model), array_map('strtolower', self::$models));
    }

    /**
     * Get UId list of CE built contents grouped by shop(s)
     *
     * @param int $id
     * @param int $id_type
     * @param int|null $id_shop
     *
     * @return array [
     *     id_shop => [
     *         id_lang => UId,
     *     ],
     * ]
     */
    public static function getBuiltList($id, $id_type, $id_shop = null)
    {
        $uids = array();
        $table = _DB_PREFIX_ . 'ce_meta';
        $shop = null === $id_shop ? '__' : '%02d';
        $__id = sprintf("%d%02d__$shop", $id, $id_type, $id_shop);
        $rows = \Db::getInstance()->executeS(
            "SELECT id FROM $table WHERE id LIKE '$__id' AND name = '_elementor_edit_mode'"
        );
        if ($rows) {
            foreach ($rows as &$row) {
                $uid = self::parse($row['id']);
                isset($uids[$uid->id_shop]) or $uids[$uid->id_shop] = array();
                $uids[$uid->id_shop][$uid->id_lang] = $uid;
            }
        }
        return $uids;
    }
}

function absint($num)
{
    return $num instanceof UId ? $num : abs((int) $num);
}

function get_user_meta($user_id, $key = '', $single = false)
{
    return get_post_meta($user_id, '_u_' . $key, $single);
}

function update_user_meta($user_id, $key, $value, $prev_value = '')
{
    return update_post_meta($user_id, '_u_' . $key, $value, $prev_value);
}

function get_the_ID()
{
    if (UId::$_ID) {
        return UId::$_ID;
    }
    $controller = \Context::getContext()->controller;

    if ($controller instanceof \AdminCEEditorController ||
        $controller instanceof \CreativeElementsPreviewModuleFrontController
    ) {
        $id_key = \Tools::getIsset('post_id') ? 'post_id' : 'template_id';

        return UId::parse(\Tools::getValue('uid', \Tools::getValue($id_key)));
    }
    return false;
}

function get_preview_post_link($post = null, array $args = array(), $relative = true)
{
    if ($post instanceof UId) {
        $uid = $post;
    } elseif (is_numeric($post)) {
        $uid = UId::parse($post);
    } elseif ($post instanceof Post) {
        $uid = UId::parse($post->ID);
    } elseif (null === $post) {
        $uid = get_the_ID();
    } else {
        throw new \RuntimeException('TODO');
    }
    $ctx = \Context::getContext();
    $id_shop = $uid->id_shop ? $uid->id_shop : $uid->getDefaultShopId();
    $args['id_employee'] = $ctx->employee->id;
    $args['adtoken'] = \Tools::getAdminTokenLite($uid->getAdminController());
    $args['uid'] = $uid->toDefault();

    switch ($uid->id_type) {
        case UId::REVISION:
            throw new \RuntimeException('TODO');
            break;
        case UId::TEMPLATE:
            $link = $ctx->link->getModuleLink('creativeelements', 'preview', array(), null, null, null, $relative);
            break;
        case UId::CONTENT:
            $hook = \Tools::strtolower(\CEContent::getHookById($uid->id));

            if (in_array($hook, Helper::$productHooks)) {
                if ($id_product = (int) \Tools::getValue('footerProduct')) {
                    $args['footerProduct'] = $id_product;
                    $prod = new \Product($id_product, false, $uid->id_lang, $id_shop);
                } else {
                    $prods = \Product::getProducts($uid->id_lang, 0, 1, 'date_upd', 'DESC', false, $relative);
                    $prod = new \Product(!empty($prods[0]['id_product']) ? $prods[0]['id_product'] : null, false, $uid->id_lang);
                }
                $prod_attr = empty($prod->cache_default_attribute) ? 0 : $prod->cache_default_attribute;
                empty($prod->active) && $args['preview'] = 1;

                $link = $ctx->link->getProductLink($prod, null, null, null, $uid->id_lang, $id_shop, $prod_attr, false, $relative);
                break;
            }
            $page = 'index';

            if (stripos($hook, 'shoppingcart') !== false) {
                $page = 'cart';
                $args['action'] = 'show';
            } elseif ('displayleftcolumn' == $hook || 'displayrightcolumn' == $hook) {
                $layout = 'r' != $hook[7] ? 'layout-left-column' : 'layout-right-column';
                $layouts = Helper::getPageLayouts();
                unset($layouts['category']);

                if ($key = array_search($layout, $layouts)) {
                    $page = $key;
                } elseif ($key = array_search('layout-both-columns', $layouts)) {
                    $page = $key;
                }
            } elseif ('displaynotfound' == $hook) {
                $page = 'search';
            } elseif ('displaymaintenance' == $hook) {
                $args['maintenance'] = 1;
            }
            $link = $ctx->link->getPageLink($page, null, $uid->id_lang, null, false, $id_shop, $relative);

            if ('index' == $page && \Configuration::get('PS_REWRITING_SETTINGS')) {
                // Remove rewritten URL if exists
                $link = preg_replace('~[^/]+$~', '', $link);
            }
            break;
        case UId::PRODUCT:
            $prod = new \Product($uid->id, false, $uid->id_lang, $id_shop);
            $prod_attr = !empty($prod->cache_default_attribute) ? $prod->cache_default_attribute : 0;
            empty($prod->active) && $args['preview'] = 1;

            $link = $ctx->link->getProductLink($prod, null, null, null, $uid->id_lang, $id_shop, $prod_attr, false, $relative);
            break;
        case UId::CATEGORY:
            $link = $ctx->link->getCategoryLink($uid->id, null, $uid->id_lang, null, $id_shop, $relative);
            break;
        case UId::CMS:
            $link = $ctx->link->getCmsLink($uid->id, null, null, $uid->id_lang, $id_shop, $relative);
            break;
        case UId::YBC_BLOG_POST:
            $link = \Module::getInstanceByName('ybc_blog')->getLink('blog', array('id_post' => $uid->id), $uid->id_lang);
            break;
        case UID::XIPBLOG_POST:
            $link = call_user_func('XipBlog::xipBlogPostLink', array('id' => $uid->id));
            break;
        case UId::STBLOG_POST:
            $post = new \StBlogClass($uid->id, $uid->id_lang);

            $link = $ctx->link->getModuleLink('stblog', 'article', array(
                'id_st_blog' => $uid->id,
                'id_blog' => $uid->id,
                'rewrite' => $post->link_rewrite,
            ), null, $uid->id_lang, null, $relative);
            break;
        case UId::ADVANCEBLOG_POST:
            $post = new \BlogPosts($uid->id, $uid->id_lang);
            $args['blogtoken'] = $args['adtoken'];
            unset($args['adtoken']);

            $link = $ctx->link->getModuleLink('advanceblog', 'detail', array(
                'id' => $uid->id,
                'post' => $post->link_rewrite,
            ), null, $uid->id_lang, null, $relative);
            break;
        case UId::PRESTABLOG_POST:
            $post = new \NewsClass($uid->id, $uid->id_lang);
            empty($post->actif) && $args['preview'] = \Module::getInstanceByName('prestablog')->generateToken($uid->id);

            $link = call_user_func('PrestaBlog::prestablogUrl', array(
                'id' => $uid->id,
                'seo' => $post->link_rewrite,
                'titre' => $post->title,
                'id_lang' => $uid->id_lang,
            ));
            break;
        case UId::SIMPLEBLOG_POST:
            $post = new \SimpleBlogPost($uid->id, $uid->id_lang, $uid->id_shop);
            $cat = new \SimpleBlogCategory($post->id_simpleblog_category, $uid->id_lang, $uid->id_shop);

            $link = call_user_func('SimpleBlogPost::getLink', $post->link_rewrite, $cat->link_rewrite);
            break;
        case UId::PSBLOG_POST:
            $post = new \PsBlogBlog($uid->id, $uid->id_lang, $uid->id_shop);

            $link = \PsBlogHelper::getInstance()->getBlogLink(array(
                'id_psblog_blog' => $post->id,
                'link_rewrite' => $post->link_rewrite,
            ));
            break;
        case UId::HIBLOG_POST:
            $post = new \HiBlogPost($uid->id, $uid->id_lang, $uid->id_shop);

            $link = \Module::getInstanceByName('hiblog')->returnBlogFrontUrl($post->id, $post->friendly_url, 'post');
            break;
        default:
            $method = "get{$uid->getModel()}Link";

            $link = $ctx->link->$method($uid->id, null, $uid->id_lang, $id_shop, $relative);
            break;
    }
    return explode('#', $link)[0] . (stripos($link, '?') === false ? '?' : '&') . http_build_query($args);
}

function get_edit_post_link(UId $uid)
{
    $ctx = \Context::getContext();
    $id = $uid->id;
    $model = $uid->getModel();
    $admin = $uid->getAdminController();

    switch ($uid->id_type) {
        case UId::REVISION:
            throw new \RuntimeException('TODO');
            break;
        case UId::YBC_BLOG_POST:
            $link = $ctx->link->getAdminLink($admin) . '&' . http_build_query(array(
                'configure' => 'ybc_blog',
                'tab_module' => 'front_office_features',
                'module_name' => 'ybc_blog',
                'control' => 'post',
                'id_post' => $id,
            ));
            break;
        case UId::PRESTABLOG_POST:
            $link = $ctx->link->getAdminLink($admin) . "&configure=prestablog&editNews&idN=$id";
            break;
        case UId::CONTENT:
            if (\Tools::getIsset('footerProduct')) {
                $id = (int) \Tools::getValue('footerProduct');
                $model = 'Product';
                $admin = 'AdminProducts';
            }
            // Continue default case
        default:
            $def = &$model::${'definition'};
            $args = array(
                $def['primary'] => $id,
                "update{$def['table']}" => 1,
            );
            $link = $ctx->link->getAdminLink($admin, true, $args) . '&' . http_build_query($args);
            break;
    }
    return $link;
}
