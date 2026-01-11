<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class GoogleAnalytics extends Module {
    public function __construct()
    {
        $this->name = 'googleanalytics';
        $this->version = '1.0.0';
        $this->author = 'KOWL';
        parent::__construct();

        $this->displayName = 'Google Analytics';
        $this->description = 'Integracja zewnętrzna';
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayHeader');
    }

    public function hookDisplayHeader()
    {
        return $this->display(__FILE__, 'header.tpl');
    }
}