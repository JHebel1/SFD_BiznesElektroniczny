<?php
/**
 * Creative Elements - Elementor based PageBuilder [in-stock]
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;

class GlobalCssFile extends CssFile
{
    const META_KEY = '_elementor_global_css';

    const FILE_HANDLER_ID = 'elementor-global';

    /**
     * @return array
     */
    protected function loadMeta()
    {
        return get_option(self::META_KEY);
    }

    /**
     * @param string $meta
     */
    protected function updateMeta($meta)
    {
        update_option(self::META_KEY, $meta);
    }

    /**
     * @return string
     */
    protected function getFileHandleId()
    {
        return self::FILE_HANDLER_ID;
    }

    protected function renderCss()
    {
        $this->renderSchemesCss();

        $this->renderSettingsCss();
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return 'global-' . \Context::getContext()->shop->id;
    }

    protected function getInlineDependency()
    {
        return 'elementor-frontend';
    }

    /**
     * @return bool
     */
    protected function isUpdateRequired()
    {
        $file_last_updated = $this->getMeta('time');

        $schemes_last_update = get_option(SchemeBase::LAST_UPDATED_META);

        if ($file_last_updated < $schemes_last_update) {
            return true;
        }

        $elementor_settings_last_updated = get_option(Settings::UPDATE_TIME_FIELD);

        if ($file_last_updated < $elementor_settings_last_updated) {
            return true;
        }

        return false;
    }

    private function renderSchemesCss()
    {
        $elementor = Plugin::$instance;

        foreach ($elementor->widgets_manager->getWidgetTypes() as $widget) {
            $scheme_controls = $widget->getSchemeControls();

            foreach ($scheme_controls as $control) {
                $this->addControlRules($control, $widget->getControls(), function ($control) use ($elementor) {
                    $scheme_value = $elementor->schemes_manager->getSchemeValue($control['scheme']['type'], $control['scheme']['value']);

                    if (empty($scheme_value)) {
                        return null;
                    }

                    if (!empty($control['scheme']['key'])) {
                        $scheme_value = $scheme_value[$control['scheme']['key']];
                    }

                    if (empty($scheme_value)) {
                        return null;
                    }

                    return $scheme_value;
                }, array('{{WRAPPER}}'), array('.elementor-widget-' . $widget->getName()));
            }
        }
    }

    private function renderSettingsCss()
    {
        $container_width = absint(get_option('elementor_container_width'));

        if (!empty($container_width)) {
            $this->stylesheet_obj->addRules('.elementor-section.elementor-section-boxed > .elementor-container', 'max-width:' . $container_width . 'px');
        }
    }
}
