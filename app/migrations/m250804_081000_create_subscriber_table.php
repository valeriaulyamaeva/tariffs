<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscriber}}`.
 */
class m250804_081000_create_subscriber_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscriber}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(100)->notNull(),
            'contract_number' => $this->string(10)->notNull()->unique(),
            'phone' => $this->string(13)->notNull(),
            'address' => $this->string(100)->notNull(),
            'tariff_id' => $this->integer()->notNull(),
            'is_blocked' => $this->boolean()->defaultValue(false),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-subscriber-tariff_id',
            '{{%subscriber}}',
            'tariff_id',
            '{{%tariff}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-subscriber-tariff_id', '{{%subscriber}}');
        $this->dropTable('{{%subscriber}}');
    }
}
