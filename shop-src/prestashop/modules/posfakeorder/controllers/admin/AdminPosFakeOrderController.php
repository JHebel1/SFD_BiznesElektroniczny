<?php
class AdminPosFakeOrderController extends ModuleAdminController
{
	public function __construct() {

     $token = Tools::getAdminTokenLite('AdminModules');
     $currentIndex='index.php?controller=AdminModules&token='.$token.'&configure=posfakeorder&tab_module=front_office_features&module_name=posfakeorder';

     parent::__construct();
     Tools::redirectAdmin($currentIndex);
  }
}
