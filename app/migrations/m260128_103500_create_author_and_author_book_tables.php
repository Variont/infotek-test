<?php

use yii\db\Migration;

class m260128_103500_create_author_and_author_book_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'last_name' => $this->string()->notNull(),
            'first_name' => $this->string()->notNull(),
            'middle_name' => $this->string()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('{{%author_book}}', [
            'author_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-author_book', '{{%author_book}}', ['author_id', 'book_id']);

        $this->addForeignKey(
            'fk-author_book-author',
            '{{%author_book}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-author_book-book',
            '{{%author_book}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
    }
}
