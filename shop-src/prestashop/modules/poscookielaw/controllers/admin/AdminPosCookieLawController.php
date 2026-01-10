<?php
class AdminPosCookieLawController extends ModuleAdminController
{
	public function __construct() {

     $token = Tools::getAdminTokenLite('AdminModules');
     $currentIndex='index.php?controller=AdminModules&token='.$token.'&configure=poscookielaw&tab_module=front_office_features&module_name=poscookielaw';

     parent::__construct();
     Tools::redirectAdmin($currentIndex);
  }
}
