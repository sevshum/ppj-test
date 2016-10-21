<?php

class m141006_123157_attachment_tables extends yii\db\Migration
{

    public function safeUp()
    {
        $sql = <<< EOD
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `model_id` bigint(20) NOT NULL,
  `model_type` varchar(32) NOT NULL,
  `file` varchar(255) NOT NULL,
  `origin_name` varchar(255) DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `params` text,
  `order` int(11) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model_type_model_id_idx` (`model_type`,`model_id`),
  KEY `order_idx` (`order`),
  KEY `type_idx` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOD;
        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable('images');
    }
}