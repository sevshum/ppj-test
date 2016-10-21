<?php

class m140204_111453_categories_entities extends yii\db\Migration
{

    public function safeUp()
    {
        $sql = <<< EOD
DROP TABLE IF EXISTS `categories_entities`;
CREATE TABLE `categories_entities` (
  `item_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(32) NOT NULL,
  `parent_code` varchar(32) DEFAULT NULL,
  KEY `entity_idx` (`entity_id`,`entity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOD;
        $this->execute($sql);
    }

    public function safeDown()
    {
        $this->dropTable('categories_entities');
    }
}