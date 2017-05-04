<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\articlesdata\entities\Yii2DataArticleImage
 * @var $model_image \maks757\articlesdata\components\UploadImages
 * @var $language_id integer
 * @var $article_id integer
 * @var $languagePrimaryKeyFieldName string
 */
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
?>
    <a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article_id]) ?>"
       class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?= $form->field($model_image, 'imageFile')->widget(FileInput::className(), [
        'options' => [
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'previewFileType' => 'image',
            'initialPreviewAsData'=>true,
            'initialPreview'=>[
                !empty($model->getImage()) ? $model->getImage() : null
            ],
        ],
    ])->label('Изображение') ?>
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
<?php ActiveForm::end() ?>