<?php

use yii\db\Migration;

/**
 * Class m220512_155834_create_table_albums
 */
class m220512_155834_create_table_albums extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('album', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull()
        ]);

        $this->addForeignKey('fk_album_user', 'album', 'user_id', 'user', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('album');
    }
}
