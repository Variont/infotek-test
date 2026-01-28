<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $years array */
/* @var $selectedYear int */

echo "<h2>ТОП 10 авторов по количеству книг за год</h2>";

echo Html::beginForm(['author/top'], 'get');
echo Html::dropDownList('year', $selectedYear, array_combine($years, $years), ['onchange' => 'this.form.submit()']);
echo Html::endForm();

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'full_name',
            'label' => 'Автор',
        ],
        [
            'attribute' => 'books_count',
            'label' => 'Количество книг',
        ],
    ],
]);
