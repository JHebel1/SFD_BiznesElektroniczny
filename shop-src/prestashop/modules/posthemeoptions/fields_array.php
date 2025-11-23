<?php

$headerTemplates = $homeTemplates = $footerTemplates = $this->getAllTemplates();
array_unshift($headerTemplates,['id_ce_template' => 0 , 'title' => '-- Hook "DisplayHeaderBuilder" --']);
array_unshift($homeTemplates,['id_ce_template' => 0 , 'title' => '-- Hook "DisplayHomeBuilder" --']);
array_unshift($footerTemplates,['id_ce_template' => 0 , 'title' => '-- Hook "DisplayFooterBuilder" --']);

// start tab general
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('General'),
        'icon' => 'icon-cogs'
    ),
    'input' => array(
        array(
            'type' => 'infoheading',
            'label' => $this->l('Layout'),
            'name'=> 'layout'
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Layout'),
            'name' => 'layout',
            'options' => array (
                'query' => array(
                    '1' => array(
                        'id' => 'wide',
                        'name' => 'wide'
                    ),
					'2' => array(
                        'id' => 'boxed', 
                        'name' => 'boxed'
                    ),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Container max width'),
            'name' => 'container_width',
            'class' => 'fixed-width-md',
            'desc' => $this->l('Set maxium width of page. You must provide px or percent suffix (example 1240px or 100%)'),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Boxed width'),
            'name' => 'boxed_width',
            'class' => 'fixed-width-md',
            'desc' => $this->l('Set width of boxed layout. You must provide px or percent suffix (example 1240px or 100%)'),
        ),
		array(
            'type' => 'select',
            'label' => $this->l('Sidebar width'),
            'name' => 'sidebar',
            'options' => array (
                'query' => array(
                    '1' => array(
                        'id' => 'normal',
                        'name' => 'normal'
                    ),
					'2' => array(
                        'id' => 'narrow', 
                        'name' => 'narrow'
                    ),
                    '3' => array(
                        'id' => 'wide',
                        'name' => 'wide'
                    ),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Body font & color'),
            'name'=> 'body'
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Main color'),
            'name' => 'g_main_color',
        ), 
        array(
            'type' => 'text',
            'label' => $this->l('Google font URL'),
            'name' => 'g_body_gfont_url',
            'desc' => $this->l('Example: https://fonts.googleapis.com/css?family=Open+Sans:400,700 Add 400 and 700 font weigh if exist. If you need adds latin-ext or cyrilic too. Go to '). '<a href="https://www.google.com/fonts" target="_blank">'.$this->l('Google font').'</a> to get font URL',
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Google font name'),
            'name' => 'g_body_gfont_name',
            'desc' => $this->l('Example: \'Montserrat\', sans-serif'),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Body font size'),
            'name' => 'g_body_font_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Body color'),
            'name' => 'g_body_font_color',
        ), 
        array(
            'type' => 'infoheading',
            'label' => $this->l('Body background'),
            'name'=> 'bodybg'
        ), 
		 array(
            'type' => 'switch',
            'label' => $this->l('Dark theme'),
            'name' => 'g_dark',
            'class' => 'fixed-width-xs',
            'values' => array(
                array(
                    'id' => 'g_dark_on',
                    'value' => 1,
                    'label' => $this->l('Yes') 
                    ),
                array(
                    'id' => 'g_dark_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            ),
            'desc' => $this->l('if you choose dark theme please change background body to dark color')
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Body background color'),
            'name' => 'g_body_bg_color',
        ),
        array(
            'type' => 'filemanager',
            'label' => $this->l('Body background image'),
            'name' => 'g_body_bg_image',
        ), 
        array(
            'type' => 'select',
            'label' => $this->l('Body background repeat'),
            'name' => 'g_body_bg_repeat',
            'options' => array (
                'query' => array(
                    '1' => array(
                        'id' => 'x',
                        'name' => 'Repeat X'
                    ),
					'2' => array(
                        'id' => 'y', 
                        'name' => 'Repeat Y'
                    ),
                    '3' => array(
                        'id' => 'xy', 
                        'name' => 'Repeat both'
                    ),
                    '4' => array(
                        'id' => 'none', 
                        'name' => 'Repeat none'
                    ),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Body background attachment'),
            'name' => 'g_body_bg_attachment',
            'options' => array (
                'query' => array(
                    '1' => array(
                        'id' => 'scroll',
                        'name' => 'Scroll'
                    ),
					'2' => array(
                        'id' => 'fixed', 
                        'name' => 'Fixed'
                    ),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Body background size'),
            'name' => 'g_body_bg_size',
            'options' => array (
                'query' => array(
                    '1' => array(
                        'id' => 'auto',
                        'name' => 'Auto'
                    ),
					'2' => array(
                        'id' => 'cover', 
                        'name' => 'Cover'
                    ),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Title block'),
            'name' => 'heading_content'
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Title block- Google font URL'),
            'name' => 'g_title_gfont_url',
            'desc' => $this->l('Example: https://fonts.googleapis.com/css?family=Open+Sans:400,700 Add 400 and 700 font weigh if exist. If you need adds latin-ext or cyrilic too. Go to '). '<a href="https://www.google.com/fonts" target="_blank">'.$this->l('Google font').'</a> to get font URL',
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Title block- Google font name'),
            'name' => 'g_title_gfont_name',
            'desc' => $this->l('Example: \'Montserrat\', sans-serif'),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Title block font size'),
            'name' => 'g_title_font_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Title block color'),
            'name' => 'g_title_font_color',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Title block transform'),
            'name' => 'g_title_font_transform',
            'options' => array (
                'query' => self::$text_transform,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
		array(
            'type' => 'select',
            'label' => $this->l('Title block font weight'),
            'name' => 'g_title_font_weight',
            'options' => array (
                'query' => self::$text_font_weight,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Title block fontsize in column'),
            'name' => 'g_title_font_size_column',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Header'),
        'icon' => 'icon-header'
    ),
    'input' => array(
        array(
            'type' => 'select2',
            'label' => $this->l('Header template'),
            'name' => 'header_template',
            'options' => array (
                'query' => $headerTemplates,
                'id' => 'id_ce_template',
                'name' => 'title'
            ),
            'class' => 'fixed-width-400',
            'desc' => $this->l('Use Hook "displayHeaderBuilder" to be able to assign a layout via "Content Anywhere" option of "Creative Elements".').'<br>'.$this->l('Use Hook "displayHeaderBuilder" when you use multi-languages'),
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Header sticky'),
            'name' => 'header_sticky',
            'class' => 'fixed-width-xs',
            'values' => array(
                array(
                    'id' => 'header_sticky_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'header_sticky_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            ),
            'desc' => $this->l('Add "sticky-inner" class to section element to make it sticky')
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Sticky background'),
            'name' => 'sticky_background',
        ),

    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Homepage'),
        'icon' => 'icon-home'
    ),
    'input' => array(
        array(
            'type' => 'select2',
            'label' => $this->l('Homepage template'),
            'name' => 'home_template',
            'options' => array (
                'query' => $homeTemplates,
                'id' => 'id_ce_template',
                'name' => 'title'
            ),
            'class' => 'fixed-width-400',
            'desc' => $this->l('Use Hook "displayHomeBuilder" to be able to assign a layout via "Content Anywhere" option of "Creative Elements".').'<br>'.$this->l('Use Hook "displayHomeBuilder" when you use multi-languages'),
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Footer'),
        'icon' => 'icon-outdent'
    ),
    'input' => array(
        array(
            'type' => 'select2',
            'label' => $this->l('Footer template'),
            'name' => 'footer_template',
            'options' => array (
                'query' => $footerTemplates,
                'id' => 'id_ce_template',
                'name' => 'title'
            ),
            'class' => 'fixed-width-400',
            'desc' => $this->l('Use Hook "displayFooterBuilder" to be able to assign a layout via "Content Anywhere" option of "Creative Elements".').'<br>'.$this->l('Use Hook "displayFooterBuilder" when you use multi-languages'),
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Page title'),
        'icon' => 'icon-credit-card'
    ),
    'input' => array(
        array(
            'type' => 'filemanager',
            'label' => $this->l('Background image'),
            'name' => 'ptitle_bg_image',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Text color'),
            'name' => 'ptitle_color',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Page title size'),
            'name' => 'ptitle_size',
            'options' => array (
                'query' =>[
                    1 => ['id' => 'small', 'name' => 'Small'],
                    2 => ['id' => 'default', 'name' => 'Default'],
                    3 => ['id' => 'big', 'name' => 'Big'],
                ],
                'id' => 'id',
                'name' => 'name'
            ),
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Product grid settings'),
        'icon' => 'icon-th'
    ),
    'input' => array(
        array(
            'type' => 'image-select',
            'label' => $this->l('Product grid display'),
            'name' => 'p_display',
            'default_value' => 1,
			'class' => 'item_box',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Grid 1'),
                        'img' => 'img1.png',
                        ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Grid 2'),
                        'img' => 'img2.png',
                        ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Grid 3'),
                        'img' => 'img3.png',
                        ),
                    array(
                        'id_option' => 4,
                        'name' => $this->l('Grid 4'),
                        'img' => 'img4.png',
                        ),
					array(
                        'id_option' => 5,
                        'name' => $this->l('Grid 5'),
                        'img' => 'img5.png',
                        ),	
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Use no border'),
            'name' => 'p_border',
            'class' => 'fixed-width-xs',
            'values' => array(
                array(
                    'id' => 'p_border_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'p_border_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            ),
            'desc' => $this->l('Border arround product box')
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Use no padding'),
            'name' => 'p_padding',
            'class' => 'fixed-width-xs',
            'values' => array(
                array(
                    'id' => 'p_padding_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'p_padding_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            ),
            'desc' => $this->l('There is no padding between products')
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Product name color'),
            'name' => 'p_name_color',
        ), 
        array(
            'type' => 'color2',
            'label' => $this->l('Product name color hover'),
            'name' => 'p_name_colorh',
        ), 
        array(
            'type' => 'text',
            'label' => $this->l('Product name font size'),
            'name' => 'p_name_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'radio',
            'label' => $this->l('Product name length'),
            'name' => 'p_name_length',
            'default_value' => 0,
            'values' => array(
                array(
                    'id' => 'p_name_length_0',
                    'value' => 0,
                    'label' => $this->l('1 line, product name is cut.'),
                    ),
                array(
                    'id' => 'p_name_length_1',
                    'value' => 1,
                    'label' => $this->l('2 lines, product name is full'),
                    ),

            ),
            'validation' => 'isUnsignedInt',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Product name transform'),
            'name' => 'p_name_transform',
            'options' => array (
                'query' => self::$text_transform,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Price color'),
            'name' => 'p_price_color',
        ),  
        array(
            'type' => 'text',
            'label' => $this->l('Price font size'),
            'name' => 'p_price_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Category page settings'),
        'icon' => 'icon-list-alt'
    ),
    'input' => array(
        array(
            'type' => 'image-select',
            'label' => $this->l('Category layout'),
            'name' => 'cp_layout',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Left column & filter in left column'),
                        'img' => 'category1.jpg'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('No column & filter in top'),
                        'img' => 'category2.jpg'
                    ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
			'desc' => $this->l('Go to Design > Theme & Logo > Choose Layouts to manage category page layout'),
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Show subcategories'),
            'name' => 'cp_subcategories',
            'class' => 'fixed-width-xs',
            'desc' => $this->l(''),
            'values' => array(
                array(
                    'id' => 'cp_subcategories_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'cp_subcategories_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            )
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Product per page'),
            'name' => 'PS_PRODUCTS_PER_PAGE',
        ), 
        array(
            'type' => 'select',
            'label' => $this->l('Number product per row'),
            'name' => 'cp_perrow',
            'options' => array (
                'query' =>self::$product_row,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Product page settings'),
        'icon' => 'icon-archive'
    ),
    'input' => array(
    	array(
            'type' => 'infoheading',
            'label' => $this->l('Product page layout'),
            'name'=> 'ppage'
        ),
    	array(
            'type' => 'image-select',
            'label' => $this->l('Product detail layout'),
            'name' => 'productp_layout',
            'direction' => 'vertical',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Layout 1'),
                        'img' => 'product1.jpg'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Layout 2'),
                        'img' => 'product2.jpg'
                    ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Layout 3'),
                        'img' => 'product3.jpg'
                    ),
                    array(
                        'id_option' => 4,
                        'name' => $this->l('Layout 4'),
                        'img' => 'product4.jpg'
                    ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
        ),
        array(
        	'type' => 'wrapper_open',
        	'class' => 'productp-layout1 productp-layout3 productp-layout'
        ),
        array(
            'type' => 'radio',
            'label' => $this->l('Product thumbnails on desktop devices:'),
            'name' => 'ppl1_thumbnail',
            'default_value' => 0,
            'values' => array(
                array(
                    'id' => 'product_thumbnails_0',
                    'value' => 0,
                    'label' => $this->l('Default'),
                    ),
                array(
                    'id' => 'product_thumbnails_1',
                    'value' => 1,
                    'label' => $this->l('Left side vertical slider'),
                    ),
                array(
                    'id' => 'product_thumbnails_2',
                    'value' => 2,
                    'label' => $this->l('Right side vertical slider'),
                    ),
            ),
            'icon_path' => $this->_path,
            'validation' => 'isUnsignedInt',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Number of thumbnails'),
            'name' => 'ppl1_items',
            'options' => array (
                'query' => array(
                	1 => array('id' =>3 , 'name' => '3'),
       				2 => array('id' =>4 , 'name' => '4'),
       				3 => array('id' =>5 , 'name' => '5'),
       				4 => array('id' =>6 , 'name' => '6'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
        	'type' => 'wrapper_open',
        	'class' => 'productp-layout2 productp-layout'
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Column'),
            'name' => 'ppl2_column',
            'options' => array (
                'query' => array(
                	1 => array('id' =>1 , 'name' => '1'),
       				2 => array('id' =>2 , 'name' => '2'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
            'type' => 'wrapper_open',
            'class' => 'productp-layout3 productp-layout'
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Background color'),
            'name' => 'productp_background',
        ),
        array(
            'type' => 'wrapper_close',
        ),
        array(
        	'type' => 'wrapper_open',
        	'class' => 'productp-layout4 productp-layout'
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Number of image on screen'),
            'name' => 'ppl3_items',
            'options' => array (
                'query' => array(
                	1 => array('id' =>2 , 'name' => '2'),
       				2 => array('id' =>3 , 'name' => '3'),
       				3 => array('id' =>4 , 'name' => '4'),
       				4 => array('id' =>5 , 'name' => '5'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Configurations'),
            'name'=> 'ppagec'
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Product name color'),
            'name' => 'pp_name_color',
        ), 
        array(
            'type' => 'text',
            'label' => $this->l('Product name font size'),
            'name' => 'pp_name_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Product name transform'),
            'name' => 'pp_name_transform',
            'options' => array (
                'query' => self::$text_transform,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Price color'),
            'name' => 'pp_price_color',
        ),  
        array(
            'type' => 'text',
            'label' => $this->l('Price font size'),
            'name' => 'pp_price_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'radio',
            'label' => $this->l('Product infomation tab display'),
            'name' => 'pp_infortab',
            'default_value' => 0,
            'values' => array(
                array(
                    'id' => 'product_thumbnails_0',
                    'value' => 0,
                    'label' => $this->l('Horizontal tab'),
                    ),
                array(
                    'id' => 'product_thumbnails_1',
                    'value' => 1,
                    'label' => $this->l('Vertical tab'),
                    ),
                array(
                    'id' => 'product_thumbnails_2',
                    'value' => 2,
                    'label' => $this->l('Accordion'),
                    ),
            ),
            'icon_path' => $this->_path,
            'validation' => 'isUnsignedInt',
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )

);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Custom CSS/JS'),
        'icon' => 'icon-pencil-square'
    ),
    'input' => array(
        array(
            'type' => 'customtextarea',
            'name' => 'custom_css',
            'rows' => 15,
            'label' => $this->l('Custom CSS'),
            'required' => false,
            'lang' => false
        ),
        array(
            'type' => 'customtextarea',
            'name' => 'custom_js',
            'rows' => 15,
            'label' => $this->l('Custom JS'),
            'required' => false,
            'lang' => false
        )
        
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Demo setup'),
        'icon' => 'icon-cloud-download'
    ),
    'input' => array(
        array(
            'type' => 'posthemes',
            'label' => $this->l('Demo setup'),
            'name'=> 'posthemes'
        ),
    ),
    
);