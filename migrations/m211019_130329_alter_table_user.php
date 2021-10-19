<?php

use yii\db\Migration;

/**
 * Class m211019_130329_alter_table_user
 */
class m211019_130329_alter_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn('user', 'email_confirm_token', $this->string()->unique()->after('email'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'status');
        $this->dropColumn('user', 'email_confirm_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211019_130329_alter_table_user cannot be reverted.\n";

        return false;
    }
    */
}
