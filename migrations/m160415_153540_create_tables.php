<?php

use yii\db\Schema;
use yii\db\Migration;

class m160415_153540_create_tables extends Migration {

    public function up() {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'image' => $this->string(100)->notNull(),
            'date' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
        ]);

        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('article_gallery', [
            'id' => $this->primaryKey(),
            'position' => $this->integer()->notNull(),
            'article_id' => $this->integer(),
        ]);

        $this->addForeignKey('article_gallery_article_fk',
            'article_gallery', 'article_id',
            'article', 'id',
            'CASCADE', 'CASCADE');


        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('article_image', [
            'id' => $this->primaryKey(),
            'image' => $this->string(100)->notNull(),
            'position' => $this->integer()->notNull(),
            'article_id' => $this->integer(),
        ]);

        $this->addForeignKey('article_image_article_fk',
            'article_image', 'article_id',
            'article', 'id',
            'CASCADE', 'CASCADE');

        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('article_text', [
            'id' => $this->primaryKey(),
            'position' => $this->integer()->notNull(),
            'article_id' => $this->integer(),
            'text' => $this->text(),
        ]);

        $this->addForeignKey('article_text_article_fk',
            'article_text', 'article_id',
            'article', 'id',
            'CASCADE', 'CASCADE');

        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('article_video', [
            'id' => $this->primaryKey(),
            'position' => $this->integer()->notNull(),
            'article_id' => $this->integer(),
            'text' => $this->text(),
        ]);

        $this->addForeignKey('article_video_article_fk',
            'article_video', 'article_id',
            'article', 'id',
            'CASCADE', 'CASCADE');

    }

    public function down() {
        return true;
    }
}
