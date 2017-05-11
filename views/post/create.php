<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\articlesdata\entities\Yii2DataArticle
 * @var $this \yii\web\View
 * @var $image_model \maks757\articlesdata\entities\Yii2DataArticle
 * @var $users \common\models\User[]
 * @var $rows array
 * @var $module \yii\base\Module
 * @var $article \maks757\articlesdata\entities\Yii2DataArticle
 * @var $module \maks757\articlesdata\ArticleModule
 */

use kartik\file\FileInput;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$model->getCountFields();
$css = <<<css
iframe{
    width: 100%;
    height: 600px;
}
css;
$this->registerCss($css);
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?= $form->field($image_model, 'imageFile')->widget(FileInput::className(), [
    'options' => [
        'accept' => 'image/*'
    ],
    'pluginOptions' => [
        'showRemove' => false,
        'previewFileType' => 'image',
        'initialPreviewAsData' => true,
        'initialPreview' => [
            !empty($model->getImage()) ? $model->getImage() : null
        ],
    ],
])->label('Миниатюра') ?>
<?= $form->field($model, 'name')->textInput()->label('Название') ?>
<?= $form->field($model, 'description')->widget(\dosamigos\ckeditor\CKEditor::className(), [
    'preset' => 'full',
    'clientOptions' => [
        'extraPlugins' => 'iframe,font,uicolor,colordialog,colorbutton,flash,magicline,print',
        'filebrowserUploadUrl' => \yii\helpers\Url::toRoute(['/articles/post/upload'], true)
    ]
])->label('Описание') ?>
<?= $form->field($model, 'date')->widget(DatePicker::className(), [
    'language' => 'ru',
    'dateFormat' => 'dd-MM-yyyy',
    'options' => [
        'class' => 'form-control',
        'id' => 'amtimevideo-date'
    ]
])->label('Дата') ?>


