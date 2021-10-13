<?php

use yii\db\Migration;

/**
 * Class m211011_152934_alter_report_id_author
 */
class m211011_152934_alter_report_id_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('report', 'id_author', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211011_152934_alter_report_id_author cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211011_152934_alter_report_id_author cannot be reverted.\n";

        return false;
    }
    */
}
