<?php

namespace maks757\articlesdata\entities;

use maks757\egallery\entities\Gallery;
use maks757\imagable\Imagable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "article_gallery".
 *
 * @property integer $id
 * @property string $position
 * @property integer $article_id
 *
 * @property Yii2DataArticle $article
 */
class Yii2DataArticleGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position'], 'required'],
            [['article_id', 'position'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataArticle::className(), 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Yii2DataArticle::className(), ['id' => 'article_id']);
    }

    public function setDefaultPosition()
    {
        if(!is_integer($this->position))
            $this->position = $this->article->getCountFields() + 1;
    }

    public function getImages(){
        $images = [];
        $galleries = Gallery::findAll(['key' => md5(self::className()), 'object_id' => $this->id]);
        ArrayHelper::multisort($galleries, 'position');
        foreach ($galleries as $gallery){
            /**@var Imagable $imagine */
            $imagine = \Yii::$app->egallery;
            $imagePath = $imagine->get('egallery', 'origin', $gallery->image);
            $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
            $images[] = [
                'image' => str_replace('\\', '/', str_replace($aliasPath,'',$imagePath)),
                'name' => $gallery->title
            ];
        }
        return $images;
    }
}
