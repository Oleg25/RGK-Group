<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-search">


    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'type' => ActiveForm::TYPE_INLINE,
    ]); ?>


    <?php $authArray = ArrayHelper::map(\app\models\Author::find()->orderBy('firstname')->all(), 'id', 'fullname') ?>
    <?php echo $form->field($model, 'author_id')->dropDownList($authArray, ['prompt' => 'Автор'])->label(false) ?>

    <?= $form->field($model, 'name') ?>


    <br />

    <p>
        <?php
        echo '<br /><label class="control-label">Дата выхода книги</label>';

        echo DatePicker::widget([
            'model' => $model,
            'attribute' => 'beginDate',
            'attribute2' => 'endDate',

            'type' => DatePicker::TYPE_RANGE,
            'form' => $form,
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'autoclose' => true,
            ]
        ]);
        ?>


    </p>
    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
