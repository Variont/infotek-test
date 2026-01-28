<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Книжный магазин!</h1>

        <p class="lead">Для просмотра зайдите в Учетную запись.</p>

        <p>
            <?= Html::a('Вход в ЛК', ['/site/login'], ['class' => 'btn btn-lg btn-success']) ?>
        </p>
    </div>

    <div class="body-content">


    </div>
</div>
