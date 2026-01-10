<?php

class demoData
{
    public function initData()
    {
        $return = true;
        $languages = Language::getLanguages(true);
        $id_shop = Context::getContext()->shop->id;
        $id_hook_reassurance = (int)Hook::getIdByName('displayReassurance');
		$id_hook_contact = (int)$this->getIdByName('displayMapcontact');
        $queries = [
            'INSERT INTO `'._DB_PREFIX_.'pos_staticblock` (`id_pos_staticblock`, `id_hook`, `position`, `name`,`active`) VALUES
				(1, '.$id_hook_contact.', 1, "Map Block", 1),
                (2, '.$id_hook_reassurance.', 2, "payment block", 1)'
        ];

        foreach (Language::getLanguages(true, Context::getContext()->shop->id) as $lang) {
            $queries[] = 'INSERT INTO `'._DB_PREFIX_.'pos_staticblock_lang` (`id_pos_staticblock`, `id_lang`, `content`) VALUES
				(1, '.(int)$lang['id_lang'].', \'<div class="section">
				<div class="desc_contact">Contact us via the contact form below, or come visit us on our office in Melbourne and we will discuss your new project</div>
				<iframe width="100%" height="535" style="border: 0;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3593.2218412210027!2d-80.24599528461161!3d25.76323511482399!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d9b7087dfe11a1%3A0xe150d5083889a11a!2s3030%20SW%208th%20St%2C%20Miami%2C%20FL%2033135%2C%20USA!5e0!3m2!1sen!2s!4v1625713519549!5m2!1sen!2s" allowfullscreen="allowfullscreen" loading="lazy"></iframe></div>\'),			
				(2, '.(int)$lang['id_lang'].', \'<div class="payment-detail"><img src="/pos_ecolife/img/cms/payment.png" alt="" />
				<p>Guarantee safe & secure checkout</p>
				</div>\')'
            ;
        }

        $queries[] = 'INSERT INTO `'._DB_PREFIX_.'pos_staticblock_shop` (`id_pos_staticblock`, `id_shop`) VALUES
                (1, 1),
				(2, 1)';  

        foreach ($queries as $query) {
            $return &= Db::getInstance()->execute($query);
        }

        return $return;
    }
	 public function getIdByName($hook_name){
        $sql = 'SELECT h.`id_hook` FROM `'._DB_PREFIX_.'hook` h WHERE h.`name` = \''.$hook_name.'\'';
        $result = Db::getInstance()->executeS($sql);
        return $result[0]['id_hook'];
    }
}
?>