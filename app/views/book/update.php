<?php

use app\models\Book;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var Book $model */

$this->title = $titlePrefix . ' книги';

?>

<div class="update-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'title')->textInput()->label('Название') ?>

    <?= $form->field($model, 'year')->textInput()->label('Год выхода') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4])->label('Описание') ?>

    <?= $form->field($model, 'isbn')->textInput()->label('Номер ISBN') ?>

    <?= $form->field($model, 'coverFile')->fileInput()->label('Обложка книги') ?>

    <?php if ($model->cover): ?>
        <div style="margin-top:10px;">
            <img src="<?= $model->cover ?>" alt="Cover" style="max-width:200px; max-height:300px;">
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'authorIds')->listBox(
        $authorsList,
        ['multiple' => true, 'size' => 4]
    )->label('Авторы книги') ?>

    <div class="form-group" style="margin-top:15px;">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
