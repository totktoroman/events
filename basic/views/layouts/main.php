<?php

/* @var $this \yii\web\View */
/* @var $content string */
use app\models\User;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use phpDocumentor\Reflection\PseudoTypes\False_;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        // Yii::$app->name
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    if(Yii::$app->user->isGuest == False){
        if(Yii::$app->user->identity->user_status_id == 1){
        $items = [
            ['label' => 'Мероприятия', 'url' => ['/event/events']],
            //['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Обратная связь', 'url' => ['/site/contact']],
            ['label' => 'Анкета', 'url' => ['/info/users']],
            ['label' => 'Аудитории', 'url' => ['/aud/index']],
            ['label' => 'Пользователи', 'url' => ['/user/index']],
            ['label' => 'Логи', 'url' => ['/log/index']],
            ['label' => 'Атрибуты', 'url' => ['/attribute/index']],
            ['label' => 'Значения атрибутов', 'url' => ['/attribute-value/index']],            
           
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->user_login . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            
                ];
            }
            else{
                $items = [
                    ['label' => 'Мероприятия', 'url' => ['/event/events']],
                   // ['label' => 'About', 'url' => ['/site/about']],
                    ['label' => 'Обратная связь', 'url' => ['/site/contact']],
                    ['label' => 'Анкета', 'url' => ['/info/users']],
                              
                        '<li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Выйти (' . Yii::$app->user->identity->user_login . ')',
                            ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>'
                    
                        ];

            }
    }
    else{
        $items = [
            ['label' => 'Мероприятия', 'url' => ['/event/events']],
            //['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Обратная связь', 'url' => ['/site/contact']],         
            ['label' => 'Войти', 'url' => ['/site/login']]
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => 'breadcrumb',//этот класс стоит по умолчанию
                'style' => ' '
            ],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ИМИ  <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
