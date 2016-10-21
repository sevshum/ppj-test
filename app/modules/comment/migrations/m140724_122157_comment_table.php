<?php

class m140724_122157_comment_table extends \yii\db\Migration
{

    public function safeUp()
    {
        $sql = <<< EOD
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `entity_type` varchar(255) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` DECIMAL(11,2),
  `status` int(11) NOT NULL DEFAULT '1',
  `request_info` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_idx` (`entity_type`,`entity_id`),
  KEY `user_id_idx` (`user_id`),
  KEY `parent_id_idx` (`parent_id`),
  KEY `created_at_idx` (`created_at`),
  KEY `status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
EOD;
        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable('comments');
    }
}