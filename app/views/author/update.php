<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Author;

/** @var yii\web\View $this */
/** @var Author $model */
/** @var string $titlePrefix */

$this->title = $titlePrefix . ' автора';

?>

<div class="update-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <!-- Скрытые поля -->
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <!-- Основные поля -->
    <?= $form->field($model, 'last_name')->textInput()->label('Фамилия') ?>
    <?= $form->field($model, 'first_name')->textInput()->label('Имя') ?>
    <?= $form->field($model, 'middle_name')->textInput()->label('Отчество') ?>

    <div class="form-group" style="margin-top:15px;">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
