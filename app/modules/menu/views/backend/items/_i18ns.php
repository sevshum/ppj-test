<?php

use app\modules\menu\models\MenuItemI18n;
use yii\bootstrap\Tabs;

$tabs = [];
$module = Yii::$app->getModule('language');
$defaultLang = $module->getDefault();

$i18ns = $model->i18ns;
foreach ($module->listing() as $lang) {
    $i18n = isset($i18ns[$lang['id']]) ? $i18ns[$lang['id']] : new MenuItemI18n();
    
    $tabs[] = array(
        'label' => $i18n->hasErrors() ? ('<span style="color:red;">' . $lang['name'] . '</span>') : $lang['name'],
        'active' => $lang['id'] == $defaultLang, 
        'content' => $this->render('_i18n_item', ['model' => $i18n, 'lang' => $lang, 'form' => $form])
    );
}
echo Tabs::widget([
    'items' => $tabs,
    'encodeLabels' => false,
    'options' => ['id' => 'tab-page-' . $model->id]
]);
?>