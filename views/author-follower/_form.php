<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AuthorFollower $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="author-follower-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'author_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'follower_phone')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '+79999999999',
        'clientOptions' => [
            'clearIncomplete' => true
        ]
    ])  ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
