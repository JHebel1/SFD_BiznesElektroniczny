<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   In-stock license
 */

defined('_PS_VERSION_') or die;

function upgrade_module_1_0_0($module)
{
    require_once _CE_PATH_ . 'classes/CEDatabase.php';
    require_once _CE_PATH_ . 'classes/CEMigrate.php';

    if (Shop::isFeatureActive()) {
        Shop::setContext(Shop::CONTEXT_ALL);
    }
    $res = _CE_PS16_ ? true : $module->uninstallOverrides() && $module->installOverrides();

    CEDatabase::initConfigs();
    CEDatabase::createTables();
    CEDatabase::updateTabs();

    foreach (CEDatabase::getHooks(false) as $hook) {
        $res = $res && $module->registerHook($hook);
    }

    CEMigrate::moveConfigs();

    if ($res && CEMigrate::storeIds()) {
        if (_CE_PS16_) {
            Context::getContext()->controller->confirmations[] = CEMigrate::renderJavaScripts();
        } else {
            ob_start(function ($json) use ($module) {
                $data = json_decode($json, true);

                if (!empty($data[$module->name]['status'])) {
                    // Upgrade
                    $data[$module->name]['msg'] .= CEMigrate::renderJavaScripts();

                    $json = json_encode($data);
                } elseif (!empty($data['status'])) {
                    // Upload
                    $data['msg'] .= CEMigrate::renderJavaScripts();
                    $data['status'] = false;

                    $json = json_encode($data);
                }
                return $json;
            });
        }
    }
    return $res;
}
