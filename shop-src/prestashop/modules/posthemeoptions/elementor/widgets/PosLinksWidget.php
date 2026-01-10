<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Posthemes\Module\Poselements\WidgetHelper;

class PosLinksWidget extends WidgetHelper { 

	public function getName() {
		return 'pos_links';
	}
	public function getTitle() {
		return $this->l( 'Links' );
	}

	public function getIcon() {
		return 'fa fa-link';
	}

	public function getCategories() {
		return [ 'posthemes' ];
	}

	protected function _registerControls() {
		
		$this->startControlsSection(
			'content_section',
			[
				'label' => $this->l( 'Title' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'title',
				[
					'label' => $this->l( 'Title' ),
					'type' => ControlsManager::TEXT, 
					'dynamic' => [
						'active' => true,
					],
					'separator' => 'none',
				]
			);
			$this->addControl(
	            'link',
	            array(
	                'label' => $this->l('Link'),
	                'type' => ControlsManager::URL,
	                'placeholder' => 'http://your-link.com',
	                'default' => array(
	                    'url' => '',
	                ),
	            )
	        );
			
	        $this->addResponsiveControl(
            	'text_align',
	            [
	                'label' => __('Alignment'),
	                'type' => ControlsManager::CHOOSE,
	                'options' => [
	                    'left' => [
	                        'title' => $this->l('Left'),
	                        'icon' => 'fa fa-align-left',
	                    ],
	                    'center' => [
	                        'title' => $this->l('Center'),
	                        'icon' => 'fa fa-align-center',
	                    ],
	                    'right' => [
	                        'title' => $this->l('Right'),
	                        'icon' => 'fa fa-align-right',
	                    ],
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .pos-links-widget' => 'text-align: {{VALUE}};',
	                ],
	            ]
	        );
	        $this->addControl(
            'links',
	            array(
	                'label' => $this->l('Links'),
	                'type' => ControlsManager::REPEATER,
	                'default' => array(),
	                'fields' => array(
	                    array(
	                    	'name' => 'title',
	                    	'label' => $this->l( 'Title' ),
							'type' => ControlsManager::TEXT, 
							'label_block'=> true,
	                    ),
	                    array(
	                        'name' => 'type_link',
	                        'label' => $this->l('Type link'),
	                        'type' => ControlsManager::SELECT2,
	                        'options' => [
	                        	'page' => $this->l('Content pages'),
	                        	'static' => $this->l('Static pages'),
	                        	'custom' => $this->l('Custom'),
	                        ],
	                    ),
	                    array(
	                        'name' => 'page_link',
	                        'label' => $this->l('Content pages'),
	                        'type' => ControlsManager::SELECT2,
	                        'options' => $this->getCMSPages(1),
	                        'condition' => array(
			                    'type_link' => 'page',
			                ),
			                'label_block' => true,
	                    ),
	                    array(
	                        'name' => 'static_link',
	                        'label' => $this->l('Static pages'),
	                        'type' => ControlsManager::SELECT2,
	                        'options' => $this->getPagesOption(),
	                        'condition' => array(
			                    'type_link' => 'static',
			                ),
			                'label_block' => true,
	                    ),
	                    array(
	                    	'name' => 'custom_link',
	                    	'label' => $this->l('Custom Link'),
			                'type' => ControlsManager::URL,
			                'placeholder' => 'http://your-link.com',
			                'default' => array(
			                    'url' => '',
			                ),
			                'condition' => array(
			                    'type_link' => 'custom',
			                ),
	                    )
	                ),
	                'title_field' => '{{{ title }}}',
	            )
	        );
			$this->addControl(
            'display_link',
                [
                    'label' => __('Display'),
                    'type' => ControlsManager::SELECT,
                    'default' => 'block',
                    'options' => [ 
                        'block' => __('Block'),
                        'inline' => __('Inline (auto)')
                    ],
                    'prefix_class' => 'display-',
                    'render_type' => 'template',
                    'frontend_available' => true
                ]
            );
		$this->endControlsSection();
		
		$this->startControlsSection(
			'title_section',
			[
				'label' => $this->l( 'Title' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		); 
			$designs_title = array('1' => 'Classic','2' => 'Border Title');
			$this->addControl(
				'design',
				[
					'label' => $this->l( 'Select design' ),
					'type' => ControlsManager::SELECT,
					'options' => $designs_title,
					'prefix_class' => 'title-',
					'frontend_available' => true,
					'default' => '1'
				]
			);
			$this->addControl(
	            'border_title_color',
	            array(
	                'label' => $this->l('Border Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .links-widget-title:after' => 'background: {{VALUE}};', 
	                ),
					'condition' => [
						'design' => '2',
					],
	            )
	        );
			$this->addControl(
				'space_title_size',
				[
					'label' => $this->l( 'Title Spacing'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 100,
						]
					],
					'default' => [
						'size' => 20,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .pos-links-widget .links-widget-title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'title_typo',
					'selector' 		=> '{{WRAPPER}} .pos-links-widget .links-widget-title',
				]
			);
			$this->addControl(
	            'title_color',
	            array(
	                'label' => $this->l('Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-links-widget .links-widget-title a,{{WRAPPER}} .pos-links-widget .links-widget-title span' => 'color: {{VALUE}};',
	                ),
	            )
	        );
		$this->endControlsSection();
		$this->startControlsSection(
			'links_section',
			[
				'label' => $this->l( 'Links' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		); 
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'links_typo',
					'selector' 		=> '{{WRAPPER}} .pos-links-widget .links-widget-content a',
				]
			);
			$this->addResponsiveControl(
				'padding',
				[
					'label' => $this->l( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .pos-links-widget .links-widget-content a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
					],
				]
			);
			$this->addResponsiveControl(
				'margin',
				[
					'label' => $this->l( 'Margin' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .pos-links-widget .links-widget-content a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
	            GroupControlBorder::getType(),
	            array(
	                'name' => 'border',
	                'label' => $this->l('Border'),
	                'placeholder' => '1px',
	                'default' => '1px',
	                'selector' => '{{WRAPPER}} .pos-links-widget .links-widget-content a'
	            )
	        );
			 $this->addControl(
	            'link_border_radius',
	            array(
	                'label' => __('Border Radius', 'elementor'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-links-widget .links-widget-content a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );

			$this->startControlsTabs('tabs_button_style');

	        $this->startControlsTab(
	            'tab_button_normal',
	            array(
	                'label' => $this->l('Normal'),
	            )
	        );

	        $this->addControl(
	            'content_text_color',
	            array(
	                'label' => $this->l('Text Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-links-widget .links-widget-content a' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-links-widget .links-widget-content a' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->endControlsTab();

	        $this->startControlsTab(
	            'tab_button_hover',
	            array(
	                'label' => $this->l('Hover'),
	            )
	        );

	        $this->addControl(
	            'hover_color',
	            array(
	                'label' => $this->l('Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-links-widget .links-widget-content a:hover' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_hover_color',
	            array(
	                'label' => $this->l('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-links-widget .links-widget-content a:hover' => 'background-color: {{VALUE}};',
	                ),
	               
	            )
	        );
	        $this->endControlsTab();

	        $this->endControlsTabs();
		$this->endControlsSection();

	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {
		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}
		$settings = $this->getSettings(); 
		$context = Context::getContext();

		foreach($settings['links'] as &$link){
			if($link['type_link'] == 'page'){
				$link['page_link'] = $context->link->getCMSLink((int)$link['page_link']);
			}
			if($link['type_link'] == 'static'){
				$link['static_link'] = $context->link->getPageLink($link['static_link']);	
			}
		}
		$context->smarty->assign(
			array(
				'title'         => $settings['title'],
				'title_url'     => $settings['link'],
				'links'         => $settings['links'],
				'id'			=> $this->getId()
			)
		);
		echo $context->smarty->fetch( POS_ELEMENTS_PATH . 'views/templates/front/links.tpl' );
		
	} 
	/**
     * Get translation for a given widget text
     *
     * @access protected
     *
     * @param string $string    String to translate
     *
     * @return string Translation
     */
    protected function l($string)
    {
        return translate($string, 'posthemeoptions', basename(__FILE__, '.php'));
    }
}