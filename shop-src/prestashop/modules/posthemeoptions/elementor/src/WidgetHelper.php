<?php

namespace Posthemes\Module\Poselements;

use Context;	
use DB;	
use Language;	
use Currency;	
use Configuration;	
use Meta;	
use Manufacturer;	
use Shop;	
use Supplier;	
use PrestaShop\PrestaShop\Adapter\Presenter\Object\ObjectPresenter;
define( 'POS_ELEMENTS_PATH', _PS_MODULE_DIR_ . 'posthemeoptions/' ); 

abstract class WidgetHelper extends \CE\WidgetBase
{
    protected $context;

    protected $catalog;

    protected $show_prices;

    protected $parentTheme;

    protected $imageSize;

    protected $currency;

    protected $usetax;

    protected $noImage;


    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();
        $this->catalog = \Configuration::get('PS_CATALOG_MODE');
        $this->show_prices = !\Configuration::isCatalogMode();
        $this->parentTheme = !empty($this->context->shop->theme) ? $this->context->shop->theme->get('parent') : '';
        $this->imageSize = \ImageType::{'getFormattedName'}('home');
        $this->loading = stripos($this->getName(), 'carousel') === false ? 'lazy' : 'auto';

        if ($this->context->controller instanceof \AdminController) {
            isset($this->context->customer->id) or $this->context->customer = new \Customer();
        } else {
            if (!$this->catalog) {
                $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link);
                $this->noImage = method_exists($imageRetriever, 'getNoPictureImage') ? $imageRetriever->getNoPictureImage($this->context->language) : null;
            }
        }
        parent::__construct($data, $args);
    }

    protected function getListingOptions()
    {
        $opts = [
            'category' => $this->l('Featured Products'),
            'prices-drop' => $this->l('Prices Drop'),
            'new-products' => $this->l('New Products'),
        ];
        if (!$this->catalog) {
            $opts['best-sales'] = $this->l('Best Sales');
        }
        $opts['products'] = $this->l('Custom Products');

        return $opts;
    }

    protected function getAjaxProductsListUrl()
    {
        if (version_compare(_PS_VERSION_, '1.7.6', '<')) {
            $url = 'ajax_products_list.php?';
            $args = [];
        } else {
            $url = 'index.php?';
            $args = [
                'controller' => 'AdminProducts',
                'token' => \Tools::getAdminTokenLite('AdminProducts'),
                'ajax' => 1,
                'action' => 'productsList',
            ];
        }
        return $url . http_build_query($args + [
            'forceJson' => 1,
            'disableCombination' => 1,
            'excludeVirtuals' => 0,
            'exclude_packs' => 0,
            'limit' => 20,
        ]);
    }
    protected function getCategoryOptions()
    {
        $categories = [];

        foreach (\Category::getSimpleCategories($this->context->language->id) as &$cat) {
            $categories[$cat['id_category']] = "#{$cat['id_category']} {$cat['name']}";
        }
        
        return $categories;
    }
    protected function getProductOptions()
    {
        $products = [];

        foreach (\Product::getSimpleProducts($this->context->language->id) as &$cat) {
            $products[$cat['id_product']] = "#{$cat['id_product']} {$cat['name']}";
        }
        return $products;
    }

    
    public function addCarouselControls($subtitle , $item)
    {
        $this->startControlsSection(
            "section_title_$subtitle",
            [
                'label' => $this->l('Carousel Settings'),
                'condition'     => [
                    'enable_slider' => 'yes',
                ],
            ]
        );
            $this->addControl(
                'rows',
                [
                    'label' => $this->l( 'Rows' ),
                    'description' => $this->l( 'Multi rows for slider' ),
                    'type'          => \CE\ControlsManager::NUMBER,
                    'default'       => 1,
                    'condition'     => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $items = array('1' => 1,'2' => 2,'3' => 3,'4' => 4,'5' => 5,'6' => 6,'7' => 7,'8' => 8);
            $responsive = array('' => $this->l('Default'), '1' => 1,'2' => 2,'3' => 3,'4' => 4,'5' => 5,'6' => 6,'7' => 7,'8' => 8);
            
            $this->addControl(
                'items',
                [
                    'label' => $this->l( 'Slides to Show'),
                    'description' => $this->l( 'Desktop screen'),
                    'type' => \CE\ControlsManager::SELECT,
                    'options' => $items,
                    'frontend_available' => true,
                    
                    'default' => $item
                ]
            );
            $this->addControl(
                'responsive',
                [
                    'label' => $this->l( 'Responsive'),
                    'type' => \CE\ControlsManager::SELECT,
                    'options' => [
                        'default'  => $this->l( 'Default'),
                        'custom' => $this->l( 'Custom')
                    ],
                    'frontend_available' => true,
                    
                    'default' => 'default'
                ]
            );
            $this->addControl(
                'items_laptop',
                [
                    'label' => $this->l( 'Items on Laptop'),
                    'description' => $this->l( 'Responsive screen: 1200px to 1535px'),
                    'type' => \CE\ControlsManager::SELECT,
                    'options' => $responsive,
                    'frontend_available' => true,
                    'condition'     => [
                        'responsive' => 'custom',
                    ],
                    'default' => ''
                ]
            );
            $this->addControl(
                'items_landscape_tablet',
                [
                    'label' => $this->l( 'Items on Landscape Tablet'),
                    'description' => $this->l( 'Responsive screen: 992px to 1199px'),
                    'type' => \CE\ControlsManager::SELECT,
                    'options' => $responsive,
                    'frontend_available' => true,
                    'condition'     => [
                        'responsive' => 'custom',
                    ],
                    'default' => ''
                ]
            );
            $this->addControl(
                'items_portrait_tablet',
                [
                    'label' => $this->l( 'Items on Portrait Tablet'),
                    'description' => $this->l( 'Responsive screen: 768px to 991px'),
                    'type' => \CE\ControlsManager::SELECT,
                    'options' => $responsive,
                    'frontend_available' => true,
                    'condition'     => [
                        'responsive' => 'custom',
                    ],
                    'default' => ''
                ]
            );
            $this->addControl(
                'items_landscape_mobile',
                [
                    'label' => $this->l( 'Items on Landscape Phone'),
                    'description' => $this->l( 'Responsive screen: 568px to 767px'),
                    'type' => \CE\ControlsManager::SELECT,
                    'options' => $responsive,
                    'frontend_available' => true,
                    'condition'     => [
                        'responsive' => 'custom',
                    ],
                    'default' => ''
                ]
            );
            $this->addControl(
                'items_portrait_mobile',
                [
                    'label' => $this->l( 'Items on Portrait Phone'),
                    'description' => $this->l( 'Responsive screen: 360px to 567px'),
                    'type' => \CE\ControlsManager::SELECT,
                    'options' => $responsive,
                    'frontend_available' => true,
                    'condition'     => [
                        'responsive' => 'custom',
                    ],
                    'default' => ''
                ]
            );
            $this->addControl(
                'items_small_mobile',
                [
                    'label' => $this->l( 'Items on Small Phone'),
                    'description' => $this->l( 'Responsive screen: <359px'),
                    'type' => \CE\ControlsManager::SELECT,
                    'options' => $responsive,
                    'frontend_available' => true,
                    'condition'     => [
                        'responsive' => 'custom',
                    ],
                    'default' => ''
                ]
            );
            $this->addControl(
                'slides_to_scroll',
                [
                    'label' => $this->l( 'Slides to Scroll'),
                    'type' => \CE\ControlsManager::SELECT,
                    'description' => $this->l( 'Set how many slides are scrolled per swipe.'),
                    'options' => [
                        '0' => $this->l( 'All visible items'),
                        '1' => $this->l( '1 item'),
                    ],
                    'default' => '1',
                    'frontend_available' => true,
                ]
            ); 
            $this->addControl(
                'arrows',
                [
                    'label'         => $this->l('Arrows'),
                    'type'          => \CE\ControlsManager::SWITCHER,
                    'default'       => 'yes',
                    'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
                ]
            );
			$this->addControl(
                'arrows_position',
                [
                    'label'         => $this->l('Arrows position'),
                    'type'          => \CE\ControlsManager::SELECT,
                    'options'       => [
                        'top' => 'Top - Right',
                        'center' => 'Center'
                    ],
                    'default' => 'center',
					'prefix_class' => 'slider-arrows-',
                    'condition'     => [
                        'arrows' => 'yes',
                    ],
                ] 
            );

            $this->addControl(
                'dots',
                [
                    'label'         => $this->l('Dots'),
                    'type'          => \CE\ControlsManager::SWITCHER,
                    'default'       => 'no',
                    'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
                ]
            );
            $this->addControl(
                'autoplay',
                [
                    'label' => $this->l( 'Autoplay'),
                    'type'          => \CE\ControlsManager::SWITCHER,
                    'default' => 'no',  
                    'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
                ]
            );
            $this->addControl(
                'autoplay_speed',
                [
                    'label'         => $this->l('AutoPlay Transition Speed (ms)'),
                    'type'          => \CE\ControlsManager::NUMBER,
                    'default'       => 3000,
                ]
            );
            $this->addControl(
                'pause_on_hover',
                [
                    'label'         => $this->l('Pause on Hover'),
                    'type'          => \CE\ControlsManager::SWITCHER,
                    'default'       => 'yes',
                    'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
                ]
            );

            $this->addControl(
                'infinite',
                [
                    'label'         => $this->l('Infinite Loop'),
                    'type'          => \CE\ControlsManager::SWITCHER,
                    'default'       => 'no',
                    'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
                ]
            );
            $this->addControl(
                'transition_speed',
                [
                    'label'         => $this->l('Transition Speed (ms)'),
                    'type'          => \CE\ControlsManager::NUMBER,
                    'default'       => 500,
                ]
            );
        

        $this->endControlsSection();
    }  

    protected function getProduct($id)
    {
        
        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $this->context->getTranslator()
        );
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $assembler = new \ProductAssembler($this->context);
        $result = ['id_product' => $id];

        try {
            if (!$assembledProduct = $assembler->assembleProduct($result)) {
                return false;
            }
            return $presenter->present(
                $presenterFactory->getPresentationSettings(),
                $assembledProduct,
                $this->context->language
            );
        } catch (\Exception $ex) {
            return false;
        }
    } 

    protected function getProducts($listing, $order_by, $order_dir, $limit, $id_category = 2, $products = [])
    {
        $tpls = [];

        if ('products' === $listing) {
            // Custom Products
            if ('rand' === $order_by) {
                shuffle($products);
            }
            foreach ($products as $product) {
                if ($product) {
                    $tpls[] = $this->getProduct($product);
                }
            }
            return $tpls;
        }

        $translator = $this->context->getTranslator();
        $query = new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery();
        $query->setResultsPerPage($limit <= 0 ? 8 : (int) $limit);
        $query->setQueryType($listing);

        switch ($listing) {
            case 'category':
                $category = new \Category((int) $id_category);
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider($translator, $category);
                $query->setSortOrder(
                    'rand' == $order_by
                    ? \PrestaShop\PrestaShop\Core\Product\Search\SortOrder::random()
                    : new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir)
                );
                break;
            case 'prices-drop':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
            case 'new-products':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
            case 'best-sales':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
        }
        $result = $searchProvider->runQuery(new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext($this->context), $query);

        $assembler = new \ProductAssembler($this->context);
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $translator
        );

        foreach ($result->getProducts() as $rawProduct) {
            $tpls[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $tpls;
    }

    public function posDefaultResponsive($item){
        switch($item) {
            case(8):
                $responsive = array(
                    'xl' => 8,
                    'lg' => 7,
                    'md' => 5,
                    'sm' => 3,
                    'xs' => 2,
                    'xxs' => 1,
                );
                break;
            case(7):
                $responsive = array(
                    'xl' => 7,
                    'lg' => 6,
                    'md' => 5,
                    'sm' => 3,
                    'xs' => 2,
                    'xxs' => 1,
                );
                break;
            case(6):
                $responsive = array(
                    'xl' => 6,
                    'lg' => 5,
                    'md' => 4,
                    'sm' => 3,
                    'xs' => 2,
                    'xxs' => 1,
                );
                break;
            case(5):
                $responsive = array(
                    'xl' => 5,
                    'lg' => 5,
                    'md' => 4,
                    'sm' => 3,
                    'xs' => 2,
                    'xxs' => 1,
                );
                break;
            case(4):;
                $responsive = array(
                    'xl' => 4,
                    'lg' => 4,
                    'md' => 3,
                    'sm' => 3,
                    'xs' => 2,
                    'xxs' => 1,
                );
                break;
            case(3):
                $responsive = array(
                    'xl' => 3,
                    'lg' => 3,
                    'md' => 3,
                    'sm' => 2,
                    'xs' => 2,
                    'xxs' => 1,
                );
                break;
            case(2):
                $responsive = array(
                    'xl' => 2,
                    'lg' => 2,
                    'md' => 2,
                    'sm' => 2,
                    'xs' => 2,
                    'xxs' => 1,
                );
                break;
            case(1):
                $responsive = array(
                    'xl' => 1,
                    'lg' => 1,
                    'md' => 1,
                    'sm' => 1,
                    'xs' => 1,
                    'xxs' => 1,
                );
                break;
        }
        return $responsive;
    }
    public function getListLanguages()
    {
        $languages = Language::getLanguages( true, $this->context->shop->id );
        
        if( count( $languages ) < 2 ){
            return;
        }
        
        foreach ( $languages as &$lang ) {
            $lang['name_simple'] = preg_replace( '/\s\(.*\)$/', '', $lang['name'] );
            $lang['url'] = $this->context->link->getLanguageLink($lang['id_lang']);
        }
                
        $params = [
            'languages' => $languages,
            'current_language' => [
                'id_lang' => $this->context->language->id,
                'name' => $this->context->language->name,
                'name_simple' => preg_replace( '/\s\(.*\)$/', '', $this->context->language->name ),
                'iso_code' => $this->context->language->iso_code
            ]
        ];

        return $params;
    }
    public function getListCurrencies()
    {
        if( Configuration::isCatalogMode() || !Currency::isMultiCurrencyActivated() ) {
            return;
        }
        
        $current_currency = null;
        $serializer = new ObjectPresenter();
        $currencies = array_map(
            function ($currency) use ($serializer, &$current_currency) {                
                $currencyArray = $serializer->present($currency);

                $currencyArray['sign'] = $currency->sign;

                $url = $this->context->link->getLanguageLink($this->context->language->id);

                $parsedUrl = parse_url($url);
                $urlParams = [];
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $urlParams);
                }
                $newParams = array_merge(
                    $urlParams,
                    [
                        'SubmitCurrency' => 1,
                        'id_currency' => $currency->id,
                    ]
                );
                $newUrl = sprintf('%s://%s%s%s?%s',
                    $parsedUrl['scheme'],
                    $parsedUrl['host'],
                    isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '',
                    $parsedUrl['path'],
                    http_build_query($newParams)
                );

                $currencyArray['url'] = $newUrl;

                if ($currency->id === $this->context->currency->id) {
                    $currencyArray['current'] = true;
                    $current_currency = $currencyArray;
                } else {
                    $currencyArray['current'] = false;
                }

                return $currencyArray;
            },
            Currency::getCurrencies(true, true)
        );
                
        $params = [
            'currencies' => $currencies,
            'current_currency' => $current_currency,
        ];

        return $params; 
    }
    public function getCMSPages($id_cms_category = 1, $id_shop = false, $id_lang = false)	
    {	
        $output = [];	
        $id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;	
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;	
        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`	
            FROM `'._DB_PREFIX_.'cms` c	
            INNER JOIN `'._DB_PREFIX_.'cms_shop` cs	
            ON (c.`id_cms` = cs.`id_cms`)	
            INNER JOIN `'._DB_PREFIX_.'cms_lang` cl	
            ON (c.`id_cms` = cl.`id_cms` AND cs.`id_shop` = cl.`id_shop`)	
            WHERE c.`id_cms_category` = '.(int)$id_cms_category.'	
            AND cl.`id_shop` = '.(int)$id_shop.'	
            AND cl.`id_lang` = '.(int)$id_lang.'	
            AND c.`active` = 1	
            ORDER BY `position`';	
        $pages = Db::getInstance()->executeS($sql);	
        foreach ($pages as $page){	
            $output[$page['id_cms']] = (isset($spacer) ? $spacer : '').$page['meta_title'];	
        } 	
        return $output;	
    }	
    	
    public function getPagesOption($id_lang = null)	
    {	
        $context = Context::getContext();	
        $output = [];	
        if (is_null($id_lang)) $id_lang = (int)$context->cookie->id_lang;	
        $contact = Meta::getMetaByPage('contact', $id_lang);	
        if($contact){	
            $output[$contact['page']] = $contact['title'];	
        };	
        $sitemap = Meta::getMetaByPage('sitemap', $id_lang);	
        if($sitemap){	
            $output[$sitemap['page']] = $sitemap['title'];	
        };	
        $stores = Meta::getMetaByPage('stores', $id_lang);	
        if($stores){	
            $output[$stores['page']] = $stores['title'];	
        };	
        $myaccount = Meta::getMetaByPage('my-account', $id_lang);	
        if($myaccount){	
            $output[$myaccount['page']] = $myaccount['title'];	
        };	
        $pricesDrop = Meta::getMetaByPage('prices-drop', $id_lang);	
        if($pricesDrop){	
            $output[$pricesDrop['page']] = $pricesDrop['title'];	
        };	
        $newProduct = Meta::getMetaByPage('new-products', $id_lang);	
        if($newProduct){	
            $output[$newProduct['page']] = $newProduct['title'];	
        };	
        $bestSales = Meta::getMetaByPage('best-sales', $id_lang);	
        if($bestSales){	
            $output[$bestSales['page']] = $bestSales['title'];	
        };	
        return $output;	
    }
}	