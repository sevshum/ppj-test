<?php
namespace app\modules\blog;

use app\modules\core\components\AppModule;
use app\modules\blog\models\Post;
use Yii;

class Module extends AppModule
{

    public $controllerNamespace = 'app\modules\blog\controllers';

    public function getArchive()
    {
        return Post::getArchive();
    }
    
    public function getArchiveDate($date)
    {
        $tokens = explode('-', $date);
        if (count($tokens) !== 2) {
            return '';
        }
        
        return Yii::t('app', '{0, date, MMMM}', $tokens[1]) . ' ' . $tokens[0];
    }
    
    public function getStringEntity()
    {
        return 'post';
    }
    
    public function search()
    {
        return [
            'sql' => '
                SELECT {select}
                FROM posts
                INNER JOIN post_i18ns
                    ON post_i18ns.parent_id = posts.id AND post_i18ns.lang_id = :lang 
                WHERE posts.`status` = ' . Post::PUBLISHED . ' 
                    AND post_i18ns.title LIKE :q OR post_i18ns.content LIKE :q',
            'select' => 'posts.id, "blog" AS model_type, posts.published_at AS `sort_at`'
        ];
    }

}
