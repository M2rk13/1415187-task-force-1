<?php

use yii\db\Migration;

/**
 * Class m210925_110926_add_column_task_table
 */
class m210925_110926_add_column_task_table extends Migration
{
    private const TABLE_NAME = 'task';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'address_comment', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'address_comment');
    }
}
