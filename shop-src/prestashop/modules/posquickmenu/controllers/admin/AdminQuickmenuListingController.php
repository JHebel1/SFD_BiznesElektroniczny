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
class AdminQuickmenuListingController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        if(!(bool)Tools::getValue('ajax'))
         Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure=posquickmenu');
        
        
    }
    /**
     * @param $content
     *
     * @throws PrestaShopException
     */
    protected function ajaxRenderJson($content)
    {
        header('Content-Type: application/json');
        $this->ajaxRender(json_encode($content));
    }

    /**
     * Enable or disable a block
     *
     * @throws PrestaShopException
     */
    public function displayAjaxChangeBlockStatus()
    {
        $now = new DateTime();
        $psreassuranceId = (int) Tools::getValue('idpsr');
        $newStatus = ((int) Tools::getValue('status') == 1) ? 0 : 1;

        $dataToUpdate = [
            'status' => $newStatus,
        ];
        $whereCondition = 'id_quickmenu = ' . $psreassuranceId;

        $updateResult = Db::getInstance()->update('posquickmenu', $dataToUpdate, $whereCondition);

        // Response
        $this->ajaxRenderJson($updateResult ? 'success' : 'error');
    }

    /**
     * Delete a block
     *
     * @throws PrestaShopException
     */
    public function displayAjaxDeleteBlock()
    {
        $result = false;
        $idPSR = (int) Tools::getValue('idBlock');
        $blockPSR = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'posquickmenu WHERE `id_quickmenu` = ' . (int) $idPSR);
        if (!empty($blockPSR)) {
            $result = true;
            // Remove Custom icon
            if (!empty($blockPSR['custom_icon'])) {
                $filePath = str_replace(__PS_BASE_URI__, _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR, $blockPSR['custom_icon']);
                if (file_exists($filePath)) {
                    $result = unlink($filePath);
                }
            }
            // Remove Block Translations
            if ($result) {
                $result = Db::getInstance()->delete('posquickmenu_lang', 'id_quickmenu = ' . (int) $idPSR);
            }
            // Remove Block
            if ($result) {
                $result = Db::getInstance()->delete('posquickmenu', 'id_quickmenu = ' . (int) $idPSR);
            }
        }

        // Response
        $this->ajaxRenderJson($result ? 'success' : 'error');
    }

    /**
     * Update color settings to be used in front-office display
     *
     * @throws PrestaShopException
     */
    public function displayAjaxSaveColor()
    {
        $color1 = Tools::getValue('color1');
        $color2 = Tools::getValue('color2');
        $show_text = Tools::getValue('show_text');
        $result = false;

        if (!empty($color1) && !empty($color2)) {
            $result = Configuration::updateValue('QM_ICON_COLOR', $color1)
                && Configuration::updateValue('QM_SHOW_TEXT', $show_text)
                && Configuration::updateValue('QM_TEXT_COLOR', $color2);
        }

        // Response
        $this->ajaxRenderJson($result ? 'success' : 'error');
    }

    /**
     * Modify the settings of one block from BO "configure" page
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function displayAjaxSaveBlockContent()
    {
        $errors = [];

        $picto = Tools::getValue('picto');
        $id_block = empty(Tools::getValue('id_block')) ? null : Tools::getValue('id_block');
        $type_link = (int) Tools::getValue('typelink');
        $id_cms = Tools::getValue('id_cms');
        $psr_languages = (array) json_decode(Tools::getValue('lang_values'));
       
        $blockPsr = new QuickmenuActivity($id_block);
        if (!$id_block) {
            // Last position
            $blockPsr->position = Db::getInstance()->getValue('SELECT MAX(position) AS max FROM ' . _DB_PREFIX_ . 'posquickmenu');
            $blockPsr->position = $blockPsr->position ? $blockPsr->position + 1 : 1;
            $blockPsr->status = false;
        }
        $blockPsr->handleBlockValues($psr_languages, $type_link, $id_cms);
        $blockPsr->icon = $picto;
        if (empty($picto)) {
            $blockPsr->custom_icon = '';
        }

        if (isset($_FILES) && !empty($_FILES)) {
            $customImage = $_FILES['file'];
            $fileTmpName = $customImage['tmp_name'];
            $filename = $customImage['name'];

            // validateUpload return false if no error (false -> OK)
            $authExtensions = ['gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg'];
            $authMimeType = ['image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/svg'];
            if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
                // PrestaShop 1.7.7.0+
                $validUpload = ImageManager::validateUpload(
                    $customImage,
                    0,
                    $authExtensions,
                    $authMimeType
                );
            } else {
                // PrestaShop < 1.7.7
                $validUpload = false;
                $mimeType = $this->getMimeType($customImage['tmp_name']);
                if ($mimeType && (
                    !in_array($mimeType, $authMimeType)
                    || !ImageManager::isCorrectImageFileExt($customImage['name'], $authExtensions)
                    || preg_match('/\%00/', $customImage['name'])
                )) {
                    $validUpload = Context::getContext()->getTranslator()->trans('Image format not recognized, allowed formats are: .gif, .jpg, .png', [], 'Admin.Notifications.Error');
                }
                if ($customImage['error']) {
                    $validUpload = Context::getContext()->getTranslator()->trans('Error while uploading image; please change your server\'s settings. (Error code: %s)', [$customImage['error']], 'Admin.Notifications.Error');
                }
            }
            if (is_bool($validUpload) && $validUpload === false) {
                move_uploaded_file($fileTmpName, $this->module->folder_file_upload . $filename);
                $blockPsr->custom_icon = $this->module->img_path_perso . '/' . $filename;
                $blockPsr->icon = '';
            } else {
                $errors[] = $validUpload;
            }
        }
        if (empty($errors)) {
            if ($id_block) {
                $blockPsr->update();
            } else {
                $blockPsr->add();
            }
        }

        // Response
        $this->ajaxRenderJson(empty($errors) ? 'success' : 'error');
    }

    /**
     * Reorder the blocks positions
     *
     * @throws PrestaShopException
     */
    public function displayAjaxUpdatePosition()
    {
        $blocks = Tools::getValue('blocks');
        $result = false;

        if (!empty($blocks) && is_array($blocks)) {
            foreach ($blocks as $key => $id_block) {
                // Set the position of the Reassurance block
                $position = $key + 1;

                $dataToUpdate = ['position' => (int) $position];
                $whereCondition = 'id_quickmenu = ' . (int) $id_block;
                $updateResult = (bool) Db::getInstance()->update('posquickmenu', $dataToUpdate, $whereCondition);

                // If the update can't be done, we return false
                if (!$updateResult) {
                    break;
                }
            }
            $result = $updateResult ? true : false;
        }

        // Response
        $this->ajaxRenderJson($result ? 'success' : 'error');
    }

    /**
     * @return string|bool
     */
    private function getMimeType(string $filename)
    {
        $mimeType = false;
        // Try with GD
        if (function_exists('getimagesize')) {
            $imageInfo = @getimagesize($filename);
            if ($imageInfo) {
                $mimeType = $imageInfo['mime'];
            }
        }
        // Try with FileInfo
        if (!$mimeType && function_exists('finfo_open')) {
            $const = defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME;
            $finfo = finfo_open($const);
            $mimeType = finfo_file($finfo, $filename);
            finfo_close($finfo);
        }
        // Try with Mime
        if (!$mimeType && function_exists('mime_content_type')) {
            $mimeType = mime_content_type($filename);
        }
        // Try with exec command and file binary
        if (!$mimeType && function_exists('exec')) {
            $mimeType = trim(exec('file -b --mime-type ' . escapeshellarg($filename)));
            if (!$mimeType) {
                $mimeType = trim(exec('file --mime ' . escapeshellarg($filename)));
            }
            if (!$mimeType) {
                $mimeType = trim(exec('file -bi ' . escapeshellarg($filename)));
            }
        }

        return $mimeType;
    }
}
