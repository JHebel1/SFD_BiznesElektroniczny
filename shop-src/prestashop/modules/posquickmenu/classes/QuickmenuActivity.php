<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author PrestaShop SA <contact@prestashop.com>
 * @copyright  2007-2019 PrestaShop SA
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
class QuickmenuActivity extends ObjectModel
{

    public $id;
    public $icon;
    public $custom_icon;
    public $title;
    public $html_content;
    public $status;
    public $position;
    public $id_shop;
    public $type_content;
    public $link;
    public $id_cms;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'posquickmenu',
        'primary' => 'id_quickmenu',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'icon' => ['type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isCleanHtml', 'size' => 255],
            'custom_icon' => ['type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isCleanHtml', 'size' => 255],
            'title' => ['type' => self::TYPE_STRING, 'shop' => true, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255],
            'html_content' => ['type' => self::TYPE_HTML, 'shop' => true, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 2000],
            'status' => ['type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool', 'required' => true],
            'position' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isunsignedInt', 'required' => false],
            'type_content' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isunsignedInt', 'required' => false],
            'id_cms' => ['type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isCleanHtml', 'required' => false],
            'link' => ['type' => self::TYPE_STRING, 'shop' => true, 'lang' => true, 'validate' => 'isUrl', 'required' => false, 'size' => 255],
        ],
    ];

    /**
     * @param int $id_lang
     * @param int $id_shop
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public static function getAllBlockByLang($id_lang = 1, $id_shop = 1)
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'posquickmenu` pr
            LEFT JOIN ' . _DB_PREFIX_ . 'posquickmenu_lang prl ON (pr.id_quickmenu = prl.id_quickmenu)
            WHERE prl.id_lang = "' . (int) $id_lang . '" AND prl.id_shop = "' . (int) $id_shop . '"
            ORDER BY pr.position';

        $result = Db::getInstance()->executeS($sql);

        return $result;
    }

    /**
     * @param array $psr_languages
     * @param int $type_content
     * @param int $id_cms
     */
    public function handleBlockValues($psr_languages, $type_content, $id_cms)
    {   
        $languages = Language::getLanguages();
        $newValues = [];
        foreach ($psr_languages as $key => $value) {
            $newValues[$key] = [
                'title' => $value->title,
                'html_content' => $value->html_content,
                'url' => $value->url,
            ];
        }

        foreach ($languages as $language) {
            if (false === array_key_exists($language['id_lang'], $newValues)) {
                continue;
            }

            $this->title[$language['id_lang']] = $newValues[$language['id_lang']]['title'];
            $this->html_content[$language['id_lang']] = $newValues[$language['id_lang']]['html_content'];
            $this->link[$language['id_lang']] = $newValues[$language['id_lang']]['url'];
            // if ($type_content === 5) {
            //     $this->link[$language['id_lang']] = $newValues[$language['id_lang']]['url'];
            // }
        }

        if (!empty($id_cms) && $type_content === 1) {
            $this->id_cms = $id_cms;
            $link = Context::getContext()->link;

            foreach ($languages as $language) {
                $link = $this->fomartLink($id_cms, $language['id_lang']);
                $this->link[$language['id_lang']] = $link;
            }
        }

        if ($type_content == 'undefined') {
            $type_content = 0;
        }

        $this->type_content = $type_content;
    }

    public function fomartLink($selection , $id_lang = null)
    {   
        if (is_null($selection)) return;
        
        $link = '';

        if (is_null($id_lang)) $id_lang = (int)$this->context->language->id;

        $type = Tools::substr($selection, 0, 3);
        $key = Tools::substr($selection, 3, Tools::strlen($selection) - 3);
        
        //echo '<pre>';print_r($item);die;
        $title = '';
        switch ($type)
        {
            case 'CAT':
                $link = Context::getContext()->link->getCategoryLink((int)$key, null, $id_lang);
                
                break;
            case 'CMS':
                $id_shop = (int)Context::getContext()->shop->id;
                $link = Context::getContext()->link->getCMSLink((int)$key, null, $id_lang, $id_shop);
                
                break;
            case 'MAN':
                $man = new Manufacturer((int)$key, $id_lang);
                $link = Context::getContext()->link->getManufacturerLink($man->id, $man->link_rewrite, $id_lang);
                
                break;
            case 'SUP':
                $sup = new Supplier((int)$key, $id_lang);
                $link = Context::getContext()->link->getSupplierLink($sup->id, $sup->link_rewrite, $id_lang);
               
                break;
            case 'PAG':
                if($key == 'homepag'){
                    $pag = Meta::getMetaByPage('index', $id_lang);
                }else{
                    $pag = Meta::getMetaByPage($key, $id_lang);
                }
                
                $link = Context::getContext()->link->getPageLink($pag['page'], true, $id_lang);
                
                break;
            case 'SHO':
                $shop = new Shop((int)$key);
                $link = $shop->getBaseURL();

                break;
            default:
                $link = $item['link'];
                break;
        }
        return $link;
    }

    /**
     * @param int $id_shop
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public static function getAllBlockByShop($id_shop = 1)
    {
        $result = [];

        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'posquickmenu` pr
            LEFT JOIN ' . _DB_PREFIX_ . 'posquickmenu_lang prl ON (pr.id_quickmenu = prl.id_quickmenu)
            WHERE prl.id_shop = "' . (int) $id_shop . '"
            GROUP BY prl.id_lang, pr.id_quickmenu
            ORDER BY pr.position';

        $dbResult = Db::getInstance()->executeS($sql);

        foreach ($dbResult as $key => $value) {
            $result[$value['id_lang']][$value['id_quickmenu']]['title'] = $value['title'];
            $result[$value['id_lang']][$value['id_quickmenu']]['html_content'] = $value['html_content'];
            $result[$value['id_lang']][$value['id_quickmenu']]['url'] = $value['link'];
        }

        return $result;
    }

    /**
     * @param int $id_lang
     * @param int $id_shop
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public static function getAllBlockByStatus($id_lang = 1, $id_shop = 1)
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'posquickmenu` pr
            LEFT JOIN ' . _DB_PREFIX_ . 'posquickmenu_lang prl ON (pr.id_quickmenu = prl.id_quickmenu)
            WHERE prl.id_lang = "' . (int) $id_lang . '" 
                AND prl.id_shop = "' . (int) $id_shop . '"
                AND pr.status = 1
            ORDER BY pr.position';

        $result = Db::getInstance()->executeS($sql);

        foreach ($result as &$item) {
            $item['is_svg'] = !empty($item['custom_icon'])
                && (ImageManager::getMimeType(str_replace(__PS_BASE_URI__, _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR, $item['custom_icon'])) == 'image/svg');
        }

        return $result;
    }
    
}
