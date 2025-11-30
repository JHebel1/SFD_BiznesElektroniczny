<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

define('_MODULE_SMARTBLOG_VERSION_', '4.1.1');
define('_MODULE_SMARTBLOG_DIR_', _PS_MODULE_DIR_ . 'smartblog/images/');
define('_MODULE_SMARTBLOG_URL_', _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . '/modules/' . 'smartblog/');
define('_MODULE_SMARTBLOG_IMAGE_URL_', _MODULE_SMARTBLOG_URL_ . 'images/');
define('_MODULE_SMARTBLOG_GALLARY_DIR_', _PS_MODULE_DIR_ . 'smartblog/gallary/');
define('_MODULE_SMARTBLOG_JS_DIR_', _PS_MODULE_DIR_ . 'smartblog/views/js/');
define('_MODULE_SMARTBLOG_CLASS_DIR_', _PS_MODULE_DIR_ . 'smartblog/classes/');

require_once dirname(__FILE__) . '/classes/BlogCategory.php';
require_once dirname(__FILE__) . '/classes/BlogImageType.php';
require_once dirname(__FILE__) . '/classes/BlogTag.php';
require_once dirname(__FILE__) . '/classes/SmartBlogPost.php';
require_once dirname(__FILE__) . '/classes/SmartBlogHelperTreeCategories.php';
require_once dirname(__FILE__) . '/classes/Blogcomment.php';
require_once dirname(__FILE__) . '/classes/BlogPostCategory.php';
require_once dirname(__FILE__) . '/classes/SmartBlogLink.php';
class smartblog extends Module
{
	public $nrl;
	public $crl;
	public $erl;
	public $capl;
	public $warl;
	public $sucl;

	public function __construct()
	{

		$this->name          = 'smartblog';
		$this->tab           = 'front_office_features';
		$this->version       = '4.1.1';
		$this->author        = 'SmartDataSoft';
		$this->need_upgrade  = true;
		$this->controllers   = array('category', 'details', 'search', 'tagpost', "archivemonth","list");
		$this->secure_key    = Tools::encrypt($this->name);
		$this->smart_shop_id = Context::getContext()->shop->id;
		$this->bootstrap     = true;
		parent::__construct();
		$this->displayName = $this->trans('Smart Blog', [], 'Modules.Smartblog.Smartblog');
		$this->nrl  = $this->trans('Name is required', [], 'Modules.Smartblog.Smartblog');
		$this->crl  = $this->trans('Comment must be between 25 and 1500 characters!', [], 'Modules.Smartblog.Smartblog');
		$this->erl  = $this->trans('E-mail address not valid !', [], 'Modules.Smartblog.Smartblog');
		$this->capl = $this->trans('Captcha is not valid', [], 'Modules.Smartblog.Smartblog');
		$this->warl = $this->trans('Warning: Please check required form bellow!', [], 'Modules.Smartblog.Smartblog');
		$this->sucl = $this->trans('Your comment successfully submitted.', [], 'Modules.Smartblog.Smartblog');
		$this->description      = $this->trans('The Most Powerfull Prestashop Blog  Module - by smartdatasoft', [], 'Modules.Smartblog.Smartblog');
		$this->confirmUninstall = $this->trans('Are you sure you want to delete your details ?', [], 'Modules.Smartblog.Smartblog');
		$this->module_key       = '5679adf718951d4bc63422b616a9d75d';

	}

	public function install()
	{

		Configuration::updateGlobalValue('smartblogrootcat', '1');
		Configuration::updateGlobalValue('smartpostperpage', '5');
		Configuration::updateGlobalValue('sborderby', 'id_smart_blog_post');
		Configuration::updateGlobalValue('sborder', 'DESC');
		Configuration::updateGlobalValue('smartshowauthorstyle', '1');
		Configuration::updateGlobalValue('smartshowauthor', '1');
		Configuration::updateGlobalValue('smartmainblogurl', 'smartblog');
		Configuration::updateGlobalValue('smartusehtml', '1');
		Configuration::updateGlobalValue('smartshowauthorstyle', '1');
		Configuration::updateGlobalValue('smartenablecomment', '1');
		Configuration::updateGlobalValue('smartenableguestcomment', '1');
		Configuration::updateGlobalValue('smartcaptchaoption', '1');
		Configuration::updateGlobalValue('smartshowviewed', '1');
		Configuration::updateGlobalValue('smartshownoimg', '1');
		Configuration::updateGlobalValue('smartsearchengine', '1');
		Configuration::updateGlobalValue('smartshowcolumn', '3');
		Configuration::updateGlobalValue('smartacceptcomment', '1');
		Configuration::updateGlobalValue('smartcustomcss', '');
		Configuration::updateGlobalValue('smartdisablecatimg', '1');
		Configuration::updateGlobalValue('smartdataformat', 'm/d/Y H:i:s');
		Configuration::updateGlobalValue('smartblogurlpattern', 1);
		Configuration::updateGlobalValue('smartblogmetatitle', 'Smart Blog Title');
		Configuration::updateGlobalValue('smartblogmetakeyword', 'smart,blog,smartblog,prestashop blog,prestashop,blog');
		Configuration::updateGlobalValue('smartblogmetadescrip', 'Prestashop powerfull blog site developing module. It has hundrade of extra plugins. This module developed by SmartDataSoft.com');
		Configuration::updateGlobalValue('smartshowhomepost', 4);

		$ret  = (bool) parent::install();
		$ret &= $this->addquickaccess();
		$ret &= $this->htaccessCreate();
		$ret &= $this->registerHook('displayHeader') &&
			$this->registerHook('moduleRoutes') &&
			$this->registerHook('displayBackOfficeHeader');

		$ret &= $this->installSql();
		$ret &= $this->CreateSmartBlogTabs();
		$ret &= $this->requiredDataInstall();
		$ret &= $this->sampleDataInstall();
		$ret &= $this->installDummyData();

		// Later Will Be Fine Tuned
		// *************************************

		$ret &= $this->SmartHookInsert();
		$ret &= $this->SmartHookRegister();
		return true;
	}

	public function isUsingNewTranslationSystem(){
		return true;
	}

	protected function installSql()
	{
		$sql = array();
		include_once dirname(__FILE__) . '/sql/install.php';
		foreach ($sql as $sq) :
			if (!Db::getInstance()->Execute($sq)) {
				return false;
			}
		endforeach;
		return true;
	}

	public function installDummyData()
	{
		$image_types         = BlogImageType::GetImageAllType('post');
		$id_smart_blog_posts = $this->getAllPost();

		$tmp_name            = tempnam(_PS_TMP_IMG_DIR_, 'PS');
		$langs               = Language::getLanguages();
		$arrayImg = array();
		foreach (scandir(__DIR__ . '/dummy_data') as $images) {
			if (in_array($images, array('.', '..', '.DS_Store'))) {
				continue;
			}
			$arrayImg[] = $images;
		}

		$img_count = 0;
		$dummy_post_ids = array();
		foreach ($id_smart_blog_posts as $id_smart_blog_post) {
			$dummy_post_ids[] = $id_smart_blog_post['id_smart_blog_post'];
		}


		$dummy_post_ids = array_unique($dummy_post_ids);

		foreach ($dummy_post_ids as $id_smart_blog_post) {
			$files_to_delete = array();
			$files_to_delete[] = _PS_TMP_IMG_DIR_ . 'smart_blog_post_' . $id_smart_blog_post . '.jpg';
			$files_to_delete[] = _PS_TMP_IMG_DIR_ . 'smart_blog_post_mini_' . $id_smart_blog_post . '.jpg';
			foreach ($langs as $l) {
				$files_to_delete[] = _PS_TMP_IMG_DIR_ . 'smart_blog_post_' . $id_smart_blog_post . '_' . $l['id_lang'] . '.jpg';
				$files_to_delete[] = _PS_TMP_IMG_DIR_ . 'smart_blog_post_mini_' . $id_smart_blog_post . '_' . $l['id_lang'] . '.jpg';
			}
			foreach ($files_to_delete as $file) {
				if (file_exists($file)) {
					@unlink($file);
				}
			}
			if (isset($arrayImg[$img_count])) {
				Tools::Copy(__DIR__ . '/dummy_data/' . $arrayImg[$img_count], _PS_MODULE_DIR_ . '/smartblog/images/' . $id_smart_blog_post . '.jpg');
				foreach ($image_types as $image_type) {
					ImageManager::resize(
						__DIR__ . '/dummy_data/' . $arrayImg[$img_count],
						_PS_MODULE_DIR_ . 'smartblog/images/' . $id_smart_blog_post . '-' . stripslashes($image_type['type_name']) . '.jpg',
						(int) $image_type['width'],
						(int) $image_type['height']
					);
				}
			}
			$img_count = (count($arrayImg) > $img_count) ? $img_count + 1 : 0;
		}
		Tools::Copy(__DIR__ . '/dummy_data/no.jpg', _PS_MODULE_DIR_ . '/smartblog/images/no.jpg');
		foreach ($image_types as $image_type) {
			ImageManager::resize(
				__DIR__ . '/dummy_data/no.jpg',
				_PS_MODULE_DIR_ . 'smartblog/images/no-' . stripslashes($image_type['type_name']) . '.jpg',
				(int) $image_type['width'],
				(int) $image_type['height']
			);
		}
	}

