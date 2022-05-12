<?php

use yii\db\Migration;

/**
 * Class m220512_155655_alter_user_add_column
 */
class m220512_155655_alter_user_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'firstname', $this->string(45));
        $this->addColumn('user', 'lastname', $this->string(45));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'firstname');
        $this->dropColumn('user', 'lastname');
    }
}
