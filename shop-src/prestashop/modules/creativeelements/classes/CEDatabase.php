<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

class CEDatabase
{
    private static $hooks = array(
        'displayBackOfficeHeader',
        'displayHeader',
        'displayFooterProduct',
        'overrideLayoutTemplate',
        'CETemplate',
        // Actions
        'actionProductAdd',
        'actionObjectCERevisionDeleteAfter',
        'actionObjectCETemplateDeleteAfter',
        'actionObjectCEContentDeleteAfter',
        'actionObjectProductDeleteAfter',
        'actionObjectCategoryDeleteAfter',
        'actionObjectManufacturerDeleteAfter',
        'actionObjectSupplierDeleteAfter',
        'actionObjectCmsDeleteAfter',
        'actionObjectCmsCategoryDeleteAfter',
        'actionObjectYbc_blog_post_classDeleteAfter',
        'actionObjectXipPostsClassDeleteAfter',
        'actionObjectStBlogClassDeleteAfter',
        'actionObjectBlogPostsDeleteAfter',
        'actionObjectNewsClassDeleteAfter',
    );

    public static function initConfigs()
    {
        $defaults = array(
            // General
            'elementor_frontend_edit' => 1,
            'elementor_max_revisions' => 10,
            // Style
            'elementor_default_generic_fonts' => 'sans-serif',
            'elementor_container_width' => 1140,
            'elementor_space_between_widgets' => 20,
            'elementor_page_title_selector' => _CE_PS16_ ? 'h1.page-heading' : 'header.page-header h1',
            'elementor_page_wrapper_selector' => _CE_PS16_
                ? '#columns, #columns .container'
                : '#wrapper, #wrapper .container, #content'
            ,
            // Advanced
            'elementor_load_fontawesome' => 1,
            'elementor_load_waypoints' => 1,
            'elementor_load_slick' => 1,
        );
        foreach ($defaults as $key => $value) {
            Configuration::hasKey($key) or Configuration::updateValue($key, $value);
        }
    }

    public static function createTables()
    {
        $db = Db::getInstance();
        $ce_revision = _DB_PREFIX_ . 'ce_revision';
        $ce_template = _DB_PREFIX_ . 'ce_template';
        $ce_content = _DB_PREFIX_ . 'ce_content';
        $ce_meta = _DB_PREFIX_ . 'ce_meta';
        $engine = _MYSQL_ENGINE_;

        return $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_revision` (
                `id_ce_revision` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `parent` bigint(20) UNSIGNED NOT NULL,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(255) NOT NULL,
                `content` longtext NOT NULL,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_revision`),
                KEY `id` (`parent`),
                KEY `date_add` (`date_upd`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_template` (
                `id_ce_template` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(128) NOT NULL DEFAULT '',
                `type` varchar(64) NOT NULL DEFAULT '',
                `content` longtext,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_template`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_content` (
                `id_ce_content` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `id_product` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `hook` varchar(64) NOT NULL DEFAULT '',
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_content`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$ce_content}_shop` (
                `id_ce_content` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_content`,`id_shop`),
                KEY `id_shop` (`id_shop`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$ce_content}_lang` (
                `id_ce_content` int(10) UNSIGNED NOT NULL,
                `id_lang` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL DEFAULT 1,
                `title` varchar(128) NOT NULL DEFAULT '',
                `content` longtext,
                PRIMARY KEY (`id_ce_content`,`id_shop`,`id_lang`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_meta` (
                `id_ce_meta` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
                `name` varchar(255) DEFAULT NULL,
                `value` longtext,
                PRIMARY KEY (`id_ce_meta`),
                KEY `id` (`id`),
                KEY `name` (`name`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ");
    }

    public static function addHome()
    {
        if (Db::getInstance()->getValue('SELECT 1 FROM ' . _DB_PREFIX_ . 'ce_content')) {
            return;
        }
        $content = new CEContent();
        $content->hook = 'displayHome';
        $content->active = true;
        $content->content = array();
        $content->title = array();

        foreach (Language::getLanguages(false) as $lang) {
            $content->title[$lang['id_lang']] = 'Home';
        }
        $content->add();
    }

    public static function updateTabs()
    {
        $improve = (int) Tab::getIdFromClassName('IMPROVE');

        try {
            $parent = self::updateTab($improve, 'AdminParentCEContent', true, array('en' => 'Creative Elements'), 'ce');
            $parent->position = $improve ? 1 : Tab::getInstanceFromClassName('AdminParentModules')->position;
            $parent->update();

            self::updateTab($parent->id, 'AdminCEContent', true, array(
                'en' => 'Content Anywhere',
                'fr' => 'Contenu n’importe où',
                'es' => 'Contenido cualquier lugar',
                'it' => 'Contenuto Ovunque',
                'de' => 'Inhalt überall',
            ));
            self::updateTab($parent->id, 'AdminCETemplates', true, array(
                'en' => 'Saved Templates',
                'fr' => 'Modèles enregistrés',
                'es' => 'Plantillas guardadas',
                'it' => 'Template salvati',
                'de' => 'Gespeicherte Templates',
            ));
            self::updateTab($parent->id, 'AdminCESettings', true, array(
                'en' => 'Settings',
                'fr' => 'Réglages',
                'es' => 'Ajustes',
                'it' => 'Impostazioni',
                'de' => 'Einstellungen',
            ));
            self::updateTab($parent->id, 'AdminCEEditor', false, array(
                'en' => 'Live Editor',
                'fr' => 'Éditeur en direct',
                'es' => 'Editor en vivo',
                'it' => 'Editor live',
                'de' => 'Live Editor',
            ));
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }

    protected static function updateTab($id_parent, $class, $active, array $name, $icon = '')
    {
        $tab = new Tab((int) Tab::getIdFromClassName($class));
        $tab->id_parent = $id_parent;
        $tab->module = 'creativeelements';
        $tab->class_name = $class;
        $tab->active = $active;
        $tab->icon = $icon;
        $tab->name = array();

        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[$lang['id_lang']] = isset($name[$lang['iso_code']]) ? $name[$lang['iso_code']] : $name['en'];
        }

        if (!$tab->save()) {
            throw new Exception('Can not save Tab: ' . $class);
        }

        return $tab;
    }

    public static function getHooks($all = true)
    {
        $hooks = self::$hooks;

        if (_CE_PS16_) {
            $hooks[] = 'displayOverrideTemplate';
        }
        if ($all) {
            $ce_content = _DB_PREFIX_ . 'ce_content';
            $rows = Db::getInstance()->executeS("SELECT DISTINCT hook FROM $ce_content");

            if (!empty($rows)) {
                foreach ($rows as &$row) {
                    $hook = $row['hook'];

                    if ($hook && !in_array($hook, $hooks)) {
                        $hooks[] = $hook;
                    }
                }
            }
        }
        return $hooks;
    }
}
