<?php

use app\models\UserSubscriptions;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\User;
use app\models\Author;

/** @var yii\web\View $this */
/** @var app\models\UserSubscriptions $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Подписки пользователей';

$subscribedAuthorIds = UserSubscriptions::find()
    ->select('author_id')
    ->where(['user_id' => Yii::$app->user->id])
    ->column(); // вернет массив id авторов

$authors = Author::find()
    ->where(['not in', 'id', $subscribedAuthorIds])
    ->all();
?>

<div class="user-subscriptions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Создать новую подписку</h2>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'author_id')->dropDownList(
        ArrayHelper::map(
            $authors,
            'id',
            fn($author) => $author->getFullName()
        ),
        ['prompt' => 'Выберите автора']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <h2>Текущие подписки</h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'author_id',
                'label' => 'Автор',
                'value' => fn($model) => $model->author->getFullName(),
            ],
            [
                'label' => 'Написал книг',
                'value' => fn($model) => $model->author->countMentionedInBooks(),
            ],

        ],
    ]); ?>

</div>
