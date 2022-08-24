<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%games}}`.
 */
class m220822_112656_create_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%game}}', [
            'id' => $this->primaryKey()->notNull(),
            'answer' => $this->string(10),
            'attempt_1' => $this->string(10),
            'attempt_2' => $this->string(10),
            'attempt_3' => $this->string(10),
            'attempt_4' => $this->string(10),
            'attempt_5' => $this->string(10),
            'attempt_6' => $this->string(10),
            'url' => $this->string()->notNull()->unique(),
            'user_id' => $this->integer(),
            'created_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%game}}');
    }
}
