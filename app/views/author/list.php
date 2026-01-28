<?php

/** @var yii\web\View $this */

use app\models\Author;
use yii\bootstrap5\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;

?>
<div class="site-index">

    <p>
        <?= Html::a('Добавить автора', ['author/create'], ['class' => 'btn btn-lg btn-primary']) ?>
    </p>

    <div class="body-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'label' => 'Авторы',
                    'format' => 'raw',
                    'value' => fn(Author $model) => $model->getFullName(),
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Создан',
                    'format' => ['date', 'php:d.m.Y H:i'],
                    'value' => fn($model) => date('d.m.Y H:i', $model->created_at),
                ],
                [
                    'attribute' => 'updated_at',
                    'label' => 'Обновлен',
                    'format' => ['date', 'php:d.m.Y H:i'],
                    'value' => fn($model) => date('d.m.Y H:i', $model->updated_at),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                ],
            ],
            'pager' => [
                'class' => LinkPager::class,
                'options' => ['class' => 'pagination justify-content-center'],
            ],
        ]); ?>
    </div>
</div>
