<?php  

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Manufacturer;
use Posthemes\Module\Poselements\WidgetHelper;

class PosBrandWidget extends WidgetHelper { 
	public function getName() {
		return 'pos_brand';
	}

	public function getTitle() {
		return $this->l( 'Brand Logo');
	}
	
	public function getIcon() {
		return 'fa fa-barcode';
	}

	public function getCategories() {
		return [ 'posthemes' ];
	}
	
	protected function _registerControls() {
		
		//Display
		$this->startControlsSection(
			'item_section',
			[
				'label' => $this->l( 'General'),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'limit',
				[
					'label'     	=> $this->l('Limit'),
					'type'      	=> ControlsManager::NUMBER,
					'default'  	 	=> 8,
				]
			);
			$this->addControl(
				'brand_background',
				[
					'label' 		=> $this->l('background'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .pos-brand-widgets .brand-item' => 'background: {{VALUE}};',
					],
				]
			);
			$this->addResponsiveControl(
			'brand_padding',
				[
					'label' 		=> $this->l('Padding'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .pos-brand-widgets .brand-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'brand_border_radius',
				[
					'label' 		=> $this->l('Border Radius'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .pos-brand-widgets .brand-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' 			=> 'item_border',
					'selector' 		=> '{{WRAPPER}} .pos-brand-widgets .brand-item',
				]
			);
			$this->addControl(
				'enable_slider',
				[
					'label' 		=> $this->l('Enable Slider'),
					'type' 			=> ControlsManager::HIDDEN,
					'default' 		=> 'yes', 
				]
			);
		
			$this->addResponsiveControl(
				'columns',
				[
					'label' => $this->l( 'Columns'),
					'type' => ControlsManager::SLIDER,
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'size_units' => ['item'],
					'range' => [
						'item' => [
							'min' => 1,
							'max' => 6,
							'step' => 1,
						],
					],
					'desktop_default' => [
						'size' => 4,
						'unit' => 'item',
					],
					'tablet_default' => [
						'size' => 3,
						'unit' => 'item',
					],
					'mobile_default' => [
						'size' => 2,
						'unit' => 'item',
					],
					'condition' 	=> [
						'enable_slider!' => 'yes',
					],
				]
			);
		$this->endControlsSection();
		//Tab Setting
		$this->addCarouselControls($this->getName(), 6);
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	 
	protected function render() {

		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}

		$settings = $this->getSettings(); 
		$context = \Context::getContext();
		$output = '';

		// Data settings
        if($settings['enable_slider']){
			$responsive = array();
			if($settings['responsive'] == 'default') {
				$responsive = $this->posDefaultResponsive((int)$settings['items']);
			}else{
				$default_responsive = $this->posDefaultResponsive((int)$settings['items']);
				
				$responsive = array(
					'xl' => $settings['items_laptop'] ? $settings['items_laptop'] : $default_responsive['xl'],
					'lg' => $settings['items_landscape_tablet'] ? $settings['items_landscape_tablet'] : $default_responsive['lg'],
					'md' => $settings['items_portrait_tablet'] ? $settings['items_portrait_tablet'] : $default_responsive['md'],
					'sm' => $settings['items_landscape_mobile'] ? $settings['items_landscape_mobile'] : $default_responsive['sm'],
					'xs' => $settings['items_portrait_mobile'] ? $settings['items_portrait_mobile'] : $default_responsive['xs'],
					'xxs' => $settings['items_small_mobile'] ? $settings['items_small_mobile'] : $default_responsive['xxs'],
				);
			};

			$slick_options = [
				'slidesToShow'  => (int) $settings['items'],
				'slidesToScroll'=> ($settings['slides_to_scroll'] == '1') ? (int) $settings['items'] : 1,
				'autoplay'      => $settings['autoplay'] ? true : false,
				'infinite'      => $settings['infinite'] ? true : false,
				'arrows'        => $settings['arrows'] ? true : false,
				'dots'          => $settings['dots'] ? true : false,
				'autoplaySpeed' => (int) $settings['autoplay_speed'] ? (int) $settings['autoplay_speed'] : 3000,
				'speed'			=> (int) $settings['transition_speed'] ? (int) $settings['transition_speed'] : 3000,
				'rows'          => (int) $settings['rows'] ? $settings['rows'] : 1,
			];
			if($settings['slides_to_scroll'] == '1'){
				$scroll = true;
			}else{
				$scroll = false;
			}
			$slick_responsive = [
				'items_laptop'           => (int)$responsive['xl'],
				'items_landscape_tablet' => (int)$responsive['lg'],
				'items_portrait_tablet'  => (int)$responsive['md'],
				'items_landscape_mobile' => (int)$responsive['sm'],
				'items_portrait_mobile'  => (int)$responsive['xs'],
				'items_small_mobile'     => (int)$responsive['xxs'],
				'scroll' 				 => $scroll,
			];

			$this->addRenderAttribute(
				'brandlogo', 
				[
					'class' => ['brand-logo', 'slick-slider-block', 'column-desktop-'. $responsive['xl'],'column-tablet-'. $responsive['md'],'column-mobile-'. $responsive['xs']],
					'data-slider_responsive' => json_encode($slick_responsive),
					'data-slider_options' => json_encode($slick_options),
				]
				
			);
		}else{
			$columns = array(
					'desktop' => 12/$settings['columns']['size'],
					'tablet' => 12/$settings['columns_tablet']['size'],
					'mobile' => 12/$settings['columns_mobile']['size'],
				);
		}
	 
		$brands = array();
		
		$allBrands = Manufacturer::getManufacturers(false, $context->language->id, true, false, (int)$settings['limit']?(int)$settings['limit']:8);

     	foreach ($allBrands as $brand) {
            $fileExist = file_exists(
                _PS_MANU_IMG_DIR_ . $brand['id_manufacturer'] . '.jpg'
            );
            if ($fileExist) {
                $brands[$brand['id_manufacturer']]['name'] = $brand['name'];
                $brands[$brand['id_manufacturer']]['link'] = Context::getContext()->link->getManufacturerLink($brand['id_manufacturer'], $brand['link_rewrite']);
                $brands[$brand['id_manufacturer']]['image'] = Context::getContext()->link->getManufacturerImageLink($brand['id_manufacturer']);
            }
        }
		
		if(!empty($brands)):
			?>
			<div class="pos-brand-widgets">
				<?php 
				if($settings['enable_slider']){ ?>
					<div <?php echo $this->getRenderAttributeString('brandlogo'); ?>>
						<?php foreach($brands as $brand): ?>
							<div class="brand-item">
								<a href="<?php echo $brand['link']; ?>"><img src="<?php echo $brand['image']; ?>" alt="<?php echo $brand['name']; ?>"/></a>
							</div>
						<?php endforeach; ?>
					</div>
				<?php }else{ ?>
					<div class="brand-logo grid-module">
						<?php foreach($brands as $brand): ?>
							<div class="brand-item col-lg-<?php echo $columns['desktop']; ?> col-md-<?php echo $columns['tablet']; ?> col-xs-<?php echo $columns['mobile']; ?>">
								<a href="<?php echo $brand['link']; ?>"><img src="<?php echo $brand['image']; ?>" alt="<?php echo $brand['name']; ?>"/></a>
							</div>
						<?php endforeach; ?>
					</div>
				<?php } ?>
			</div>
		<?php else: 
			echo 'No content';
		endif; 
		

	} 
	/**
	 * Render accordion widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0
	 * @access protected
	 */
	protected function _contentTemplate() {
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