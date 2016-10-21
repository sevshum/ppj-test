<?php
namespace app\modules\attachment\widgets;

use yii\web\AssetBundle;

/**
 * Description of MediaAsset
 *
 * @author mlapko
 */
class MediaAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        $this->js = ['media.js'];
        $this->depends = [
            'yii\web\JqueryAsset',
            'yii\jui\JuiAsset'
        ];
        parent::init();
    }
}
