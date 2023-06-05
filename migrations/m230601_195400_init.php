<?php

use yii\db\Migration;

/**
 * Class m230601_195400_init
 */
class m230601_195400_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('author', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string()->unique()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultExpression('now()'),
        ]);

        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'description' => $this->text()->null(),
            'date_release' => $this->date()->notNull(),
            'cover_path' => $this->text()->null(),
            'isbn' => $this->text()->null(),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultExpression('now()'),
        ]);

        $this->createTable('author_book', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
        ]);

        $this->createTable('author_follower', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'follower_phone' => $this->string(20)->notNull()
        ]);

        $this->createIndex(
            'idx-author_book-author_id-book_id',
            'author_book',
            [
                'author_id',
                'book_id'
            ]
        );

        $this->addForeignKey(
            'fk-author_book-author',
            'author_book',
            'author_id',
            'author',
            'id',
            'cascade',
        );

        $this->addForeignKey(
            'fk-author_book-book',
            'author_book',
            'book_id',
            'book',
            'id',
            'cascade',
        );

        $this->addForeignKey(
            'fk-author_follower-author',
            'author_follower',
            'author_id',
            'author',
            'id',
            'cascade',
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-author_book-author', 'author_book');
        $this->dropForeignKey('fk-author_book-book', 'author_book');
        $this->dropTable('author_book');
        $this->dropTable('author');
        $this->dropTable('book');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230601_195400_init cannot be reverted.\n";

        return false;
    }
    */
}
