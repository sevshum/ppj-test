<?php
namespace app\modules\attachment\widgets;

use yii\web\AssetBundle;

/**
 * Description of UploadImageAsset
 *
 * @author mlapko
 */
class UploadImageAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        $this->js = ['upload_image.js'];
        $this->depends = [
            'yii\web\JqueryAsset',
            'yii\jui\JuiAsset'
        ];
        parent::init();
    }
}
