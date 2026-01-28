<?php

use yii\db\Migration;

class m260128_095130_create_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32),
            'access_token' => $this->string(32),
            'role' => $this->string(20)->notNull()->defaultValue('user'),
            'phone' => $this->string(15)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $security = \Yii::$app->security;

        $this->insert('{{%user}}', [
            'username' => 'admin',
            'password_hash' => $security->generatePasswordHash('admin'),
            'auth_key' => $security->generateRandomString(),
            'access_token' => $security->generateRandomString(),
            'role' => 'admin',
            'phone' => '79997777777',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%user}}', [
            'username' => 'test',
            'password_hash' => $security->generatePasswordHash('test'),
            'auth_key' => $security->generateRandomString(),
            'access_token' => $security->generateRandomString(),
            'role' => 'user',
            'phone' => '79991111111',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function safeDown()
    {
    }
}
