<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\blog\models;

/**
 * Description of PostQuery
 *
 * @author mlapko
 */
class PostQuery extends \app\modules\core\components\AppActiveQuery
{
    
    public function published()
    {
        $this->andWhere('`status` = :s AND `published_at` <= :d', [
            ':s' => Post::PUBLISHED, ':d' => date('Y-m-d')
        ]);
        return $this;
    }
    
    public function resent()
    {
        $this->orderBy(['published_at' => SORT_DESC]);
        return $this;
    }
    
    public function archive($date)
    {
        $this->andWhere('`published_at` LIKE :published', [':published' => $date . '%']);
        return $this;
    }
    
    /**
     * @param string $tagId
     * @return \app\modules\blog\models\PostQuery
     */
    public function byTag($tagId) 
    {
        $this->join('INNER JOIN', 'posts_tags', 'posts_tags.post_id = posts.id')
             ->andWhere(['posts_tags.tag_id' => $tagId]);
        return $this;
    }
}
