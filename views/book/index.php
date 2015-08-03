<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="book-index">

    <h1>Книги</h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <br />

    <p>
        <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
     <br />
    </p>


    <?php Pjax::begin(['id'=>'books']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'authFullName',
            [
                'attribute' => 'image',
                'label'=>'Превью',
                'format' => 'html',
                'value'=>function ($model) {
                    return Html::img($model->preview, ['width'=>'50px','class' => 'im']);
                },
            ],

            'date',
            'date_create',

            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'template' => '{view} {update} {delete}',
                'headerOptions' => ['width' => '20%', 'class' => 'activity-view-link',],
                'contentOptions' => ['class' => 'padding-left-5px'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('[просм]','#', [
                            'class' => 'activity-view-link',
                            'title' => Yii::t('yii', 'View'),
                            'data-toggle' => 'modal',
                            'data-target' => '#activity-modal',
                            'data-id' => $key,
                            'data-pjax' => '0',

                        ]);
                    },

                ],

            ],


        ],


    ]); ?>
    <?php Pjax::end() ?>

<?php $this->registerJs(
    'function init_click_handlers(){
       $(".activity-view-link").click(function(e) {
            var fID = $(this).closest("tr").data("key");
            $.get(
             "index.php?r=book%2Fview&id="+fID,
              {
              id: fID
              },
                function (data)
                {
                    $("#activity-modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $("#activity-modal").modal("show");
                }
            );
        });

}

init_click_handlers();
$("#books").on("pjax:success", function() {
  init_click_handlers();
});

 $(".im").click(function() {
             $(this).toggleClass("bigger");
        });

');?>

    <?php Modal::begin([
        'size' => Modal::SIZE_LARGE,
        'options' => ['class'=>'slide'],
        'id' => 'activity-modal',
        'header' => '<h4 class="modal-title">Загрузка...</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Закрыть</a>',

    ]); ?>

    <?php Modal::end(); ?>

</div>
