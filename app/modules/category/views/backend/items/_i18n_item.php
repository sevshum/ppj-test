<?php

$prefix = 'CategoryItem[translateattrs][' . $lang['id'] . ']'; 

/* @var $form ActiveForm */
?>
<?= yii\helpers\Html::hiddenInput($prefix . '[lang_id]', $lang['id'])?>
<?= $form->field($model, 'name')->textInput(['id' => "name-{$lang['id']}", 'name' => $prefix . '[name]']); ?>        