	public static function getAllPost()
	{
		$sql = 'SELECT p.id_smart_blog_post  FROM `' . _DB_PREFIX_ . 'smart_blog_post_lang` p';
		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
			return false;
		}
		return $result;
	}

	public function hookactionHtaccessCreate()
	{
		$content = file_get_contents(_PS_ROOT_DIR_ . '/.htaccess');
		if (!preg_match('/\# Images Blog\n/', $content)) {
			$content = preg_replace_callback('/\# Images\n/', array($this, 'updateSiteHtaccess'), $content);
			@file_put_contents(_PS_ROOT_DIR_ . '/.htaccess', $content);
		}
	}

	public function hookDisplayBackOfficeHeader($params)
	{
		$this->context->controller->addJquery();
		$this->context->controller->addCSS($this->_path . 'views/css/admin.css');
	}

	public function hookDisplayHeader($params)
	{
		//$this->context->controller->addCSS($this->_path . 'views/css/fw.css');
		$this->context->controller->addCSS($this->_path . 'views/css/smartblogstyle.css', 'all');
		$smartblogurlpattern = (int) Configuration::get('smartblogurlpattern');
		$id_post             = null;
		switch ($smartblogurlpattern) {
			case 1:
				$slug    = Tools::getValue('slug');
				$id_post = self::slug2id($slug);
				break;
			case 2:
				$id_post = pSQL(Tools::getvalue('id_post'));
				break;
			case 3:
				$id_post = pSQL(Tools::getvalue('id_post'));
				break;
			default:
				$id_post = pSQL(Tools::getvalue('id_post'));
		}
		if ($id_post) {
			$obj_post         = new SmartBlogPost($id_post, true, $this->context->language->id, $this->context->shop->id);
			$meta_title       = $obj_post->meta_title;
			$meta_keyword     = $obj_post->meta_keyword;
			$meta_description = $obj_post->meta_description;
		} else {
			$meta_title       = Configuration::get('smartblogmetatitle');
			$meta_keyword     = Configuration::get('smartblogmetakeyword');
			$meta_description = Configuration::get('smartblogmetadescrip');
		}
	}

	public function htaccessCreate()
	{
		$content = file_get_contents(_PS_ROOT_DIR_ . '/.htaccess');
		if (!preg_match('/\# Images Blog\n/', $content)) {
			$content = preg_replace_callback('/\# Images\n/', array($this, 'updateSiteHtaccess'), $content);
			@file_put_contents(_PS_ROOT_DIR_ . '/.htaccess', $content);
		}
		return true;
	}

	public function updateSiteHtaccess($match)
	{
		$htupdate = '';
		include_once dirname(__FILE__) . '/htupdate.php';
		$str = '';
		if (isset($match[0])) {
			$str .= "\n{$htupdate}\n\n{$match[0]}\n";
		}
		return $str;
	}

	public function addquickaccess()
	{
		$link      = new Link();
		$qa        = new QuickAccess();
		$qa->link  = $link->getAdminLink('AdminModules') . '&configure=smartblog';
		$languages = Language::getLanguages(false);
		foreach ($languages as $language) {
			$qa->name[$language['id_lang']] = 'Smart Blog Setting';
		}
		$qa->new_window = '0';
		if ($qa->save()) {
			Configuration::updateValue('smartblog_quick_access', $qa->id);
			return true;
		}
	}

	protected function CreateSmartBlogTabs()
	{

		$postabID = Tab::getIdFromClassName('PosThemeMenu');
		$langs                = Language::getLanguages();
		$smarttab             = new Tab();
		$smarttab->class_name = 'SMARTBLOG';
		$smarttab->module     = '';
		$smarttab->id_parent  = $postabID;
		foreach ($langs as $l) {
			$smarttab->name[$l['id_lang']] = $this->trans('Blog', [], 'Modules.Smartblog.Smartblog');
		}
		$smarttab->icon = 'announcement';
		$smarttab->save();
		$tab_id = $smarttab->id;
		@copy(dirname(__FILE__) . '/views/img/AdminSmartBlog.gif', _PS_ROOT_DIR_ . '/img/t/AdminSmartBlog.gif');

		$tabvalue = array();
		// assign tab value from include file
		include_once dirname(__FILE__) . '/sql/install_tab.php';
		foreach ($tabvalue as $tab) {
			$newtab             = new Tab();
			$newtab->class_name = $tab['class_name'];
			if ($tab['id_parent'] == -1) {
				$newtab->id_parent = $tab['id_parent'];
			} else {
				$newtab->id_parent = $tab_id;
			}
			$newtab->icon = NULL;
			$newtab->module = $tab['module'];
			foreach ($langs as $l) {
				$newtab->name[$l['id_lang']] = $this->trans($tab['name'], [], 'Modules.Smartblog.Smartblog');
			}
			$newtab->save();
		}
		return true;
	}

	public function requiredDataInstall()
	{
		$ret  = true;
		$ret &= Db::getInstance()->execute(
			'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_category` (`id_parent`,`level_depth`,`position`,`active`,`created`) VALUES (0,0,0,1,NOW())'
		);

		$ret &= Db::getInstance()->execute(
			'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_category_shop` (`id_smart_blog_category`,`id_shop`) VALUES (1,' . (int) $this->smart_shop_id . ')'
		);

		$languages = Language::getLanguages(false);
		foreach ($languages as $language) {
			$ret &= Db::getInstance()->execute(
				'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_category_lang` (`id_smart_blog_category`,`name`,`meta_title`,`id_lang`,`link_rewrite`) VALUES (1,"Home","Home",' . (int) $language['id_lang'] . ",'home')"
			);
		}

		for ($i = 1; $i <= 3; $i++) {
			if ($i == 1) :
				$type_name = 'home-default';
				$width     = '470';
				$height    = '289';
				$type      = 'Category';
			elseif ($i == 2) :
				$type_name = 'single-default';
				$width     = '1050';
				$height    = '646';
				$type      = 'Category';
			elseif ($i == 3) :
				$type_name = 'author-default';
				$width     = '54';
				$height    = '54';
				$type      = 'Author';
			endif;
			$damiimgtype = 'INSERT INTO ' . _DB_PREFIX_ . "smart_blog_imagetype (type_name,width,height,type,active) VALUES ('" . $type_name . "','" . $width . "','" . $height . "','" . $type . "',1);";
			$ret        &= Db::getInstance()->execute($damiimgtype);
		}
		return $ret;
	}

	public function sampleDataInstall()
	{
		for ($i = 1; $i <= 4; $i++) {
			Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_post`(`id_author`, `id_category`, `position`, `active`, `available`, `created`, `viewed`, `comment_status`) VALUES(1,1,0,1,1,NOW(),0,1)');
		}

		$languages = Language::getLanguages(false);
		for ($i = 1; $i <= 4; $i++) {
			if ($i == 1) :
				$title = 'Eat fresh berries instead of dried ones';
				$slug  = 'eat-fresh-berries-instead-of-dried-ones';
				$des   = '<p>With Halloween creeping up and the weather starting to feel colder we want to inspire you to get a little bit creative in the kitchen this festive period, so weve treated you to a round-up of our favourite seasonal recipes from our most-loved foodie bloggers.</p>
				<p>Weve cherry picked a mix of sweet and savoury recipes, that were sure will get both adults and kids in the Halloween mood. However, if dressing up and going out trick or treating isnt your thing, then thats completely fine with us too, these recipes are just as perfect for a cosy autumnal night in.</p>
				<h4>What ever the nature of your Halloween.</h4>
				<p>It wouldnt be Halloween without a toffee apple. These sticky, gooey, delicious sticks of joy by Indy from The Little Green Spoon are made from coconut sugar, cashew butter and coconut milk. If youve got kids then this one is perfect for getting them involved. Set up a decorating station with all your favourite toppings and be prepared for a little bit of mess. Indy suggests: roasted nuts, cacao nibs, and desiccated coconut to decorate.</p>
				<p>A Global Web Index survey of internet users aged 16 to 64 found that the average amount of time spent using the web per day is now six hours and 42 minutes. This is a 1.7% decrease year-on-year, down from six hours and forty-nine minutes in January 2020.</p>
				<h4>UK marketers using influencers</h4>
				<p>A new global study from Rakuten Marketing has revealed that the proportion of marketing budget being allocated to influencer campaigns (by marketers working with influencers) has nearly doubled over the past two years, reaching 40% in 2020. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using lorem ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here making it look like readable english.</p>
				<h4>Complaints about data and privacy dominated 2019</h4>
				<p>According to the DM Commissions Annual Report, issues relating to data, privacy, and accuracy were the biggest concerns for consumers in 2020. In the year that GDPR came into force, the DM Commission reported a marked reduction in complaints against businesses in the direct marketing sector from over 200 in 2017 to just over 100 in 2020. The Commission did however investigate 27 cases involving a breach of the DMA Code, 83% of which related to data, privacy and quality (up from 69% last year). The remaining complaints were split between customer service and contractual issues.</p>
				<h4>Email spam decreased by 5%</h4>
				<p>By the time October comes around squash is in an abundance. This beautiful seasonal recipe by serial dinner party host Alexandra Dudley celebrates the squash in all its glory pairing it with a herby butterbean mash, which makes a nice change from the traditional potato. Serve this up to guests on an autumnal evening and were sure there ll be empty plates all round.</p>
				<h4>Complaints about data</h4>
				<p>We dont know about you but this is the kind of food we love. Full of seasonal veg and fragrant spices, this is the perfect bowl food after a long day. What makes this even better is youll probably have most of the ingredients in your kitchen already. Niki from @rebelrecipes finishes this dish off with a spoonful of coconut yogurt for creaminess and then adds a tahini dressing for even more flavour - delicious!</p>';
				$s_des =  'With Halloween creeping up and the weather starting to feel colder we want to inspire you to get a little bit creative in the kitchen this festive period, so weve treated you to a round-up of our favourite seasonal recipes from our most-loved foodie bloggers.';
			elseif ($i == 2) :
				$title = 'Eat your fruits instead of drinking them';
				$slug  = 'Eat-your-fruits-instead-of-drinking-them';
				$des   = '<p>With Halloween creeping up and the weather starting to feel colder we want to inspire you to get a little bit creative in the kitchen this festive period, so weve treated you to a round-up of our favourite seasonal recipes from our most-loved foodie bloggers.</p>
				<p>Weve cherry picked a mix of sweet and savoury recipes, that were sure will get both adults and kids in the Halloween mood. However, if dressing up and going out trick or treating isnt your thing, then thats completely fine with us too, these recipes are just as perfect for a cosy autumnal night in.</p>
				<h4>What ever the nature of your Halloween.</h4>
				<p>It wouldnt be Halloween without a toffee apple. These sticky, gooey, delicious sticks of joy by Indy from The Little Green Spoon are made from coconut sugar, cashew butter and coconut milk. If youve got kids then this one is perfect for getting them involved. Set up a decorating station with all your favourite toppings and be prepared for a little bit of mess. Indy suggests: roasted nuts, cacao nibs, and desiccated coconut to decorate.</p>
				<p>A Global Web Index survey of internet users aged 16 to 64 found that the average amount of time spent using the web per day is now six hours and 42 minutes. This is a 1.7% decrease year-on-year, down from six hours and forty-nine minutes in January 2020.</p>
				<h4>UK marketers using influencers</h4>
				<p>A new global study from Rakuten Marketing has revealed that the proportion of marketing budget being allocated to influencer campaigns (by marketers working with influencers) has nearly doubled over the past two years, reaching 40% in 2020. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using lorem ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here making it look like readable english.</p>
				<h4>Complaints about data and privacy dominated 2019</h4>
				<p>According to the DM Commissions Annual Report, issues relating to data, privacy, and accuracy were the biggest concerns for consumers in 2020. In the year that GDPR came into force, the DM Commission reported a marked reduction in complaints against businesses in the direct marketing sector from over 200 in 2017 to just over 100 in 2020. The Commission did however investigate 27 cases involving a breach of the DMA Code, 83% of which related to data, privacy and quality (up from 69% last year). The remaining complaints were split between customer service and contractual issues.</p>
				<h4>Email spam decreased by 5%</h4>
				<p>By the time October comes around squash is in an abundance. This beautiful seasonal recipe by serial dinner party host Alexandra Dudley celebrates the squash in all its glory pairing it with a herby butterbean mash, which makes a nice change from the traditional potato. Serve this up to guests on an autumnal evening and were sure there ll be empty plates all round.</p>
				<h4>Complaints about data</h4>
				<p>We dont know about you but this is the kind of food we love. Full of seasonal veg and fragrant spices, this is the perfect bowl food after a long day. What makes this even better is youll probably have most of the ingredients in your kitchen already. Niki from @rebelrecipes finishes this dish off with a spoonful of coconut yogurt for creaminess and then adds a tahini dressing for even more flavour - delicious!</p>';
				$s_des =  'With Halloween creeping up and the weather starting to feel colder we want to inspire you to get a little bit creative in the kitchen this festive period, so weve treated you to a round-up of our favourite seasonal recipes from our most-loved foodie bloggers.';
			elseif ($i == 3) :
				$title = 'Tips You To Balance Nutrition Meal Day';
				$slug  = 'tips-you-to-balance-nutrition-meal-day';
				$des   = '<p>With Halloween creeping up and the weather starting to feel colder we want to inspire you to get a little bit creative in the kitchen this festive period, so weve treated you to a round-up of our favourite seasonal recipes from our most-loved foodie bloggers.</p>
				<p>Weve cherry picked a mix of sweet and savoury recipes, that were sure will get both adults and kids in the Halloween mood. However, if dressing up and going out trick or treating isnt your thing, then thats completely fine with us too, these recipes are just as perfect for a cosy autumnal night in.</p>
				<h4>What ever the nature of your Halloween.</h4>
				<p>It wouldnt be Halloween without a toffee apple. These sticky, gooey, delicious sticks of joy by Indy from The Little Green Spoon are made from coconut sugar, cashew butter and coconut milk. If youve got kids then this one is perfect for getting them involved. Set up a decorating station with all your favourite toppings and be prepared for a little bit of mess. Indy suggests: roasted nuts, cacao nibs, and desiccated coconut to decorate.</p>
				<p>A Global Web Index survey of internet users aged 16 to 64 found that the average amount of time spent using the web per day is now six hours and 42 minutes. This is a 1.7% decrease year-on-year, down from six hours and forty-nine minutes in January 2020.</p>
				<h4>UK marketers using influencers</h4>
				<p>A new global study from Rakuten Marketing has revealed that the proportion of marketing budget being allocated to influencer campaigns (by marketers working with influencers) has nearly doubled over the past two years, reaching 40% in 2020. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using lorem ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here making it look like readable english.</p>
				<h4>Complaints about data and privacy dominated 2019</h4>
				<p>According to the DM Commissions Annual Report, issues relating to data, privacy, and accuracy were the biggest concerns for consumers in 2020. In the year that GDPR came into force, the DM Commission reported a marked reduction in complaints against businesses in the direct marketing sector from over 200 in 2017 to just over 100 in 2020. The Commission did however investigate 27 cases involving a breach of the DMA Code, 83% of which related to data, privacy and quality (up from 69% last year). The remaining complaints were split between customer service and contractual issues.</p>
				<h4>Email spam decreased by 5%</h4>
				<p>By the time October comes around squash is in an abundance. This beautiful seasonal recipe by serial dinner party host Alexandra Dudley celebrates the squash in all its glory pairing it with a herby butterbean mash, which makes a nice change from the traditional potato. Serve this up to guests on an autumnal evening and were sure there ll be empty plates all round.</p>
				<h4>Complaints about data</h4>
				<p>We dont know about you but this is the kind of food we love. Full of seasonal veg and fragrant spices, this is the perfect bowl food after a long day. What makes this even better is youll probably have most of the ingredients in your kitchen already. Niki from @rebelrecipes finishes this dish off with a spoonful of coconut yogurt for creaminess and then adds a tahini dressing for even more flavour - delicious!</p>';
				$s_des =  'With Halloween creeping up and the weather starting to feel colder we want to inspire you to get a little bit creative in the kitchen this festive period, so weve treated you to a round-up of our favourite seasonal recipes from our most-loved foodie bloggers.';
			elseif ($i == 4) :
				$title = 'Ways To Deodorize Vegetables And Fruits';
				$slug  = 'ways-to-deodorize-vegetables-and-fruits';
				$des   = '<p>With Halloween creeping up and the weather starting to feel colder we want to inspire you to get a little bit creative in the kitchen this festive period, so weve treated you to a round-up of our favourite seasonal recipes from our most-loved foodie bloggers.</p>
				<p>Weve cherry picked a mix of sweet and savoury recipes, that were sure will get both adults and kids in the Halloween mood. However, if dressing up and going out trick or treating isnt your thing, then thats completely fine with us too, these recipes are just as perfect for a cosy autumnal night in.</p>
				<h4>What ever the nature of your Halloween.</h4>
				<p>It wouldnt be Halloween without a toffee apple. These sticky, gooey, delicious sticks of joy by Indy from The Little Green Spoon are made from coconut sugar, cashew butter and coconut milk. If youve got kids then this one is perfect for getting them involved. Set up a decorating station with all your favourite toppings and be prepared for a little bit of mess. Indy suggests: roasted nuts, cacao nibs, and desiccated coconut to decorate.</p>
				<p>A Global Web Index survey of internet users aged 16 to 64 found that the average amount of time spent using the web per day is now six hours and 42 minutes. This is a 1.7% decrease year-on-year, down from six hours and forty-nine minutes in January 2020.</p>
				<h4>UK marketers using influencers</h4>
				<p>A new global study from Rakuten Marketing has revealed that the proportion of marketing budget being allocated to influencer campaigns (by marketers working with influencers) has nearly doubled over the past two years, reaching 40% in 2020. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using lorem ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here making it look like readable english.</p>
				<h4>Complaints about data and privacy dominated 2019</h4>
				<p>According to the DM Commissions Annual Report, issues relating to data, privacy, and accuracy were the biggest concerns for consumers in 2020. In the year that GDPR came into force, the DM Commission reported a marked reduction in complaints against businesses in the direct marketing sector from over 200 in 2017 to just over 100 in 2020. The Commission did however investigate 27 cases involving a breach of the DMA Code, 83% of which related to data, privacy and quality (up from 69% last year). The remaining complaints were split between customer service and contractual issues.</p>
				<h4>Email spam decreased by 5%</h4>
				<p>By the time October comes around squash is in an abundance. This beautiful seasonal recipe by serial dinner party host Alexandra Dudley celebrates the squash in all its glory pairing it with a herby butterbean mash, which makes a nice change from the traditional potato. Serve this up to guests on an autumnal evening and were sure there ll be empty plates all round.</p>
				<h4>Complaints about data</h4>
				<p>We dont know about you but this is the kind of food we love. Full of seasonal veg and fragrant spices, this is the perfect bowl food after a long day. What makes this even better is youll probably have most of the ingredients in your kitchen already. Niki from @rebelrecipes finishes this dish off with a spoonful of coconut yogurt for creaminess and then adds a tahini dressing for even more flavour - delicious!</p>';
				$s_des =  'With Halloween creeping up and the weather starting to feel colder we want to inspire you to get a little bit creative in the kitchen this festive period, so weve treated you to a round-up of our favourite seasonal recipes from our most-loved foodie bloggers.';
			endif;
			foreach ($languages as $language) {
				if (!Db::getInstance()->Execute(
					'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_post_lang`(`id_smart_blog_post`,`id_lang`,`meta_title`,`meta_description`,`short_description`,`content`,`link_rewrite`)
                        VALUES(' . (int) $i . ',' . (int) $language['id_lang'] . ', 
							"' . htmlspecialchars($title) . '", 
							"' . $s_des . '","' . $s_des . '","' . $des . '","' . $slug . '"
						)'
				)) {
					return false;
				}
			}
		}

		for ($i = 1; $i <= 4; $i++) {
			Db::getInstance()->Execute(
				'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_post_shop`(`id_smart_blog_post`, `id_shop`) 
        VALUES(' . (int) $i . ',' . (int) $this->smart_shop_id . ')'
			);
		}
		for ($i = 1; $i <= 4; $i++) {
			if ($i == 1) :
				$type_name = 'home-default';
				$width     = '470';
				$height    = '289';
				$type      = 'post';
			elseif ($i == 2) :
				$type_name = 'home-small';
				$width     = '200';
				$height    = '123';
				$type      = 'post';
			elseif ($i == 3) :
				$type_name = 'single-default';
				$width     = '1410';
				$height    = '868';
				$type      = 'post';
			elseif ($i == 4) :
				$type_name = 'author-default';
				$width     = '54';
				$height    = '54';
				$type      = 'Author';
			endif;
			$damiimgtype = 'INSERT INTO ' . _DB_PREFIX_ . "smart_blog_imagetype (type_name,width,height,type,active) VALUES ('" . $type_name . "','" . $width . "','" . $height . "','" . $type . "',1);";
			Db::getInstance()->execute($damiimgtype);
		}
		return true;
	}

	public function hookHeader($params)
	{
		$this->smarty->assign('meta_title', 'This is Title' . ' - ' . 'MName');
	}
	public function SmartHookInsert()
	{
		$hookvalue = array();
		include_once dirname(__FILE__) . '/sql/addhook.php';

		foreach ($hookvalue as $hkv) {

			$hookid = Hook::getIdByName($hkv['name']);
			if (!$hookid) {
				$add_hook              = new Hook();
				$add_hook->name        = pSQL($hkv['name']);
				$add_hook->title       = pSQL($hkv['title']);
				$add_hook->description = pSQL($hkv['description']);
				$add_hook->position    = pSQL($hkv['position']);
				$add_hook->live_edit   = $hkv['live_edit'];
				$add_hook->add();
				$hookid = $add_hook->id;
				if (!$hookid) {
					return false;
				}
			} else {
				$up_hook = new Hook($hookid);
				$up_hook->update();
			}
		}
		return true;
	}

	public function SmartHookRegister()
	{
		$hookvalue = array();
		include_once dirname(__FILE__) . '/sql/addhook.php';

		foreach ($hookvalue as $hkv) {

			$this->registerHook($hkv['name']);
		}
		return true;
	}

	public function uninstall()
	{
		if (
			!parent::uninstall()
			|| !Configuration::deleteByName('smartblogmetatitle')
			|| !Configuration::deleteByName('smartblogmetakeyword')
			|| !Configuration::deleteByName('smartblogmetadescrip')
			|| !Configuration::deleteByName('smartpostperpage')
			|| !Configuration::deleteByName('sborderby')
			|| !Configuration::deleteByName('sborder')
			|| !Configuration::deleteByName('smartblogrootcat')
			|| !Configuration::deleteByName('smartacceptcomment')
			|| !Configuration::deleteByName('smartusehtml')
			|| !Configuration::deleteByName('smartcaptchaoption')
			|| !Configuration::deleteByName('smartshowviewed')
			|| !Configuration::deleteByName('smartdisablecatimg')
			|| !Configuration::deleteByName('smartenablecomment')
			|| !Configuration::deleteByName('smartenableguestcomment')
			|| !Configuration::deleteByName('smartmainblogurl')
			|| !Configuration::deleteByName('smartshowcolumn')
			|| !Configuration::deleteByName('smartshowauthorstyle')
			|| !Configuration::deleteByName('smartcustomcss')
			|| !Configuration::deleteByName('smartshownoimg')
			|| !Configuration::deleteByName('smartsearchengine')
			|| !Configuration::deleteByName('smartshowauthor')
			|| !Configuration::deleteByName('smartblogurlpattern')
			|| !Configuration::deleteByName('smartshowhomepost')
		) {
			return false;
		}

		$idtabs = array();

		include_once dirname(__FILE__) . '/sql/uninstall_tab.php';
		foreach ($idtabs as $tabid) :
			if ($tabid) {
				$tab = new Tab($tabid);
				$tab->delete();
			}
		endforeach;
		$sql = array();
		include_once dirname(__FILE__) . '/sql/uninstall.php';
		foreach ($sql as $s) :
			if (!Db::getInstance()->Execute($s)) {
				return false;
			}
		endforeach;

		// $this->SmartHookDelete();
		$this->deletequickaccess();
		$this->DeleteCache();
		return true;
	}

	public function deletequickaccess()
	{
		$qa = new QuickAccess(Configuration::get('smartblog_quick_access'));
		$qa->delete();
	}


	public function getContent()
	{
		$feed_url      = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/smartblog/rss.php';
		$feed_url_html = '<div class="row">
        <div class="alert alert-info"><strong>Feed URL: </strong>' . $feed_url . '</div>
    </div>';

		$html = '';

		$this->autoregisterhook('moduleRoutes', 'smartblog');
		$this->autoregisterhook('vcBeforeInit', 'smartlegendaaddons');
		if (Tools::isSubmit('savesmartblog')) {

			Configuration::updateValue('smartblogmetatitle', Tools::getvalue('smartblogmetatitle'));
			Configuration::updateValue('smartenablecomment', Tools::getvalue('smartenablecomment'));
			Configuration::updateValue('smartenableguestcomment', Tools::getvalue('smartenableguestcomment'));
			Configuration::updateValue('smartblogmetakeyword', Tools::getvalue('smartblogmetakeyword'));
			Configuration::updateValue('smartblogmetadescrip', Tools::getvalue('smartblogmetadescrip'));
			Configuration::updateValue('smartpostperpage', Tools::getvalue('smartpostperpage'));
			Configuration::updateValue('sborderby', Tools::getvalue('sborderby'));
			Configuration::updateValue('sborder', Tools::getvalue('sborder'));
			Configuration::updateValue('smartblogrootcat', Tools::getvalue('smartblogrootcat'));
			Configuration::updateValue('smartblogurlpattern', Tools::getvalue('smartblogurlpattern'));
			Configuration::updateValue('smartacceptcomment', Tools::getvalue('smartacceptcomment'));
			Configuration::updateValue('smartcaptchaoption', Tools::getvalue('smartcaptchaoption'));
			Configuration::updateValue('smartshowviewed', Tools::getvalue('smartshowviewed'));
			Configuration::updateValue('smartdisablecatimg', Tools::getvalue('smartdisablecatimg'));
			Configuration::updateValue('smartshowauthorstyle', Tools::getvalue('smartshowauthorstyle'));
			Configuration::updateValue('smartshowauthor', Tools::getvalue('smartshowauthor'));
			Configuration::updateValue('smartshowcolumn', Tools::getvalue('smartshowcolumn'));
			Configuration::updateValue('smartmainblogurl', Tools::getvalue('smartmainblogurl'));
			Configuration::updateValue('smartusehtml', Tools::getvalue('smartusehtml'));
			Configuration::updateValue('smartshownoimg', Tools::getvalue('smartshownoimg'));
			Configuration::updateValue('smartsearchengine', Tools::getvalue('smartsearchengine'));
			Configuration::updateValue('smartdataformat', Tools::getvalue('smartdataformat'));
			Configuration::updateValue('smartcustomcss', Tools::getvalue('smartcustomcss'), true);
			Configuration::updateValue('smartshowhomepost', Tools::getvalue('smartshowhomepost'));

			$this->processImageUpload($_FILES);
			$html   = $this->displayConfirmation($this->trans('The settings have been updated successfully.', [], 'Modules.Smartblog.Smartblog'));

			$helper = $this->SettingForm();
			$html  .= $feed_url_html;
			$html  .= $helper->generateForm($this->fields_form);
			$helper = $this->regenerateform();
			$html  .= $helper->generateForm($this->fields_form);

			return $html;
		} elseif (Tools::isSubmit('generateimage')) {
			if (Tools::getvalue('isdeleteoldthumblr') != 1) {
				BlogImageType::ImageGenerate();
				$html   = $this->displayConfirmation($this->trans('Generate New Thumblr Succesfully.', [], 'Modules.Smartblog.Smartblog'));
				$helper = $this->SettingForm();
				$html  .= $helper->generateForm($this->fields_form);
				$helper = $this->regenerateform();
				$html  .= $helper->generateForm($this->fields_form);

				return $html;
			} else {
				BlogImageType::ImageDelete();
				BlogImageType::ImageGenerate();
				$html   = $this->displayConfirmation($this->trans('Delete Old Image and Generate New Thumblr Succesfully.', [], 'Modules.Smartblog.Smartblog'));
				$helper = $this->SettingForm();
				$html  .= $helper->generateForm($this->fields_form);
				$helper = $this->regenerateform();
				$html  .= $helper->generateForm($this->fields_form);

				return $html;
			}
		} else {

			$helper = $this->SettingForm();
			$html  .= $helper->generateForm($this->fields_form);
			$helper = $this->regenerateform();
			$html  .= $helper->generateForm($this->fields_form);

			return $html;
		}
	}

	public function autoregisterhook($hook_name = 'moduleRoutes', $module_name = 'smartblog', $shop_list = null)
	{
		if ((Module::isEnabled($module_name) == 1) && (Module::isInstalled($module_name) == 1)) {
			$return    = true;
			$id_sql    = 'SELECT `id_module` FROM `' . _DB_PREFIX_ . 'module` WHERE `name` = "' . $module_name . '"';
			$id_module = Db::getInstance()->getValue($id_sql);
			if (is_array($hook_name)) {
				$hook_names = $hook_name;
			} else {
				$hook_names = array($hook_name);
			}
			foreach ($hook_names as $hook_name) {
				if (!Validate::isHookName($hook_name)) {
					throw new PrestaShopException('Invalid hook name');
				}
				if (!isset($id_module) || !is_numeric($id_module)) {
					return false;
				}
				// $hook_name_bak = $hook_name;
				// if ($alias = Hook::getRetroHookName($hook_name)) {
				// 	$hook_name = $alias;
				// }
				$id_hook = Hook::getIdByName($hook_name);
				// $live_edit = Hook::getLiveEditById((int) Hook::getIdByName($hook_name_bak));
				if (!$id_hook) {
					$new_hook            = new Hook();
					$new_hook->name      = pSQL($hook_name);
					$new_hook->title     = pSQL($hook_name);
					$new_hook->live_edit = (bool) preg_match('/^display/i', $new_hook->name);
					$new_hook->position  = (bool) $new_hook->live_edit;
					$new_hook->add();
					$id_hook = $new_hook->id;
					if (!$id_hook) {
						return false;
					}
				}
				if (is_null($shop_list)) {
					$shop_list = Shop::getShops(true, null, true);
				}
				foreach ($shop_list as $shop_id) {
					$sql = 'SELECT hm.`id_module`
                        FROM `' . _DB_PREFIX_ . 'hook_module` hm, `' . _DB_PREFIX_ . 'hook` h
                        WHERE hm.`id_module` = ' . (int) ($id_module) . ' AND h.`id_hook` = ' . (int) $id_hook . '
                        AND h.`id_hook` = hm.`id_hook` AND `id_shop` = ' . (int) $shop_id;
					if (Db::getInstance()->getRow($sql)) {
						continue;
					}

					$sql = 'SELECT MAX(`position`) AS position
                        FROM `' . _DB_PREFIX_ . 'hook_module`
                        WHERE `id_hook` = ' . (int) $id_hook . ' AND `id_shop` = ' . (int) $shop_id;
					if (!$position = Db::getInstance()->getValue($sql)) {
						$position = 0;
					}

					$return &= Db::getInstance()->insert(
						'hook_module',
						array(
							'id_module' => (int) $id_module,
							'id_hook'   => (int) $id_hook,
							'id_shop'   => (int) $shop_id,
							'position'  => (int) ($position + 1),
						)
					);
				}
			}
			return $return;
		} else {
			return false;
		}
	}

	protected function regenerateform()
	{
		$default_lang                 = (int) Configuration::get('PS_LANG_DEFAULT');
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->trans('Blog Thumblr Configuration', [], 'Modules.Smartblog.Smartblog'),
			),
			'input'  => array(
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Delete Old Thumblr', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'isdeleteoldthumblr',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
			),
			'submit' => array(
				'title' => $this->trans('Re Generate Thumblr', [], 'Modules.Smartblog.Smartblog'),
			),
		);

		$helper                  = new HelperForm();
		$helper->module          = $this;
		$helper->name_controller = $this->name;
		$helper->token           = Tools::getAdminTokenLite('AdminModules');
		foreach (Language::getLanguages(false) as $lang) {
			$helper->languages[] = array(
				'id_lang'    => $lang['id_lang'],
				'iso_code'   => $lang['iso_code'],
				'name'       => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
			);
		}
		$helper->currentIndex                       = AdminController::$currentIndex . '&configure=' . $this->name;
		$helper->default_form_language              = $default_lang;
		$helper->allow_employee_form_lang           = $default_lang;
		$helper->toolbar_scroll                     = true;
		$helper->show_toolbar                       = false;
		$helper->submit_action                      = 'generateimage';
		$helper->fields_value['isdeleteoldthumblr'] = Configuration::get('isdeleteoldthumblr');
		return $helper;
	}

	public function processImageUpload($FILES)
	{
		if (isset($FILES['avatar']) && isset($FILES['avatar']['tmp_name']) && !empty($FILES['avatar']['tmp_name'])) {
			if (ImageManager::validateUpload($FILES['avatar'], 4000000)) {
				return $this->displayError($this->trans('Invalid image', [], 'Modules.Smartblog.Smartblog'));
			} else {
				$ext       = Tools::substr($FILES['avatar']['name'], strrpos($FILES['avatar']['name'], '.') + 1);
				$file_name = 'avatar.' . $ext;
				$path      = _PS_MODULE_DIR_ . 'smartblog/images/avatar/' . $file_name;
				if (!move_uploaded_file($FILES['avatar']['tmp_name'], $path)) {
					return $this->displayError($this->trans('An error occurred while attempting to upload the file.', [], 'Modules.Smartblog.Smartblog'));
				} else {
					$author_types = BlogImageType::GetImageAllType('author');
					foreach ($author_types as $image_type) {
						$dir = _PS_MODULE_DIR_ . 'smartblog/images/avatar/avatar-' . Tools::stripslashes($image_type['type_name']) . '.jpg';
						if (file_exists($dir)) {
							unlink($dir);
						}
					}
					$images_types = BlogImageType::GetImageAllType('author');
					foreach ($images_types as $image_type) {
						ImageManager::resize(
							$path,
							_PS_MODULE_DIR_ . 'smartblog/images/avatar/avatar-' . Tools::stripslashes($image_type['type_name']) . '.jpg',
							(int) $image_type['width'],
							(int) $image_type['height']
						);
					}
				}
			}
		}
	}

	public function SettingForm()
	{
		$blog_url                     = self::GetSmartBlogLink('module-smartblog-list');
		$img_desc                     = '';
		$img_desc                    .= '' . $this->trans('Upload an Avatar from your computer. N.B : Only jpg image is allowed', [], 'Modules.Smartblog.Smartblog');
		$img_desc                    .= '<br/><img style="clear:both;border:1px solid black;" alt="" src="' . __PS_BASE_URI__ . 'modules/smartblog/images/avatar/avatar.jpg" height="100" width="100"/><br />';

		$orders = array(
			array(
				'id_order' => "ASC", 
				'order' => 'Ascending' 
			),
			array(
				'id_order' => "DESC", 
				'order' => 'Descending' 
			)
		);

		$default_lang                 = (int) Configuration::get('PS_LANG_DEFAULT');
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->trans('Setting', [], 'Modules.Smartblog.Smartblog'),
			),
			'input'  => array(
				array(
		            'type' => 'block_label',
		            'label' => $this->l('General'),
		            'name'=> ''
		        ),
				array(
					'type'     => 'text',
					'label'    => $this->trans('Meta Title', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartblogmetatitle',
					'size'     => 70,
					'required' => true,
				),
				array(
					'type'     => 'text',
					'label'    => $this->trans('Meta Keyword', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartblogmetakeyword',
					'size'     => 70,
					'required' => false,
				),
				array(
					'type'     => 'textarea',
					'label'    => $this->trans('Meta Description', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartblogmetadescrip',
					'rows'     => 7,
					'cols'     => 66,
					'required' => true,
				),
				array(
					'type'     => 'text',
					'label'    => $this->trans('Main Blog Url', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartmainblogurl',
					'size'     => 15,
					'required' => true,
					'desc'     => '<p class="alert alert-info"><a href="' . $blog_url . '">' . $blog_url . '</a></p>',
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Use .html with Friendly Url', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartusehtml',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'radio',
					'label'    => $this->trans('Blog Page Url Pattern', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartblogurlpattern',
					'required' => false,
					'class'    => 't',
					'values'   => array(
						array(
							'id'    => 'smartblogurlpattern_a',
							'value' => 1,
							'label' => $this->trans('alias/{slug}html ( ex: alias/share-the-love-for-prestashop-1-6.html)', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'smartblogurlpattern_b',
							'value' => 2,
							'label' => $this->trans('alias/{id_post}_{slug}html ( ex: alias/1_share-the-love-for-prestashop-1-6.html)', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
		            'type' => 'block_label',
		            'label' => $this->l('Display settings'),
		            'name'=> ''
		        ),
				array(
					'type'     => 'text',
					'label'    => $this->trans('Number of posts per page', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartpostperpage',
					'size'     => 15,
					'required' => true,
				),
				array(
					'type'     => 'select',
					'label'    => $this->trans('Order By', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'sborderby',
					'options' => [
						'query' => $this->getOrderBylist(),
						'id' => 'id_orderby',
						'name' => 'orderby',
					],
				),
				array(
					'type'     => 'select',
					'label'    => $this->trans('Order', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'sborder',
					'options' => [
						'query' => $orders,
						'id' => 'id_order',
						'name' => 'order',
					],
				),
				array(
					'type'     => 'text',
					'label'    => $this->trans('Date format', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartdataformat',
					'size'     => 15,
					'required' => true,		
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Auto accepted comment', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartacceptcomment',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Enable Captcha', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartcaptchaoption',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Enable Comment', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartenablecomment',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Allow Guest Comment', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartenableguestcomment',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Show Author Name', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartshowauthor',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Show Post Viewed', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartshowviewed',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Show Author Name Style', [], 'Modules.Smartblog.Smartblog'),
					'desc'     => 'YES : \'First Name Last Name\'<br> NO : \'Last Name First Name\'',
					'name'     => 'smartshowauthorstyle',
					'required' => false,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('First Name, Last Name', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Last Name, First Name', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'          => 'file',
					'label'         => $this->trans('AVATAR Image:', [], 'Modules.Smartblog.Smartblog'),
					'name'          => 'avatar',
					'display_image' => false,
					'desc'          => $img_desc,
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Show \'No Image\'', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartshownoimg',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Index in Serch Engine', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartsearchengine',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Show Category', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartdisablecatimg',
					'required' => false,
					'desc'     => 'Show category image and description on category page',
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
						),
					),
				),

				array(
					'type'     => 'textarea',
					'label'    => $this->trans('Custom CSS', [], 'Modules.Smartblog.Smartblog'),
					'name'     => 'smartcustomcss',
					'rows'     => 7,
					'cols'     => 66,
					'required' => false,
				),
			),
			'submit' => array(
				'title' => $this->trans('Save', [], 'Modules.Smartblog.Smartblog'),
			),
		);
		$helper                  = new HelperForm();
		$helper->module          = $this;
		$helper->name_controller = $this->name;
		$helper->token           = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex    = AdminController::$currentIndex . '&configure=' . $this->name;
		foreach (Language::getLanguages(false) as $lang) {
			$helper->languages[] = array(
				'id_lang'    => $lang['id_lang'],
				'iso_code'   => $lang['iso_code'],
				'name'       => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
			);
		}
		$helper->toolbar_btn              = array(
			'save' =>
			array(
				'desc' => $this->trans('Save', [], 'Modules.Smartblog.Smartblog'),
				'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name . 'token=' . Tools::getAdminTokenLite('AdminModules'),
			),
		);
		$helper->default_form_language    = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->title                    = $this->displayName;
		$helper->show_toolbar             = true;
		$helper->toolbar_scroll           = true;
		$helper->submit_action            = 'save' . $this->name;
		$helper->fields_value['smartpostperpage']        = Configuration::get('smartpostperpage');
		$helper->fields_value['sborderby']        = Configuration::get('sborderby');
		$helper->fields_value['sborder']        = Configuration::get('sborder');
		$helper->fields_value['smartdataformat']         = Configuration::get('smartdataformat');
		$helper->fields_value['smartacceptcomment']      = Configuration::get('smartacceptcomment');
		$helper->fields_value['smartshowauthorstyle']    = Configuration::get('smartshowauthorstyle');
		$helper->fields_value['smartshowauthor']         = Configuration::get('smartshowauthor');
		$helper->fields_value['smartmainblogurl']        = Configuration::get('smartmainblogurl');
		$helper->fields_value['smartusehtml']            = Configuration::get('smartusehtml');
		$helper->fields_value['smartshowcolumn']         = Configuration::get('smartshowcolumn');
		$helper->fields_value['smartblogmetakeyword']    = Configuration::get('smartblogmetakeyword');
		$helper->fields_value['smartblogmetatitle']      = Configuration::get('smartblogmetatitle');
		$helper->fields_value['smartblogmetadescrip']    = Configuration::get('smartblogmetadescrip');
		$helper->fields_value['smartshowviewed']         = Configuration::get('smartshowviewed');
		$helper->fields_value['smartdisablecatimg']      = Configuration::get('smartdisablecatimg');
		$helper->fields_value['smartenablecomment']      = Configuration::get('smartenablecomment');
		$helper->fields_value['smartenableguestcomment'] = Configuration::get('smartenableguestcomment');
		$helper->fields_value['smartcustomcss']          = Configuration::get('smartcustomcss');
		$helper->fields_value['smartshownoimg']          = Configuration::get('smartshownoimg');
		$helper->fields_value['smartsearchengine']          = Configuration::get('smartsearchengine');
		$helper->fields_value['smartcaptchaoption']      = Configuration::get('smartcaptchaoption');
		$helper->fields_value['smartblogurlpattern']     = Configuration::get('smartblogurlpattern');
		$helper->fields_value['smartshowhomepost']       = Configuration::get('smartshowhomepost');
		return $helper;
	}

	public static function GetSmartBlogUrl()
	{
		$ssl_enable       = Configuration::get('PS_SSL_ENABLED');
		$id_lang          = (int) Context::getContext()->language->id;
		$id_shop          = (int) Context::getContext()->shop->id;
		$rewrite_set      = (int) Configuration::get('PS_REWRITING_SETTINGS');
		$ssl              = null;
		static $force_ssl = null;
		if ($ssl === null) {
			if ($force_ssl === null) {
				$force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
			}
			$ssl = $force_ssl;
		}
		if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $id_shop !== null) {
			$shop = new Shop($id_shop);
		} else {
			$shop = Context::getContext()->shop;
		}
		$base    = ($ssl == 1 && $ssl_enable == 1) ? 'https://' . $shop->domain_ssl : 'http://' . $shop->domain;
		$langUrl = Language::getIsoById($id_lang) . '/';
		if ((!$rewrite_set && in_array($id_shop, array((int) Context::getContext()->shop->id, null))) || !Language::isMultiLanguageActivated($id_shop) || !(int) Configuration::get('PS_REWRITING_SETTINGS', null, null, $id_shop)) {
			$langUrl = '';
		}

		return $base . $shop->getBaseURI() . $langUrl;
	}

	public static function GetSmartBlogLink($rewrite = 'smartblog', $params = null, $id_shop = null, $id_lang = null)
	{
		$url          = self::GetSmartBlogUrl();
		$dispatcher   = Dispatcher::getInstance();
		$id_lang      = (int) Context::getContext()->language->id;

		$force_routes = (bool) Configuration::get('PS_REWRITING_SETTINGS');
		if (Tools::isSubmit('savesmartblog')) {
			$usehtml = (int) Configuration::get('smartusehtml');
			if ($usehtml != 0) {
				$html = '.html';
			} else {
				$html = '';
			}
			return $url . Tools::getvalue('smartmainblogurl') . $html;
		}

		if ($params != null) {
			return $url . $dispatcher->createUrl($rewrite, $id_lang, $params, $force_routes);
		} else {
			$params = array();
			return $url . $dispatcher->createUrl($rewrite, $id_lang, $params, $force_routes);
		}
	}

	public function hookModuleRoutes($params)
	{
		$alias   = Configuration::get('smartmainblogurl');
		$usehtml = (int) Configuration::get('smartusehtml');
		if ($usehtml != 0) {
			$html = '.html';
		} else {
			$html = '';
		}
		$smartblogurlpattern = (int) Configuration::get('smartblogurlpattern');
		$my_link = array();
		$is_crazy_admin = Tools::getValue('hook');
		if($is_crazy_admin == 'extended'){

			$my_link = $this->urlPatterWithIdOne($alias, $html);

		}else{
			switch ($smartblogurlpattern) {
				case 1:
					$my_link = $this->urlPatterWithoutId($alias, $html);
					break;
				case 2:
					$my_link = $this->urlPatterWithIdOne($alias, $html);
					break;
	
				default:
					$my_link = $this->urlPatterWithIdOne($alias, $html);
			}
		}
		return $my_link;
	}

	public function urlPatterWithoutId($alias, $html)
	{
		$my_link = array(
			'module-smartblog-list'                    => array(
				'controller' => 'list',
				'rule'       => $alias . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list'                => array(
				'controller' => 'category',
				'rule'       => $alias . '/category' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list_module'         => array(
				'controller' => 'category',
				'rule'       => 'module/' . $alias . '/category' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list_pagination'     => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_pagination'          => array(
				'controller' => 'categorypage',
				'rule'       => $alias . '/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_category_rule'       => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{slug}' . $html,
				'keywords'   => array(
					'id_category'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'slug'          => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
					'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-category'            => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{slug}' . $html,
				'keywords'   => array(
					'id_category'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'slug'          => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
					'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-categorypage'            => array(
				'controller' => 'categorypage',
				'rule'       => $alias . '/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_category_pagination' => array(
				'controller' => 'categorypage',
				'rule'       => $alias . '/category/{slug}/page/{page}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'page'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
					'slug'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_cat_page_mod'        => array(
				'controller' => 'category',
				'rule'       => 'module/' . $alias . '/category/{slug}/page/{page}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'page'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
					'slug'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_search'              => array(
				'controller' => 'search',
				'rule'       => $alias . '/search',
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-tagpost'                 => array(
				'controller' => 'tagpost',
				'rule'       => $alias . '/tag/{tag}' . $html,
				'keywords'   => array(
					'tag' => array(
						'regexp' => '[_a-zA-Z0-9-\pL\+]*',
						'param'  => 'tag',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_tag'                 => array(
				'controller' => 'tagpost',
				'rule'       => $alias . '/tag/{tag}' . $html,
				'keywords'   => array(
					'tag' => array(
						'regexp' => '[_a-zA-Z0-9-\pL\+]*',
						'param'  => 'tag',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_search_pagination'   => array(
				'controller' => 'search',
				'rule'       => $alias . '/search/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_post_rule'           => array(
				'controller' => 'details',
				'rule'       => $alias . '/{slug}' . $html,
				'keywords'   => array(
					'id_post'       => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_post',
					),
					'slug'          => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
					'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-details'                => array(
				'controller' => 'details',
				'rule'       => $alias . '/{slug}' . $html,
				'keywords'   => array(

					'slug'          => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
					'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-archivemonth'             => array(
				'controller' => 'archivemonth',
				'rule'       => $alias . '/archive/{year}/{month}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '',
						'param'  => 'month',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-archive'             => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '',
						'param'  => 'year',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_archive_pagination'  => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_month'               => array(
				'controller' => 'archivemonth',
				'rule'       => $alias . '/archive/{year}/{month}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_month_pagination'    => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/page/{page}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'page'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_day'                 => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/{day}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'day'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'day',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_day_pagination'      => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/{day}/page/{page}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'day'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'day',
					),
					'page'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_year'                => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_year_pagination'     => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/page/{page}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
		);
		return $my_link;
	}

	public function urlPatterWithIdOne($alias, $html)
	{
		$my_link = array(
			'module-smartblog-list'        => array(
				'controller' => 'list',
				'rule'       => $alias . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list'                => array(
				'controller' => 'category',
				'rule'       => $alias . '/category' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list_module'         => array(
				'controller' => 'category',
				'rule'       => 'module/' . $alias . '/category' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list_pagination'     => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_pagination'          => array(
				'controller' => 'category',
				'rule'       => $alias . '/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-category'            => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{slug}' . $html,
				'keywords'   => array(

					'slug'        => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_category_rule'       => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{id_category}_{slug}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'slug'        => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_category_pagination' => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{id_category}_{slug}/page/{page}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'page'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
					'slug'        => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_cat_page_mod'        => array(
				'controller' => 'category',
				'rule'       => 'module/' . $alias . '/category/{id_category}_{slug}/page/{page}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'page'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
					'slug'        => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_search'              => array(
				'controller' => 'search',
				'rule'       => $alias . '/search' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_tag'                 => array(
				'controller' => 'tagpost',
				'rule'       => $alias . '/tag/{tag}' . $html,
				'keywords'   => array(
					'tag' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'tag',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_search_pagination'   => array(
				'controller' => 'search',
				'rule'       => $alias . '/search/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_post'                => array(
				'controller' => 'details',
				'rule'       => $alias . '/{id_post}_{slug}' . $html,
				'keywords'   => array(
					'id_post' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_post',
					),
					'slug'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),


			'smartblog_post_rule'           => array(
				'controller' => 'details',
				'rule'       => $alias . '/{id_post}_{slug}' . $html,
				'keywords'   => array(
					'id_post' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_post',
					),
					'slug'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),

			'smartblog_archive_pagination'  => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_month'               => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_month_pagination'    => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/page/{page}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'page'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_day'                 => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/{day}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'day'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'day',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_day_pagination'      => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/{day}/page/{page}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'day'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'day',
					),
					'page'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_year'                => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_year_pagination'     => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/page/{page}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
		);
		return $my_link;
	}

	public static function displayDate($date, $id_lang = null, $full = false, $separator = null)
	{
		if ($id_lang !== null) {
			Tools::displayParameterAsDeprecated('id_lang');
		}
		if ($separator !== null) {
			Tools::displayParameterAsDeprecated('separator');
		}

		if (!$date || !($time = strtotime($date))) {
			return $date;
		}

		if ($date == '0000-00-00 00:00:00' || $date == '0000-00-00') {
			return '';
		}

		if (!Validate::isDate($date) || !Validate::isBool($full)) {
			throw new PrestaShopException('Invalid date');
		}

		$date_format = ($full ? Configuration::get('smartdataformat') : Configuration::get('smartdataformat'));
		return date($date_format, $time);
	}

	public static function smartblogthemelist()
	{
		$directory = _PS_MODULE_DIR_ . 'smartblog/views/templates/front/themes/';
		if ( !is_dir( $directory ) ) {
			return false;       
		}
		$scanned_directory_theme = array_diff($files = preg_grep('/^([^.])/', scandir($directory)), array('..', '.'));
		sort($scanned_directory_theme);
		$directory_theme = _PS_THEME_DIR_ . "modules/smartblog/views/templates/front/themes/";
		if (is_dir($directory_theme)) {
			$scanned_directory_theme_theme = array_diff($files = preg_grep('/^([^.])/', scandir($directory_theme)), array('..', '.'));
			sort($scanned_directory_theme_theme);
			$scanned_directory_theme = array_merge($scanned_directory_theme, $scanned_directory_theme_theme);
		}
		$directory_p_theme = _PS_PARENT_THEME_DIR_ . "modules/smartblog/views/templates/front/themes/";
		if (is_dir($directory_p_theme)) {
			$scanned_directory_theme_p_theme = array_diff($files = preg_grep('/^([^.])/', scandir($directory_p_theme)), array('..', '.'));
			sort($scanned_directory_theme_p_theme);
			$scanned_directory_theme = array_merge($scanned_directory_theme, $scanned_directory_theme_p_theme);
		}
		$scanned_directory_theme = array_unique($scanned_directory_theme);
		$returnArray = [];
		foreach ($scanned_directory_theme as $key => $theme) {
			$returnArray[$key]['lab'] = ucfirst($theme);
			$returnArray[$key]['val'] = $theme;
		}
		return $returnArray;
	}

	public static function getOrderBylist()
	{
		$options = array(
			array(
				'id_orderby' => "id_smart_blog_post", 
				'orderby' => 'Blog Id' 
			),
			array(
			  'id_orderby' => "name", 
			  'orderby' => 'Name' 
			),
			array(
				'id_orderby' => "created", 
				'orderby' => 'Date Created' 
			),
			array(
				'id_orderby' => "viewed", 
				'orderby' => 'Popularity (Based on views)' 
			),
		);
		
		return $options;
	}


	public static function categoryslug2id($slug)
	{
		$sql = 'SELECT p.id_smart_blog_category 
                FROM `' . _DB_PREFIX_ . 'smart_blog_category_lang` p 
                WHERE p.link_rewrite =  "' . pSQL($slug) . '"';

		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
			return false;
		}
		return $result[0]['id_smart_blog_category'];
	}

	public static function slug2id($slug)
	{
		$sql = 'SELECT p.id_smart_blog_post 
                FROM `' . _DB_PREFIX_ . 'smart_blog_post_lang` p 
                WHERE p.link_rewrite =  "' . pSQL($slug) . '"';

		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
			return false;
		}
		return $result[0]['id_smart_blog_post'];
	}



	public function smartblogcategoriesHookLeftColumn($params)
	{

		if (!$thiscn->isCached('plugins/smartblogcategories.tpl')) {
			$view_data    = array();
			$id_lang      = $this->context->language->id;
			$BlogCategory = new BlogCategory();
			$categories   = $BlogCategory->getCategory(1, $id_lang);
			$i            = 0;
			foreach ($categories as $category) {
				$categories[$i]['count'] = $BlogCategory->getPostByCategory($category['id_smart_blog_category']);
				$i++;
			}
			$protocol_link    = (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
			$protocol_content = (isset($useSSL) and $useSSL and Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
			$smartbloglink = new SmartBlogLink($protocol_link, $protocol_content);
			$this->smarty->assign(
				array(
					'smartbloglink' => $smartbloglink,
					'categories'    => $categories,
				)
			);
		}
		return $this->display(__FILE__, 'views/templates/front/plugins/smartblogcategories.tpl');
	}



	public function hookactionsbdeletecat($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbnewcat($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbupdatecat($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbtogglecat($params)
	{
		return $this->DeleteCache();
	}



	public function hookactionsbdeletepost($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbnewpost($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbupdatepost($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbtogglepost($params)
	{
		return $this->DeleteCache();
	}

	public function DeleteCache()
	{
		$this->_clearCache('plugins/smartblogcategories.tpl');
		$this->_clearCache('plugins/smartblog_latest_news.tpl');
		$this->_clearCache('plugins/smartblogrelatedproduct.tpl');
	}

	public function smartblogrelatedproductHookdisplayProductTab($params)
	{
		return $this->display(__FILE__, 'views/templates/front/plugins/smartproduct_tab.tpl');
	}

	public function hookdisplayProductTab($params)
	{
		return $this->smartblogrelatedproductHookdisplayProductTab($params);
	}

	public function smartblogrelatedproductHookdisplayProductTabContent($params)
	{
		$id_lang = $this->context->language->id;
		$posts   = SmartBlogPost::getRelatedPostsByProduct($id_lang, Tools::getvalue('id_product'));
		$this->smarty->assign(
			array(
				'posts' => $posts,
			)
		);
		return $this->display(__FILE__, 'views/templates/front/plugins/smart_product_tab_creator.tpl');
	}

	public function hookdisplayProductTabContent($params)
	{
		return $this->smartblogrelatedproductHookdisplayProductTabContent($params);
	}
}