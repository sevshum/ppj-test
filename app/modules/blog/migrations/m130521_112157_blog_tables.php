<?php

class m130521_112157_blog_tables extends \yii\db\Migration
{

    public function safeUp()
    {
        $sql = <<< EOD
DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `published_at` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published_at_idx` (`published_at`),
  KEY `category_id_idx` (`category_id`),
  KEY `slug_idx` (`slug`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `post_i18ns`;
CREATE TABLE IF NOT EXISTS `post_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_id` varchar(2) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `short_content` text NOT NULL,
  `content` text NOT NULL,
  `meta_title` varchar(255) DEFAULT '',
  `meta_keywords` text DEFAULT '',
  `meta_description` text DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent_id_idx` (`parent_id`),
  UNIQUE KEY `lang_id_parent_id_unique_idx` (`lang_id`,`parent_id`) 
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            
DROP TABLE IF EXISTS `post_tags`;
CREATE TABLE IF NOT EXISTS `post_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `frequency` int(11) DEFAULT '1',  
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `posts_tags`;
CREATE TABLE IF NOT EXISTS `posts_tags` (
  `post_id` INT(11) UNSIGNED NOT NULL,
  `tag_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY  (`post_id`,`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `posts` (`id`, `user_id`, `slug`, `image`, `category_id`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'example', NULL, '', 1, '2016-10-21', '2016-10-21 09:28:28', '2016-10-21 13:54:48');

INSERT INTO `post_i18ns` (`id`, `lang_id`, `parent_id`, `title`, `short_content`, `content`, `meta_title`, `meta_keywords`, `meta_description`) VALUES
(1, 'en', 1, 'Example post', '<h2>What is Lorem Ipsum?</h2><p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and\r\n typesetting industry. Lorem Ipsum has been the industry''s standard \r\ndummy text ever since the 1500s, when an unknown printer took a galley \r\nof type and scrambled it to make a type specimen book. It has survived \r\nnot only five centuries, but also the leap into electronic typesetting, \r\nremaining essentially unchanged. It was popularised in the 1960s with \r\nthe release of Letraset sheets containing Lorem Ipsum passages, and more\r\n recently with desktop publishing software like Aldus PageMaker \r\nincluding versions of Lorem Ipsum.</p>', '<h2>What is Lorem Ipsum?</h2><p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and\r\n typesetting industry. Lorem Ipsum has been the industry''s standard \r\ndummy text ever since the 1500s, when an unknown printer took a galley \r\nof type and scrambled it to make a type specimen book. It has survived \r\nnot only five centuries, but also the leap into electronic typesetting, \r\nremaining essentially unchanged. It was popularised in the 1960s with \r\nthe release of Letraset sheets containing Lorem Ipsum passages, and more\r\n recently with desktop publishing software like Aldus PageMaker \r\nincluding versions of Lorem Ipsum.</p><h2>Why do we use it?</h2><p>It is a long established fact that a reader will be distracted by the\r\n readable content of a page when looking at its layout. The point of \r\nusing Lorem Ipsum is that it has a more-or-less normal distribution of \r\nletters, as opposed to using ''Content here, content here'', making it \r\nlook like readable English. Many desktop publishing packages and web \r\npage editors now use Lorem Ipsum as their default model text, and a \r\nsearch for ''lorem ipsum'' will uncover many web sites still in their \r\ninfancy. Various versions have evolved over the years, sometimes by \r\naccident, sometimes on purpose (injected humour and the like).</p>', '', '', '');
EOD;
        $this->execute($sql);
    }

    public function down()
    {
        
    }
}