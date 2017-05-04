<?php

namespace maks757\articlesdata\entities;

use Yii;

/**
 * This is the model class for table "article_text".
 *
 * @property integer $id
 * @property string $position
 * @property integer $article_id
 * @property string $text
 *
 * @property Yii2DataArticle $article
 */
class Yii2DataArticleText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position'], 'required'],
            [['text'], 'string'],
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
            'id' => 'ID',
            'position' => 'Position',
            'article_id' => 'Article ID',
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
}
