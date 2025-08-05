<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tariff_change_history}}`.
 */
class m250804_082000_create_tariff_change_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tariff_change_history}}', [
            'id' => $this->primaryKey(),
            'subscriber_id' => $this->integer()->notNull(),
            'old_tariff_id' => $this->integer()->null(),
            'new_tariff_id' => $this->integer()->null(),
            'changed_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Внешние ключи
        $this->addForeignKey(
            'fk-history-subscriber_id',
            '{{%tariff_change_history}}',
            'subscriber_id',
            '{{%subscriber}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-history-old_tariff_id',
            '{{%tariff_change_history}}',
            'old_tariff_id',
            '{{%tariff}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-history-new_tariff_id',
            '{{%tariff_change_history}}',
            'new_tariff_id',
            '{{%tariff}}',
            'id',
            'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-history-new_tariff_id', '{{%tariff_change_history}}');
        $this->dropForeignKey('fk-history-old_tariff_id', '{{%tariff_change_history}}');
        $this->dropForeignKey('fk-history-subscriber_id', '{{%tariff_change_history}}');
        $this->dropTable('{{%tariff_change_history}}');
    }
}