<?php
/** @var yii\web\View $this */
/** @var app\models\Author $model */

use yii\helpers\Html;

$this->title = "Автор";
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="book-view">

    <p><b>ID:</b> <?= Html::encode($model->id) ?></p>

    <p><b>ФИО:</b> <?= Html::encode($model->getFullName()) ?></p>

    <p><b>Добавлен:</b> <?= Html::encode(date('d.m.Y H:i', $model->updated_at)) ?></p>

    <p><b>Обновлен:</b> <?= Html::encode(date('d.m.Y H:i', $model->created_at)) ?></p>

</div>
