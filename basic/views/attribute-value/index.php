<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Attribute;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AttributeValueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Attribute Values';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-value-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Attribute Value', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'attribute_value_id',
            //'attribute_id',
            [
                'attribute' => 'attribute_id', 
                'value' => function($attribute_id){
                    return Attribute::getName($attribute_id->attribute_id);
                
                },
                'label' => 'Атрибут'
            ],
            'value',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
