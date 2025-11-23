<?php
class AdminPosPopupController extends ModuleAdminController
{
	public function __construct() {

     $token = Tools::getAdminTokenLite('AdminModules');
     $currentIndex='index.php?controller=AdminModules&token='.$token.'&configure=pospopup&tab_module=front_office_features&module_name=pospopup';

     parent::__construct();
     Tools::redirectAdmin($currentIndex);
  }
}
