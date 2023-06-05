<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date_release')->widget(\yii\jui\DatePicker::className(), [
        'options' => ['class' => 'form-control'],
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?= $form->field($model, 'cover_path')->fileInput() ?>

    <?= $form->field($model, 'isbn')->textInput() ?>

    <div class="form-group field-book-authors">
        <label class="control-label">Authors</label>
        <?= Html::dropDownList(
            'new_authors',
            ArrayHelper::getColumn($model->authors, 'id'),
            ArrayHelper::map(
                \app\models\Author::find()->select('id, full_name')->all(),
                'id',
                'full_name'
            ),
            [
                    'class' => 'form-control',
                'multiple' => 'multiple',
            ]
        ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
