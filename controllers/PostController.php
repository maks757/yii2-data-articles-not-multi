<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata\controllers;

use maks757\articlesdata\components\UploadImage;
use maks757\articlesdata\entities\Yii2DataArticle;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class PostController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $provider = new ActiveDataProvider([
            'query' => Yii2DataArticle::find()->orderBy(['date' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
                ],
            ]);
        return $this->render('index', [
            'articles' => $provider->getModels(),
            'pagination' => $provider->getPagination()
        ]);
    }

    public function actionDelete($id)
    {
        Yii2DataArticle::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionCreate($id = null, $type = null, $block = null, $block_id = null)
    {
        //Change field position
        Yii2DataArticle::fieldsPosition($block, $type, $block_id);
        //Create
        $request = \Yii::$app->request;
        $model = new Yii2DataArticle();
        $image_model = new UploadImage();

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if($model_data = Yii2DataArticle::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $image_model->imageFile = UploadedFile::getInstance($image_model, 'imageFile');
            $image = $image_model->upload();

            $model->create($request->post(), $image);

            return $this->redirect(Url::toRoute(['/articles/post/create', 'id' => $model->id]));
        }

        $rows = $model->getField();

        return $this->render('create', [
            'model' => $model,
            'image_model' => $image_model,
            'rows' => $rows,
        ]);
    }

    public function actionUpload(){
        $callback = $_GET['CKEditorFuncNum'];

        $file_name = $_FILES['upload']['name'];
        $file_name_tmp = $_FILES['upload']['tmp_name'];

        $file_new_name = '/textEditor/';
        $full_path = FileHelper::normalizePath(Yii::getAlias('@frontend/web').$file_new_name.$file_name);
        $http_path = $file_new_name.$file_name;

        if( move_uploaded_file($file_name_tmp, $full_path) )
            $message = 'Зображення успiшно завантажено.';
        else
            $message = 'Не вдалося завантажити зображення.';

        echo "<script type='text/javascript'>// <![CDATA[
            window.parent.CKEDITOR.tools.callFunction('$callback',  '$http_path', '$message');
    // ]]></script>";
    }

}