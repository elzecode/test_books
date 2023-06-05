<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AuthorFollower $model */

$this->title = 'Create Author Follower';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-follower-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
