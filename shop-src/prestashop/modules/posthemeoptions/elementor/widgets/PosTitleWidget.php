<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Posthemes\Module\Poselements\WidgetHelper;

class PosTitleWidget extends WidgetHelper { 

	public function getName() {
		return 'pos_title';
	}
	public function getTitle() {
		return $this->l( 'Title' );
	}

	public function getIcon() {
		return 'fa fa-header';
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
					'placeholder' => $this->l( 'Road Title' ),
					'default' => $this->l('Road Title'),
					'dynamic' => [
						'active' => true,
					],
				]
			);
			$this->addControl(
				'title_html_tag',
				[
					'label' => $this->l( 'Title HTML Tag' ),
					'type' => ControlsManager::SELECT, 
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
					],
					'default' => 'h2',
					'separator' => 'before',
				]
			);
			$this->addControl(
				'description',
				[
					'label' => $this->l( 'Description' ),
					'type' => ControlsManager::TEXTAREA, 
					'placeholder' => $this->l( 'Enter description here' ),
					'default' => $this->l('Enter description here'),
				]
			);

			$this->addResponsiveControl(
				'align',
				[
					'label' => $this->l( 'Alignment' ),
					'type' => ControlsManager::CHOOSE,
					'options' => [
						'left' => [
							'title' => $this->l( 'Left' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => $this->l( 'Center' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => $this->l( 'Right' ),
							'icon' => 'fa fa-align-right',
						],
						'justify' => [
							'title' => $this->l( 'Justified' ),
							'icon' => 'fa fa-align-justify',
						],
					],
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .pos-title-widget' => 'text-align: {{VALUE}};',
					],
				]
			);

		$this->endControlsSection();
		
		$this->startControlsSection(
			'style_section',
			[
				'label' => $this->l( 'Style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		); 
			$designs_title = array('1' => 'Classic','2' => 'Border title','3' => 'Icon under title');
			$this->addControl(
				'design',
				[
					'label' => $this->l( 'Select design' ),
					'type' => ControlsManager::SELECT,
					'options' => $designs_title,
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
	                    '{{WRAPPER}} .pos-title-widget .pos-title:after' => 'background: {{VALUE}};', 
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
					'selectors' => [
						'{{WRAPPER}} .pos-title-widget .pos-title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
			$this->addControl(
				'title_color',
				[ 
					'label' => $this->l('Title Color'),
					'type' => ControlsManager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .pos-title-widget .pos-title' => 'color: {{VALUE}};',
					], 
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .pos-title-widget .pos-title',
				]
			);
			$this->addControl(
				'subtitle_color',
				[ 
					'label' => $this->l('Description Color'),
					'type' => ControlsManager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .pos-subtitle' => 'color: {{VALUE}};',
					], 
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'subtitle_typography',
					'selector' => '{{WRAPPER}} .pos-subtitle',
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
			'icon_section',
			[
				'label' => $this->l('Icon under title'),
				'tab' => ControlsManager::TAB_STYLE,
				'condition' => [
					'design' => '3'
				]
			]
		); 
			$this->addControl(
				'icon_type',
				[
					'label' => $this->l('Icon type'),
					'type' => ControlsManager::SELECT, 
					'options' => [
						'awesome' => 'Awesome icons',
						'image' => 'Image icon',
					],
					'default' => 'awesome',
				]
			);
			$this->addControl(
				'icon',
				array(
					'label' => $this->l('Icon'),
					'type' => ControlsManager::ICON,
					'label_block' => true,
					'default' => 'fa fa-star',
					'condition' => [
						'icon_type' => 'awesome'
					]
				)
			);
			$this->addControl(
				'image',
				array(
					'label' => $this->l('Choose icon image'),
					'type' => ControlsManager::MEDIA,
					'seo' => true,
					'default' => array(
						'url' => Utils::getPlaceholderImageSrc(),
					),
					'condition' => [
						'icon_type' => 'image'
					]
				)
			);
			$this->addControl(
				'color',
				array(
					'label' => $this->l('Icon Color'),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => array(
						'{{WRAPPER}} .pos-title-3 i' => 'color: {{VALUE}};',
					),
					'condition' => [
						'icon_type' => 'awesome'
					]
				)
			);
			$this->addControl(
				'size',
				array(
					'label' => $this->l('Size'),
					'type' => ControlsManager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 6,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .pos-title-3 i' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition' => [
						'icon_type' => 'awesome'
					]
				)
			);
			$this->addControl(
				'space',
				array(
					'label' => $this->l('Space'),
					'type' => ControlsManager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 6,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .pos-title-3 i' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'condition' => [
						'icon_type' => 'awesome'
					]
				)
			);
			$this->addControl(
				'rotate',
				array(
					'label' => $this->l('Rotate'),
					'type' => ControlsManager::SLIDER,
					'default' => array(
						'size' => 0,
						'unit' => 'deg',
					),
					'selectors' => array(
						'{{WRAPPER}} .pos-title-3 i' => 'transform: rotate({{SIZE}}{{UNIT}});',
					), 
					'condition' => [
						'icon_type' => 'awesome'
					]
				)
			);
			$this->addControl(
				'through_line',
				[
					'label' => $this->l('Through line'),
					'type' => ControlsManager::SELECT, 
					'options' => [
						'none' => 'None',
						'solid' => 'Solid',
						'dashed' => 'Dashed',
						'double' => 'Double',
						'dotted' => 'Dotted',
					],
					'default' => 'solid',
					'separator' => 'before',
				]
			);
			$this->addControl(
	            'line_color',
	            array(
	                'label' => $this->l('Through line Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .pos-title-3 .under-title .title-through-line' => 'border-color: {{VALUE}};', 
	                ),
					'condition' => [
						'through_line!' => 'none', 
					],
	            )
	        );
			$this->addControl(
				'line_width',
				array(
					'label' => $this->l('Through line width'),
					'type' => ControlsManager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 200,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .pos-title-3 .under-title .title-through-line' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition' => [
						'through_line!' => 'none',
					],
				)
			);
		$this->endControlsSection();
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {

		$settings = $this->getSettings(); 
		$title = $settings['title'];
		$description = $settings['description'];
		$html = '';

		$html .= '<div class="pos-title-widget pos-title-'. $settings['design'] .'">';
			if($title){
				$html .= '<h4 class="pos-title">'. $title .'</h4>';
			}
			if($description){
				$html .= '<p class="pos-subtitle">'. $description .'</p>';
			}
			if($settings['design'] == 3){
				$html .= '<span class="under-title">';
					if($settings['through_line'] != 'none'){
						$html .= '<span class="title-through-line line-before '. $settings['through_line'] .'"></span>';
					}
					if($settings['icon_type'] == 'awesome'){
						$html .= '<i class="'. $settings['icon'] .'"></i>';
					}
					if($settings['icon_type'] == 'image' && $settings['image']['url']){
						$html .= GroupControlImageSize::getAttachmentImageHtml($settings);
					}
					if($settings['through_line'] != 'none'){
						$html .= '<span class="title-through-line line-after '. $settings['through_line'] .'"></span>';
					}
				$html .= '</span>';
			}
		$html .= '</div>';

		echo $html;
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