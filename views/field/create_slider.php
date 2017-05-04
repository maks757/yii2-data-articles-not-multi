<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\articlesdata\entities\Yii2DataArticleGallery
 * @var $languagePrimaryKeyFieldName string
 * @var $article_id integer
 * @var $language_id integer
 */
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article_id]) ?>"
   class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?php ActiveForm::end() ?>
<?php if(!empty($model->id)): ?>
    <?= $form->field(new \maks757\egallery\components\UploadForm(), 'imageFiles[]')->widget(FileInput::className(), [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'previewFileType' => 'image',
            'maxFileCount' => 20,
            'uploadUrl' => Url::toRoute(['/egallery/image/upload']),
            'uploadExtraData' => [
                'id' => $model->id,
                'key' => $model->className()
            ],
        ],
        'pluginEvents' => [
            'fileuploaded' => 'function() { $.pjax.reload({container:"#pjax_block", timeout: 100000, url: "'.Url::to('', true).'"}); }'
        ]
    ])->label('Загрузка изображений') ?>
    <?php Pjax::begin(['enablePushState' => false, 'id' => 'pjax_block']) ?>
    <?= \maks757\egallery\widgets\show_images\Gallery::widget(['object' => $model, 'show_name' => false]) ?>
    <?php Pjax::end() ?>
<?php endif; ?>
