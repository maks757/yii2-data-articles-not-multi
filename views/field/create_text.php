<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\articlesdata\entities\Yii2DataArticleText
 * @var $languagePrimaryKeyFieldName string
 * @var $article_id integer
 * @var $language_id integer
 */

use yii\widgets\ActiveForm;
?>
<a href="<?= \yii\helpers\Url::toRoute(['/articles/post/create', 'id' => $article_id]) ?>"
   class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'text')->widget(\dosamigos\ckeditor\CKEditor::className(), [
    'preset' => 'full',
    'options' => ['rows' => 20],
    'clientOptions' => [
        'extraPlugins' => 'iframe,font,uicolor,colordialog,colorbutton,flash,magicline,print',
        'filebrowserUploadUrl' => \yii\helpers\Url::toRoute(['/articles/post/upload'], true)
    ]
])->label('Текст') ?>
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
<?php ActiveForm::end() ?>
