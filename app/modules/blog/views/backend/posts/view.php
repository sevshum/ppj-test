<?php
$this->pageTitle=param('siteName') . ' - Просмотр новости';
$this->breadcrumbs=[
	'Администрирование' => ['/admin/backend/main/index'],
	'Управление новостями' => ['admin'],
	'Просмотр новости',
];
$this->menu = [
	['label' => 'Добавить новость', 'url' => ['create']],
	['label' => 'Редактировать новость', 'url' => ['update', 'id' => $model->id]],
	['label' => 'Удалить новость', 'url' => '#',
		'url'=>'#',
		'linkOptions'=>[
			'submit'=>['delete','id'=>$model->id],
			'confirm'=> 'Вы действительно хотите удалить выбранную новость?'
		],
	],
];

$this->renderPartial('view', [
	'model' => $model,
]);