<?php

use kartik\widgets\ActiveForm,
    vova07\imperavi\Widget,
    yii\helpers\Html,
    yii\helpers\Url;
$prefix = 'Post[translateattrs][' . $lang['id'] . ']'; 

/* @var $form ActiveForm */
?>


<?= Html::hiddenInput($prefix . '[lang_id]', $lang['id'])?>
<?= $form->field($model, 'title')->textInput(['id' => "title-{$lang['id']}", 'name' => $prefix . '[title]']); ?>        
<?= $form->field($model, 'content')->widget(Widget::className(), [
    'options' => [
        'id' => "content-{$lang['id']}", 
        'name' => $prefix . '[content]',
    ]
]); ?>
<?= $form->field($model, 'short_content')->widget(Widget::className(), [
    'options' => [
        'id' => "short-content-{$lang['id']}", 
        'name' => $prefix . '[short_content]',
    ],
]); ?>
<?= $form->field($model, 'meta_title')->textInput(['id' => "meta_title-{$lang['id']}", 'name' => $prefix . '[meta_title]']); ?>        
<?= $form->field($model, 'meta_keywords')->textarea(['rows' => 5, 'id' => "meta_keywords-{$lang['id']}", 'name' => $prefix . '[meta_keywords]']); ?>
<?= $form->field($model, 'meta_description')->textarea(['rows' => 5, 'id' => "meta_description-{$lang['id']}", 'name' => $prefix . '[meta_description]']); ?>