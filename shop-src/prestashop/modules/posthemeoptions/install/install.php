<?php 

namespace Posthemes\Module\Poselements;

use Module;
use DB;
use Configuration;
use \CE\Plugin;

class Install extends Module
{
    private $templates;
    private $destination = _PS_IMG_DIR_.'cms/';
    private $origin_module = 'creativeelements';
    
    public function __construct()
    {
        $this->CEtemplates = _PS_MODULE_DIR_.'posthemeoptions/install/templates/';
    }

    public function installTemplates()
    {
        if (!Module::isInstalled($this->origin_module)){
            return false;
        }

        if (!$this->installDemoTemplates()){
            return false;
        }
        $header_template_id = $this->getIdElementByName('header-default');
        Configuration::updateValue('posthemeoptionsheader_template', $header_template_id);
        $home_template_id = $this->getIdElementByName('home-default');
        Configuration::updateValue('posthemeoptionshome_template', $home_template_id);
        $footer_template_id = $this->getIdElementByName('footer-default');
        Configuration::updateValue('posthemeoptionsfooter_template', $footer_template_id);

        $this->hookRegisterCEmodule();

        return true;
    }    

    public function installDemoTemplates()
    {
        require_once _PS_MODULE_DIR_.$this->origin_module.'/'.$this->origin_module.'.php';
        $CEtemplates = glob($this->CEtemplates.'*.json');

        if (!empty($CEtemplates)){
            $_SERVER['SERVER_SOFTWARE'] = 'Apache/2.4.46'; 

            foreach ($CEtemplates as $CEtemplate){
                $_FILES['file']['tmp_name'] = $CEtemplate;
                $response = \CE\Plugin::instance()->templates_manager->importTemplate();
                if (is_object($response)){
                    return false;
                }
            }
        }
        return true;
    }

    public function getIdElementByName($title){
        //get first ID by name
        $sql = 'SELECT ct.`id_ce_template` FROM `' . _DB_PREFIX_ . 'ce_template` ct WHERE ct.`active` = 1 AND ct.`title` = "'. $title . '"';
        $results = Db::getInstance()->getRow($sql);
        
        return $results['id_ce_template'];
        
    }

    public function hookRegisterCEmodule()
    {
        $hooks = ['displayContactPageBuilder','displayHeaderBuilder','displayHomeBuilder','displayFooterBuilder'];
        Module::getInstanceByName($this->origin_module)->registerHook($hooks);
    }
}