<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.18
 */

namespace app\models\form;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\db\Expression;

/**
 * @property User|null $user
 */
class LoginForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var bool
     */
    public $rememberMe = true;

    /**
     * @var User|UserForm
     */
    private $_user;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'email'      => 'Почта',
            'password'   => 'Пароль',
            'rememberMe' => 'Запомнить?',
        ];
    }

    /**
     * Проверка пароля
     *
     * @param string $attribute
     * @param array $params
     *
     * @throws \yii\base\InvalidParamException
     */
    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePasswordHash($this->password)) {
                $this->addError($attribute, 'Неправильный пароль или почта (логин).');
            }
        }
    }

    /**
     * Осуществляет "вход" пользователя на сайт
     *
     * @return bool при успехе
     * @throws \yii\base\InvalidArgumentException
     */
    public function login(): bool
    {
        $sessionDuration = $this->rememberMe ? Yii::$app->params['sessionDuration'] : 0;
        if ($this->getUser() !== null && $this->validate()
            && Yii::$app->user->login($this->getUser(), $sessionDuration)) {

            $this->getUser()->last_login_date = new Expression('NOW()');
            $this->getUser()->save();

            return true;
        }

        return false;
    }

    /**
     * Возвращает пользователя по [[email]]
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::find()->getByEmail($this->email);
        }

        return $this->_user;
    }
}
