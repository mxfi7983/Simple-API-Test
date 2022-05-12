<?php

use yii\db\Migration;

/**
 * Class m220512_155839_create_table_photos
 */
class m220512_155839_create_table_photos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('photo', [
            'id' => $this->primaryKey(),
            'album_id' => $this->integer()->notNull(),
            'title' => $this->string(),
        ]);

        $this->addForeignKey('fk_photo_album', 'photo', 'album_id', 'album', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('photo');
    }
}
