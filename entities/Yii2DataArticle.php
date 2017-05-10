<?php

namespace maks757\articlesdata\entities;

use dosamigos\transliterator\TransliteratorHelper;
use maks757\imagable\Imagable;
use maks757\seo\behaviors\SeoDataBehavior;
use maks757\seo\entities\SeoData;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $image
 * @property integer $date
 * @property string $name
 * @property string $description
 * @property string $seoUrl
 * @property string $seoTitle
 * @property string $seoDescription
 * @property string $seoKeywords
 *
 * @property Yii2DataArticleGallery[] $articleGalleries
 * @property Yii2DataArticleImage[] $articleImages
 * @property Yii2DataArticleText[] $articleTexts
 * @property SeoData $seo
 */
class Yii2DataArticle extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'seoData' => [
                'class' => SeoDataBehavior::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'name'], 'required'],
            [['date'], 'integer'],
            [['image'], 'string', 'max' => 100],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            // seo data
            [['seoUrl', 'seoTitle', 'seoDescription', 'seoKeywords'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seoUrl' => 'СЕО Ссылка',
            'seoTitle' => 'СЕО Заголовок',
            'seoDescription' => 'СЕО Описание',
            'seoKeywords' => 'СЕО Ключи',
        ];
    }

    public function getSeo()
    {
        return $this->hasOne(SeoData::className(), ['entity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleries()
    {
        return $this->hasMany(Yii2DataArticleGallery::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Yii2DataArticleImage::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTexts()
    {
        return $this->hasMany(Yii2DataArticleText::className(), ['article_id' => 'id']);
    }

    /**
     * @return mixed|string
     */
    public function getImage(){
        if(!empty($this->image)) {
            /**@var Imagable $imagine */
            $imagine = \Yii::$app->article;
            $imagePath = $imagine->getOriginal('article', $this->image);
            $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
            return str_replace($aliasPath, '', $imagePath);
        } else {
            return '';
        }
    }

    public function getField()
    {
        $rows = [];
        foreach (Yii2DataArticleText::findAll(['article_id' => $this->id]) as $text){
            $rows[] = ($text->toArray() + $text->toArray() + ['key' => 'text']);
        }
        foreach (Yii2DataArticleImage::findAll(['article_id' => $this->id]) as $image){
            $image->image = $image->getImage();
            $rows[] = ($image->toArray() + ['key' => 'image']);
        }
        foreach (Yii2DataArticleGallery::findAll(['article_id' => $this->id]) as $gallery){
            $rows[] = ($gallery->toArray() + ['images' => $gallery->getImages()] + ['key' => 'slider']);
        }
        ArrayHelper::multisort($rows, 'position');
        return $rows;
    }

    public function getCountFields(){
        $texts = Yii2DataArticleText::find()->select('position')->where(['article_id' => $this->id])->all();
        $images = Yii2DataArticleImage::find()->select('position')->where(['article_id' => $this->id])->all();
        $galleries = Yii2DataArticleGallery::find()->select('position')->where(['article_id' => $this->id])->all();
        $rows = array_merge($texts, $images, $galleries);
        ArrayHelper::multisort($rows, 'position', SORT_DESC);
        return !empty($rows) ? $rows[0]->position : 0;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataArticle
     */
    public function getNext() {
        $next = $this->find()->where(['>', 'id', $this->id])->one();
        return $next;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataArticle
     */
    public function getPrev() {
        $prev = $this->find()->where(['<', 'id', $this->id])->orderBy('id desc')->one();
        return $prev;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataArticle
     */
    public function getFirst() {
        $prev = $this->find()->orderBy(['id' => SORT_ASC])->one();
        return $prev;
    }

    public static function fieldsPosition($block, $type, $block_id){
        if(!empty($block) && !empty($type) && !empty($block_id)) {
            switch ($block) {
                case 'image': {
                    $field = Yii2DataArticleImage::findOne($block_id);
                    break;
                }
                case 'slider': {
                    $field = Yii2DataArticleGallery::findOne($block_id);
                    break;
                }
                case 'text': {
                    $field = Yii2DataArticleText::findOne($block_id);
                    break;
                }
            }

            /* @var $field  Yii2DataArticleImage|Yii2DataArticleGallery|Yii2DataArticleText */
            switch ($type) {
                case 'up': {
                    if ($field->position > 0)
                        $field->position = (integer)$field->position - 1;
                    break;
                }
                case 'down': {
                    $field->position = (integer)$field->position + 1;
                    break;
                }
            }
            if(!empty($field))
                $field->save();
        }
    }

    public function create($post, $image)
    {
        if(!empty($post)){
            $this->load($post);
            $this->date = !empty($this->date) ? strtotime($this->date) : time();
            if(empty($this->seoUrl)){
                $this->seoUrl = TransliteratorHelper::process($this->name, '', 'en');
            }
            if(!empty($image))
                $this->image = $image;
            $this->save();
        }
    }
}
