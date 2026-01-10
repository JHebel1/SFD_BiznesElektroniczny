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

class PageSettingsPage extends ControlsStack
{
    /**
     * @var Post
     */
    private $post;

    public function __construct(array $data = array())
    {
        $this->post = get_post($data['id']);

        $data['settings'] = $this->getSavedSettings();

        parent::__construct($data);
    }

    public function getName()
    {
        return 'page-settings';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_page_settings',
            array(
                'label' => __('Page Settings', 'elementor'),
                'tab' => ControlsManager::TAB_SETTINGS,
            )
        );

        $this->addControl(
            'post_title',
            array(
                'label' => __('Title', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => $this->post->post_title,
                'label_block' => true,
                'separator' => 'none',
            )
        );

        $settings_url = Settings::getUrl();

        $this->addControl(
            'hide_title',
            array(
                'label' => __('Hide Title', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('No', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'description' => sprintf(__('Not working? You can set a different selector for the title in the <a href="%s" target="_blank">Settings page</a>.', 'elementor'), $settings_url),
                'selectors' => array(
                    '{{WRAPPER}} ' . \Configuration::get('elementor_page_title_selector') => 'display: none',
                ),
            )
        );

        if (PageSettingsManager::isCptCustomTemplatesSupported()) {
            // require_once ABSPATH . '/wp-admin/includes/template.php';

            $options = array(
                'default' => __('Default', 'elementor'),
            );

            $options += get_page_templates(null, $this->post->post_type);

            $saved_template = get_post_meta($this->post->ID, '_wp_page_template', true);

            if (!$saved_template) {
                $saved_template = 'default';
            }

            $this->addControl(
                'template',
                array(
                    'label' => __('Page Layout', 'elementor'),
                    'type' => ControlsManager::SELECT,
                    'default' => $saved_template,
                    'options' => $options,
                )
            );

            $this->addControl(
                'template_default_description',
                array(
                    'type' => ControlsManager::RAW_HTML,
                    'raw' => __('Default Page Layout from your theme', 'elementor'),
                    'content_classes' => 'elementor-descriptor',
                    'separator' => '',
                    'condition' => array(
                        'template' => 'default',
                    ),
                )
            );

            $this->addControl(
                'template_canvas_description',
                array(
                    'type' => ControlsManager::RAW_HTML,
                    'raw' => __('No header, no footer, just Creative Elements', 'elementor'),
                    'content_classes' => 'elementor-descriptor',
                    'separator' => '',
                    'condition' => array(
                        'template' => 'elementor_canvas',
                    ),
                )
            );
        }

        $this->addControl(
            'full_width',
            array(
                'label' => __('Full Width', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('No', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'description' => sprintf(__('Not working? You can set a different selector for the content wrapper in the <a href="%s" target="_blank">Settings page</a>.', 'elementor'), $settings_url),
                'selectors' => array(
                    '{{WRAPPER}} ' . \Configuration::get('elementor_page_wrapper_selector') => 'min-width: 100%; margin: 0; padding: 0;',
                ),
                'condition' => array(
                    'template!' => 'elementor_canvas',
                ),
            )
        );

        // $post_type_object = get_post_type_object($this->post->post_type);
        // $can_publish = current_user_can($post_type_object->cap->publish_posts);
        $can_publish = true;

        if ('publish' === $this->post->post_status || 'private' === $this->post->post_status || $can_publish) {
            $this->addControl(
                'post_status',
                array(
                    'label' => __('Status', 'elementor'),
                    'type' => ControlsManager::SELECT,
                    'default' => $this->post->post_status,
                    'options' => get_post_statuses(),
                )
            );
        }

        $this->endControlsSection();

        $this->startControlsSection(
            'section_page_style',
            array(
                'label' => __('Body Style', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            array(
                'name' => 'background',
                'label' => __('Background', 'elementor'),
                'types' => array('none', 'classic', 'gradient'),
            )
        );

        $this->addResponsiveControl(
            'padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );

        $this->endControlsSection();

        Plugin::$instance->controls_manager->addCustomCssControls($this);
    }

    private function getSavedSettings()
    {
        $saved_settings = get_post_meta($this->post->ID, PageSettingsManager::META_KEY, true);

        return $saved_settings ? $saved_settings : array();
    }
}
