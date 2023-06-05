<?php

use app\models\Book;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            'description:ntext',
            'date_release',
            [
                'label' => 'cover',
                'format' => 'raw',
                'value' => function (Book $model) {
                    if ($src = $model->getCover()) {
                        return '<img width="150px" src="' . $src . '">';
                    }
                    return '';
                }
            ],
            [
                'label' => 'Authors',
                'format' => 'raw',
                'value' => function (Book $model) {
                    $links = [];
                    foreach ($model->authors as $author) {
                        $links[] = '<a href="' . Url::to(['author/view', 'id' => $author->id]) . '">' . $author->full_name . '</a>';
                    }
                    return implode(', ', $links);
                }
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Book $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
