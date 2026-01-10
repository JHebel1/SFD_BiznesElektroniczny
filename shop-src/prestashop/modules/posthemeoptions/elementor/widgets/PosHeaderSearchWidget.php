<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Category;
use Configuration;
use Customer;
use DB;
use Shop;
use Validate;

class PosHeaderSearchWidget extends WidgetBase { 

    public function getName() {
        return 'posSearch';
    }
    public function getTitle() {
        return __( 'Search' );
    }

    public function getIcon() {
        return 'fa fa-search';
    }

    public function getCategories() {
        return [ 'posthemes_header' ];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'search_content',
            [
                'label' => __('Search'),
            ]
        );

        $this->addControl(
            'search_type',
            [
                'label' => __('Search display'),
                'type' => ControlsManager::SELECT,
                'default' => 'classic',
                'options' => [
                    'classic' => __('Form - classic'),
                    'minimal' => __('Form - minimal'),
                    'dropdown' => __('Icon - dropdown'),
                    'topbar' => __('Icon - topbar'),
                ],
                'prefix_class' => '',
                'render_type' => 'template',
                'frontend_available' => true,
            ]
        );
        
        $this->addControl(
            'button_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => __('Icon'),
                    'text' => __('Text'),
                ],
				'condition' => [
                    'search_type' => 'classic',
                ],
                'prefix_class' => 'elementor-search--button-type-',
                'render_type' => 'template',      
            ]
        );

        $this->addControl(
            'button_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'default' => __('Search'),
                'condition' => [
                    'button_type' => 'text',
                ],
            ]
        );
		$this->addControl(
			'icon',
			[
				'label' => __( 'Search icon'),
				'type' => ControlsManager::SELECT,
				'default' => 'icon-rt-loupe',
				'options' => [
					'icon-rt-loupe' => __( 'Icon 1'),
					'icon-rt-magnifier' => __( 'Icon 2'),
					'icon-rt-search2' => __( 'Icon 3'),
					'icon-rt-search1' => __( 'Icon 4'),
					'icon-rt-search' => __( 'Icon 5'),
				],
				'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'search_type',
                            'operator' => '!==',
                            'value' => 'classic',
                        ],
                        [
                            'name' => 'button_type',
                            'value' => 'icon',
                        ],
                    ],
                ],
			]
		);
     
        $this->addControl(
            'search_dropdown_position',
            [
                'label' => __( 'Dropdown Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Left'),
                    'right' => __( 'Right'),
                ],
                'prefix_class' => 'search-dropdown-',
                'condition' => [
                    'search_type' => 'dropdown',
                ],
            ]
        );     
        $this->addControl(
            'heading_input_content',
            [
                'label' => __('Input'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'search_type' => 'classic',
                ],
            ]
        );

        $this->addResponsiveControl(
            'width_size',
            [
                'label' => __('Input width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000, 
                    ],
                ],
                'default' => [
                    'size' => 200,
                    'unit' => 'px', 
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'min-width: {{SIZE}}{{UNIT}}',
                ],
                
            ]
        );
        $this->addResponsiveControl(
            'height_size',
            [
                'label' => __('Input height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 200, 
                    ],
                ],
                'default' => [
                    'size' => 30, 
                    'unit' => 'px', 
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'min-height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .pos-search__submit' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'placeholder',
            [
                'label' => __('Placeholder'),
                'type' => ControlsManager::TEXT,
                'default' => __('Search') . '...',
            ]
        );
		$this->addControl(
            'placeholder_color',
            [
                'label' => __('Placeholder Color'),
                'type' => ControlsManager::COLOR, 
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input::placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->endControlsSection();


        // Start for style
        $this->startControlsSection(
            'section_general',
            [
                'label' => __('General'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
            $this->addControl(
            'search_width',
                [
                    'label' => __('Width'),
                    'type' => ControlsManager::SELECT,
                    'default' => 'inline',
                    'options' => [ 
                        'fullwidth' => __('Full width 100%'),
                        'inline' => __('Inline (auto)')
                    ],
                    'prefix_class' => 'pewidth-',
                    'render_type' => 'template',
                    'frontend_available' => true
                ]
            );
        $this->endControlsSection();
        $this->startControlsSection(
            'section_toggle_style',
            [
                'label' => __('Toggle'),
                'tab' => ControlsManager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'search_type', 
                            'operator' => '!==',
                            'value' => 'classic',
                        ],
                        [
                            'name' => 'search_type',
                            'operator' => '!==',
                            'value' => 'minimal',
                        ],
                    ],
                ],
            ]
        );
        $this->startControlsTabs('tabs_toggle_colors');
        $this->startControlsTab(
            'tab_toggle_normal',
            [
                'label' => __('Normal'),
            ]
        );
        $this->addControl(
            'toggle_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__toggle i' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );
        $this->endControlsTab();

        $this->startControlsTab(
            'tab_toggle_hover',
            [
                'label' => __('Hover'),
            ]
        );
        $this->addControl(
            'toggle_color_hover',
            [
                'label' => __('Hover color'), 
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__toggle i:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
            ]
        );
        $this->endControlsTab();
        $this->endControlsTabs();
        $this->addControl(
            'toggle_icon_size',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__toggle i' => 'font-size: {{SIZE}}{{UNIT}}',  
                ],
                'default' => [
                    'size' => 24, 
                    'unit' => 'px',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();
    
        $this->startControlsSection(
            'section_input_style',
            [
                'label' => __('Input'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'icon_size_minimal',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-minimal' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'search_type' => 'minimal',
                ],
            ]
        );
		$this->addControl(
            'icon_color_minimal',
            [
                'label' => __('Icon Color'),
                'type' => ControlsManager::COLOR, 
                'selectors' => [
                    '{{WRAPPER}} .icon-minimal' => 'color: {{VALUE}}',
                ],
				'condition' => [
                    'search_type' => 'minimal',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} input[type="search"].pos-search__input',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->startControlsTabs('tabs_input_colors');

        $this->startControlsTab(
            'tab_input_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'input_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'input_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'input_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .pos-search__input',
                'fields_options' => [
                    'box_shadow_type' => [
                        'separator' => 'default',
                    ],
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_input_focus',
            [
                'label' => __('Focus'),
            ]
        );

        $this->addControl(
            'input_text_color_focus',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input:focus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'input_background_color_focus',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'input_border_color_focus',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input:focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'input_box_shadow_focus',
                'selector' => '{{WRAPPER}} .pos-search__input:focus',
                'fields_options' => [
                    'box_shadow_type' => [
                        'separator' => 'default',
                    ],
                ],       
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();
        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border_input',
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .pos-search__input',
            )
        );
  
        $this->addResponsiveControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->addControl(
			'padding',
			array(
				'label' => __('Padding'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors' => array(
					'{{WRAPPER}} .pos-search__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->addControl(
			'margin',
			array(
				'label' => __('Margin'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors' => array(
					'{{WRAPPER}} .pos-search__input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE, 
				'condition' => [
					'search_type!' => 'minimal',
                ],	
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .pos-search__submit',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'condition' => [
                    'button_type' => 'text',
                ],
            ]
        );

        $this->startControlsTabs('tabs_button_colors');

        $this->startControlsTab(
            'tab_button_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'button_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'button_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_button_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'button_text_color_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'button_background_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
		 $this->addControl(
            'button_border_color_focus',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();
        
        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'button_box_shadow_focus',
                'selector' => '{{WRAPPER}} .pos-search__submit',
                'fields_options' => [
                    'box_shadow_type' => [
                        'separator' => 'default',
                    ],
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();
        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border_button',
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .pos-search__submit',
            )
        );
       
        $this->addResponsiveControl(
            'button_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->addControl(
            'icon_size',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_type' => 'icon',
                ],
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'button_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER, 
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'min-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function getListCategories($category)
    {
        $context = Context::getContext();
        $settings = $this->getSettings();
        $range = '';
        $maxdepth = $settings['cate_depth']['size'];

        if (Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = 'AND nleft >= '.(int)$category->nleft.' AND nright <= '.(int)$category->nright;
        }

        $resultIds = array();
        $resultParents = array();
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
            FROM `'._DB_PREFIX_.'category` c
            INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$context->language->id.Shop::addSqlRestrictionOnLang('cl').')
            INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int)$context->shop->id.')
            WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')
            AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').'
            '.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'
            '.$range.'
            AND c.id_category IN (
                SELECT id_category
                FROM `'._DB_PREFIX_.'category_group`
                WHERE `id_group` IN ('.pSQL(implode(', ', Customer::getGroupsStatic((int)$context->customer->id))).')
            )
            ORDER BY `level_depth` ASC, '.(Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`').' '.(Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC'));
        foreach ($result as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }

        return $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null));
    }
    protected function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
    {
        $context = Context::getContext();
        if (is_null($id_category)) {
            $id_category = $context->shop->getCategory();
        }

        $children = [];

        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
            }
        }

        if (isset($resultIds[$id_category])) {
            $link = $context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
        } else {
            $link = $name = '';
        }

        return [
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'children' => $children,
            'currentDepth' => $currentDepth - 1
        ];
    }

    protected function render()
    {
		if (is_admin()){
            return print '<div class="ce-remote-render"></div>';
        }
        if(\Module::isEnabled('possearchproducts'))
        {
            $settings = $this->getSettings();

            $module = \Module::getInstanceByName('possearchproducts');
            $params = array(
                'placeholder'       => $settings['placeholder'],
                'search_type'       => $settings['search_type'],
                'icon'              => $settings['icon'],
                'button_type'       => $settings['button_type'],
                'button_text'       => $settings['button_text'],

            );

            echo $module->renderWidget( 'displaySearch', $params);
        }

    }

    protected function _contentTemplate(){}
}
