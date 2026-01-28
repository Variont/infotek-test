<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */

use app\models\Book;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

?>
<div class="site-index">

    <?php if (Yii::$app->user->identity && Yii::$app->user->identity->role === 'admin'): ?>
        <p>
            <?= Html::a('Добавить книгу', ['book/create'], ['class' => 'btn btn-lg btn-primary']) ?>
        </p>
    <?php endif; ?>

    <div class="body-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'title',
                'year',
                'isbn',
                [
                    'label' => 'Авторы',
                    'format' => 'raw',
                    'value' => fn(Book $model) => $model->getAuthorsAsString(),
                ],
                'description:ntext',
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d.m.Y H:i'],
                    'value' => fn($model) => date('d.m.Y H:i', $model->created_at),
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['date', 'php:d.m.Y H:i'],
                    'value' => fn($model) => date('d.m.Y H:i', $model->updated_at),
                ],
                [
                    'header' => 'Действия',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'visibleButtons' => [
                        'update' => fn($model, $key, $index) => Yii::$app->user->identity->role === 'admin',
                        'delete' => fn($model, $key, $index) => Yii::$app->user->identity->role === 'admin',
                        'view'   => true,
                    ],
                ],
            ],
            'pager' => [
                'class' => LinkPager::class,
                'options' => ['class' => 'pagination justify-content-center'],
            ],
        ]); ?>
    </div>
</div>
