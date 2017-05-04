<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata\controllers;

use maks757\articlesdata\components\UploadImages;
use maks757\articlesdata\entities\Yii2DataArticleGallery;
use maks757\articlesdata\entities\Yii2DataArticleImage;
use maks757\articlesdata\entities\Yii2DataArticleText;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class FieldController extends Controller
{
    //<editor-fold desc="Text field">
    public function actionCreateText($id = null, $article_id = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataArticleText();

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('article_id')))
            $article_id = $request->post('article_id');

        if($model_data = Yii2DataArticleText::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $model->load($request->post());
            $model->article_id = $article_id;
            $model->setDefaultPosition();
            $model->save();

            return $this->redirect(Url::toRoute(['/articles/post/create', 'id' => $article_id]));
        }

        return $this->render('create_text', [
            'model' => $model,
            'article_id' => $article_id,
        ]);
    }

    public function actionTextPosition($id, $type)
    {
        $field = Yii2DataArticleText::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionTextDelete($id)
    {
        Yii2DataArticleText::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Image">
    public function actionCreateImage($id = null, $article_id = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataArticleImage();
        $model_image = new UploadImages();


        if(!empty($request->post('id')))
            $id = $request->post('id');

        if($model_data = Yii2DataArticleImage::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $model_image->imageFile = UploadedFile::getInstance($model_image, 'imageFile');
            $model->load($request->post());
            if($image = $model_image->upload())
                $model->image = $image;
            $model->article_id = $article_id;
            $model->setDefaultPosition();
            $model->save();

            return $this->redirect(Url::toRoute(['/articles/field/create-image', 'id' => $model->id, 'article_id' => $article_id]));
        }

        return $this->render('create_image', [
            'model' => $model,
            'article_id' => $article_id,
            'model_image' => $model_image,
        ]);
    }

    public function actionImagePosition($id, $type)
    {
        $field = Yii2DataArticleImage::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionImageDelete($id)
    {
        Yii2DataArticleImage::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Slider">
    public function actionCreateSlider($id = null, $article_id = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataArticleGallery();

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('article_id')))
            $article_id = $request->post('article_id');

        if(empty($id)) {
            $model->article_id = $article_id;
            $model->setDefaultPosition();
            $model->save();
            return $this->redirect(Url::toRoute(['/articles/field/create-slider', 'id' => $model->id, 'article_id' => $article_id]));
        }

        if($model_data = Yii2DataArticleGallery::findOne($id)){
            $model = $model_data;
        }

        if($request->isPost){
            $model->load($request->post());
            $model->save();

            return $this->redirect(Url::toRoute(['/articles/field/create-slider', 'id' => $model->id, 'article_id' => $article_id]));
        }

        return $this->render('create_slider', [
            'model' => $model,
            'article_id' => $article_id
        ]);
    }

    public function actionSliderPosition($id, $type)
    {
        $field = Yii2DataArticleGallery::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionSliderDelete($id)
    {
        Yii2DataArticleGallery::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>
}