<div class="panel-group" role="tablist" id="ceo" aria-multiselectable="false">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a href="#collapseOne" role="button" data-toggle="collapse"
                   data-parent="#ceo" aria-expanded="true" aria-controls="collapseOne"
                   class=""> СЕО </a>
                <a href="#collapseOne" role="button" data-toggle="collapse"
                   data-parent="#ceo" aria-expanded="true" aria-controls="collapseOne"
                   class="glyphicon glyphicon-download pull-right"></a>
            </h4>
        </div>
        <div class="panel-collapse collapse" role="tabpanel" id="collapseOne"
             aria-labelledby="headingOne" aria-expanded="true">
            <div class="panel-body">
                <?= $form->field($model, 'seoUrl', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ]);
                ?>

                <?= $form->field($model, 'seoTitle', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ]);
                ?>

                <?= $form->field($model, 'seoDescription', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->textarea(['rows' => 3]);
                ?>

                <?= $form->field($model, 'seoKeywords', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->textarea(['rows' => 3]);
                ?>
            </div>
        </div>
    </div>
</div>
<br>
<?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>
<?php Pjax::begin(['enablePushState' => false]); ?>
<?php if (!empty($model->id) && $module->showFields): ?>
    <hr>
    <h2 class="text-center">Поля статьи</h2>
    <div class="btn-group dropup">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Добавить поле<span class="caret" style="margin-left: 10px;"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="<?= Url::toRoute(['/articles/field/create-text', 'article_id' => $model->id]) ?>">Добавить
                    текст</a></li>
            <li><a href="<?= Url::toRoute(['/articles/field/create-image', 'article_id' => $model->id]) ?>">Добавить
                    изображение</a></li>
            <li><a href="<?= Url::toRoute(['/articles/field/create-slider', 'article_id' => $model->id]) ?>">Добавить
                    слайдер</a></li>
        </ul>
    </div>
    <hr>
    <?php foreach ($rows as $row): ?>
        <?php if ($row['key'] == 'text'): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3>Текст</h3>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-info"
                               href="<?=
                               Url::toRoute([
                                   '/articles/field/create-text',
                                   'id' => $row['id'],
                                   'article_id' => $model->id,
                               ]) ?>"
                               style="margin-right: 10px; cursor: pointer; font-size: 20px;">Изменить</a>
                        </div>
                        <div class="col-sm-2 text-center">
                            <div>
                                <h5>позиция <?= $row['position'] ?></h5>
                                <a class="glyphicon glyphicon-upload"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'block' => 'text', 'type' => 'up']) ?>"
                                   style="margin-right: 10px; cursor: pointer; font-size: 20px;"></a>
                                <a class="glyphicon glyphicon-download"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'block' => 'text', 'type' => 'down']) ?>"
                                   style="margin-left: 10px; cursor: pointer; font-size: 20px;"></a>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center">
                            <a class="glyphicon glyphicon-remove"
                               href="<?= Url::toRoute(['/articles/field/text-delete', 'id' => $row['id']]) ?>"
                               style="margin-left: 10px; cursor: pointer; font-size: 30px; padding: 13px 0;"></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <?= $row['text'] ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($row['key'] == 'image'): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3>Изображение</h3>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-info"
                               href="<?=
                               Url::toRoute([
                                   '/articles/field/create-image',
                                   'id' => $row['id'],
                                   'article_id' => $model->id
                               ]) ?>"
                               style="margin-right: 10px; cursor: pointer; font-size: 20px;">Изменить</a>
                        </div>
                        <div class="col-sm-2 text-center">
                            <div>
                                <h5>позиция <?= $row['position'] ?></h5>
                                <a class="glyphicon glyphicon-upload"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'block' => 'image', 'type' => 'up']) ?>"
                                   style="margin-right: 10px; cursor: pointer; font-size: 20px;"></a>
                                <a class="glyphicon glyphicon-download"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'block' => 'image', 'type' => 'down']) ?>"
                                   style="margin-left: 10px; cursor: pointer; font-size: 20px;"></a>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center">
                            <a class="glyphicon glyphicon-remove"
                               href="<?= Url::toRoute(['/articles/field/image-delete', 'id' => $row['id']]) ?>"
                               style="margin-left: 10px; cursor: pointer; font-size: 30px; padding: 13px 0;"></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <img src="<?= $row['image'] ?>" style="max-width: 100%;">
                </div>
            </div>
        <?php endif; ?>
        <?php if ($row['key'] == 'slider'): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3>Слайдер</h3>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-info"
                               href="<?= Url::toRoute(['/articles/field/create-slider', 'id' => $row['id'], 'article_id' => $model->id]) ?>"
                               style="margin-right: 10px; cursor: pointer; font-size: 20px;">Изменить</a>
                        </div>
                        <div class="col-sm-2 text-center">
                            <div>
                                <h5>позиция <?= $row['position'] ?></h5>
                                <a class="glyphicon glyphicon-upload"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'block' => 'slider', 'type' => 'up']) ?>"
                                   style="margin-right: 10px; cursor: pointer; font-size: 20px;"></a>
                                <a class="glyphicon glyphicon-download"
                                   href="<?= Url::toRoute(['/articles/post/create', 'id' => $model->id, 'block_id' => $row['id'], 'block' => 'slider', 'type' => 'down']) ?>"
                                   style="margin-left: 10px; cursor: pointer; font-size: 20px;"></a>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center">
                            <a class="glyphicon glyphicon-remove"
                               href="<?= Url::toRoute(['/articles/field/slider-delete', 'id' => $row['id']]) ?>"
                               style="margin-left: 10px; cursor: pointer; font-size: 30px; padding: 13px 0;"></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php foreach ($row['images'] as $integer): ?>
                            <div class="col-sm-3">
                                <img src="<?= $integer['image'] ?>" style="width: 100%;">
                                <p><?= $integer['name'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php Pjax::end(); ?>
