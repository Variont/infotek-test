<?php
/** @var yii\web\View $this */
/** @var app\models\Book $model */

use yii\helpers\Html;

$this->title = $model->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="book-view">

    <?php if ($model->cover): ?>
        <p>
            <b>Обложка:</b><br>
            <img src="<?= Html::encode($model->cover) ?>" alt="<?= Html::encode($model->title) ?>" style="max-width:200px; height:auto;">
        </p>
    <?php endif; ?>

    <p><b>ID:</b> <?= Html::encode($model->id) ?></p>

    <p><b>Название:</b> <?= Html::encode($model->title) ?></p>

    <p><b>Авторы:</b> <?= Html::encode($model->getAuthorsAsString()) ?></p>

    <p><b>Год:</b> <?= Html::encode($model->year) ?></p>

    <p><b>Номер:</b> <?= Html::encode($model->isbn) ?></p>

    <p><b>Описание:</b> <?= Html::encode($model->description) ?></p>

    <p><b>Добавлена:</b> <?= Html::encode(date('d.m.Y H:i', $model->updated_at)) ?></p>

    <p><b>Обновлена:</b> <?= Html::encode(date('d.m.Y H:i', $model->created_at)) ?></p>

</div>
