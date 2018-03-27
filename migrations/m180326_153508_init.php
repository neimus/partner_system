<?php

use app\migrations\MigrationMySQL;

/**
 * Class m180326_153508_init
 */
class m180326_153508_init extends MigrationMySQL
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->dbType === 'mysql') {
            $this->beginUp();
            /**
             * **********************************
             *          ТАБЛИЦА USER
             * **********************************
             */
            $this->dropTableIsExists('user');
            $this->beginCreateTable();
            $this->createTable('user', [
                'id'                   => $this->primaryKey(11)->unsigned()->comment('Id'),
                'username'             => $this->string(55)->notNull()->unique()->comment('Имя пользователя'),
                'email'                => $this->string(255)->notNull()->unique()->comment('Электронная почта'),
                'auth_key'             => $this->string(32)->notNull()->unique()->comment('Авторизационный ключ'),
                'password_hash'        => $this->string(255)->notNull()->comment('Хеш пароля'),
                'password_reset_token' => $this->string(255)->unique()->defaultValue(null)
                    ->comment('Токен для сброса пароля'),
                'created_at'           => $this->dateTime()->notNull()->comment('Дата создания'),
                'last_login_date'      => $this->dateTime()->comment('Дата последнего входа'),
                'reset_token_date'     => $this->dateTime()->comment('Дата запроса токена для сброса'),
                'is_deleted'           => $this->boolean()->notNull()->defaultValue(false)
                    ->comment('Флаг для удаления пользователя'),
                'is_activated'         => $this->boolean()->notNull()->defaultValue(true)
                    ->comment('Активирован ли пользователь'),
            ], $this->getOptions());
            $this->addCommentOnTable('user', 'Пользователи');

            $this->endCreateTable();

            /**
             * **********************************
             *          ТАБЛИЦА REFERRAL
             * **********************************
             */
            $this->dropTableIsExists('referral');
            $this->beginCreateTable();
            $this->createTable('referral', [
                'id'          => $this->primaryKey(11)->unsigned()->comment('Id'),
                'user_id'     => $this->integer(11)->notNull()->unsigned()
                    ->comment('Id пользователя, который пригласил'),
                'referral_id' => $this->integer(255)->notNull()->unique()->unsigned()->comment('Id участника партнерской программы'),
            ], $this->getOptions());
            $this->addCommentOnTable('referral', 'Участники партнерской программы');
            $this->endCreateTable();

            $this->setIndex('referral', 'user_id');
            $this->setForeignKey('referral', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
            $this->setForeignKey('referral', 'referral_id', 'user', 'id', 'CASCADE', 'CASCADE');

            $this->endUp();
        } else {
            echo 'The database is not MySQL format, the migration is not possible';
        }

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->dbType === 'mysql') {
            $this->beginDown();
            $this->dropTableIsExists('referral');
            $this->dropTableIsExists('user');
            $this->endDown();

            return true;
        }

        echo 'The database is not MySQL format, the migration is not possible';

        return false;
    }
}
