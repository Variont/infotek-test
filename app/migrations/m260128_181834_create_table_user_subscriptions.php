<?php

use yii\db\Migration;

class m260128_181834_create_table_user_subscriptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_subscriptions}}', [
            'user_id'   => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-unique-user-author',
            '{{%user_subscriptions}}',
            ['user_id', 'author_id'],
            true
        );

        $this->addForeignKey(
            'fk-user_subscriptions-user_id',
            '{{%user_subscriptions}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_subscriptions-author_id',
            '{{%user_subscriptions}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
