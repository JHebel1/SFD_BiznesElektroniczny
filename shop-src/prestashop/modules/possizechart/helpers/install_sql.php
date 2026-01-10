<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'possizecharts`(
  `id_possizecharts` int(11) NOT NULL auto_increment,
  `active` int(11) DEFAULT NULL,
  `condition` int(11) DEFAULT NULL,
  `specific_product` varchar(150) DEFAULT NULL,
  `specific_product_catg` varchar(150) DEFAULT NULL,
  `specific_product_manu` varchar(150) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_possizecharts`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'possizecharts_lang` (
  `id_possizecharts` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `content` LONGTEXT,
  PRIMARY KEY (`id_possizecharts`,`id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'possizecharts_shop` (
  `id_possizecharts_shop`  int(11) NOT NULL auto_increment,
  `id_possizecharts`  int(11) NOT NULL,
  `id_shop` int(11) NOT NULL,
  KEY(`id_possizecharts_shop`),
  PRIMARY KEY (`id_possizecharts`,`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
