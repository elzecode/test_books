<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author $model */
/** @var yii\data\ActiveDataProvider $followerDataProvider */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            'created_at',
            'updated_at',
        ],
    ]) ?>


    <h1>Followers</h1>

    <p>
        <?= Html::a('Follow', ['author-follower/create', 'author_id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $followerDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'follower_phone',
        ],
        'layout' => '{items}',
    ]); ?>

</div>
