<?php

namespace maks757\articlesdata\entities;

use maks757\imagable\Imagable;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "article_image".
 *
 * @property integer $id
 * @property string $image
 * @property string $position
 * @property integer $article_id
 *
 * @property Yii2DataArticle $article
 */
class Yii2DataArticleImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'position'], 'required'],
            [['article_id', 'position'], 'integer'],
            [['image'], 'string', 'max' => 100],
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

    public function getImage(){
        /* @var Imagable $imagine */
        $imagine = \Yii::$app->article;
        $imagePath = $imagine->get('images', 'origin', $this->image);
        $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
        return str_replace($aliasPath,'',$imagePath);
    }
}
