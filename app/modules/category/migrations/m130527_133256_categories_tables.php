<?php

class m130527_133256_categories_tables extends yii\db\Migration
{

    public function safeUp()
    {
        $sql = <<< EOD
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code_idx` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
DROP TABLE IF EXISTS `category_items`;
CREATE TABLE IF NOT EXISTS `category_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,  
  `code` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `tree` int(11) DEFAULT NULL,
  `lft`  int(11) DEFAULT NULL,
  `rgt`  int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `category_item_i18ns`;
CREATE TABLE IF NOT EXISTS `category_item_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `lang_id` varchar(2) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id_idx` (`parent_id`),
  UNIQUE KEY `lang_id_parent_id_unique_idx` (`lang_id`,`parent_id`)      
) ENGINE=InnoDB DEFAULT CHARSET=utf8;    
            
EOD;
        $this->execute($sql);
    }

    public function down()
    {
        
    }
}