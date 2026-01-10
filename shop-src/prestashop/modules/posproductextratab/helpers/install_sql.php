<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'posproductextratabs`(
  `id_posproductextratabs` int(11) NOT NULL auto_increment,
  `active` int(11) DEFAULT NULL,
  `condition` int(11) DEFAULT NULL,
  `specific_product` varchar(150) DEFAULT NULL,
  `specific_product_catg` varchar(150) DEFAULT NULL,
  `specific_product_manu` varchar(150) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_posproductextratabs`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'posproductextratabs_lang` (
  `id_posproductextratabs` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `content` LONGTEXT,
  PRIMARY KEY (`id_posproductextratabs`,`id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'posproductextratabs_shop` (
  `id_posproductextratabs_shop`  int(11) NOT NULL auto_increment,
  `id_posproductextratabs`  int(11) NOT NULL,
  `id_shop` int(11) NOT NULL,
  KEY(`id_posproductextratabs_shop`),
  PRIMARY KEY (`id_posproductextratabs`,`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
