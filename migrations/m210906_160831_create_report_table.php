<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report}}`.
 */
class m210906_160831_create_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report}}', [
            'id' => $this->primaryKey(),
            'id_city' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'text' => $this->text()->notNull(),
            'rating' => $this->integer(),
            'img' => $this->string(255),
            'id_author' => $this->integer()->notNull(),
            'date_create' => $this->date()->defaultValue(date('Y-m-d')),
        ]);

        $this->createIndex(
            'idx-report-id_city',
            'report',
            'id_city'
        );

        $this->addForeignKey(
            'fk-report-id_city',
            'report',
            'id_city',
            'city',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-report-id_author',
            'report',
            'id_author'
        );

        $this->addForeignKey(
            'fk-report-id_author',
            'report',
            'id_author',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-report-id_user',
            'report'
        );

        $this->dropIndex(
            'idx-report-id_user',
            'report'
        );

        $this->dropForeignKey(
            'fk-report-id_city',
            'report',
        );

        $this->dropIndex(
            'idx-report-id_city',
            'report'
        );

        $this->dropTable('{{%report}}');
    }
}
