<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.18
 */

namespace app\models;

use app\models\query\UserQuery;
use InvalidArgumentException;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Модель для таблицы "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $created_at
 * @property string $last_login_date
 * @property string $reset_token_date
 * @property integer $is_deleted
 * @property integer $is_activated
 */
class User extends AbstractModel implements IdentityInterface
{
    /**
     * Статусы
     */
    const NOT_DELETED   = 0;
    const DELETED       = 1;
    const ACTIVATED     = 1;
    const NOT_ACTIVATED = 0;

    const COL_ID                   = 'id';
    const COL_EMAIL                = 'email';
    const COL_USERNAME             = 'username';
    const COL_AUTH_KEY             = 'auth_key';
    const COL_PASSWORD             = 'password';
    const COL_PASSWORD_HASH        = 'password_hash';
    const COL_PASSWORD_RESET_TOKEN = 'password_reset_token';
    const COL_DATE_CREATE          = 'created_at';
    const COL_DATE_LAST_LOGIN      = 'last_login_date';
    const COL_DATE_RESET_TOKEN     = 'reset_token_date';
    const COL_IS_DELETED           = 'is_deleted';
    const COL_IS_ACTIVATED         = 'is_activated';

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                [
                    self::COL_IS_DELETED,
                    self::COL_IS_ACTIVATED,
                ],
                'boolean',
            ],
            [
                [
                    self::COL_EMAIL,
                    self::COL_USERNAME,
                    self::COL_AUTH_KEY,
                    self::COL_PASSWORD_HASH,
                ],
                'required',
            ],
            [
                [
                    self::COL_EMAIL,
                    self::COL_USERNAME,
                    self::COL_AUTH_KEY,
                    self::COL_PASSWORD_RESET_TOKEN,
                ],
                'unique',
            ],
            [
                [
                    self::COL_DATE_CREATE,
                    self::COL_DATE_LAST_LOGIN,
                    self::COL_DATE_RESET_TOKEN,
                ],
                'safe',
            ],
            [
                [
                    self::COL_EMAIL,
                    self::COL_PASSWORD_HASH,
                    self::COL_PASSWORD_RESET_TOKEN,
                ],
                'string',
                'max' => 255,
            ],
            [[self::COL_EMAIL], 'email'],
            [[self::COL_USERNAME], 'string', 'min' => 2, 'max' => 55],
            [[self::COL_AUTH_KEY], 'string', 'max' => 32],
        ];
    }

    public function behaviors(): array
    {
        return
            [
                [
                    'class'              => TimestampBehavior::class,
                    'updatedAtAttribute' => false,
                    'value'              => function () {
                        return date('Y-m-d H:i:s', time());
                    },
                ],
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            self::COL_ID                   => 'ID',
            self::COL_EMAIL                => 'Почта',
            self::COL_USERNAME             => 'Имя',
            self::COL_AUTH_KEY             => 'Авторизационный ключ',
            self::COL_PASSWORD_HASH        => 'Хеш пароля',
            self::COL_PASSWORD_RESET_TOKEN => 'Токен для сброса пароля',
            self::COL_DATE_CREATE          => 'Дата создания',
            self::COL_DATE_LAST_LOGIN      => 'Дата последнего входа',
            self::COL_DATE_RESET_TOKEN     => 'Дата запроса токена для сброса',
            self::COL_IS_DELETED           => 'Удален ли пользователь',
            self::COL_IS_ACTIVATED         => 'Состояние пользователя',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints(): array
    {
        return [
            self::COL_ID                   => 'ID',
            self::COL_EMAIL                => 'электронная почта используется в качестве входа на сайт (логин)',
            self::COL_USERNAME             => 'Имя',
            self::COL_AUTH_KEY             => 'Авторизационный ключ',
            self::COL_PASSWORD_HASH        => 'Хеш пароля',
            self::COL_PASSWORD_RESET_TOKEN => 'Токен для сброса пароля',
            self::COL_DATE_CREATE          => 'Дата создания',
            self::COL_DATE_LAST_LOGIN      => 'Дата последнего входа',
            self::COL_DATE_RESET_TOKEN     => 'Дата запроса токена для сброса',
            self::COL_IS_DELETED           => 'Удален ли пользователь',
            self::COL_IS_ACTIVATED         => 'Активирован ли пользователь',
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery активный запрос используемый AR классом.
     */
    public static function find(): UserQuery
    {
        return new UserQuery(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * Находит идентификатор пользователя по ID.
     *
     * @param string|int $id идентификатор пользователя
     *
     * @return User|null
     * Null возвращается в случае, если идентификатор пользователя не найден
     * или он находится в не активном состоянии или выставлен флаг удален (например activate=0 и/или deleted=1)
     */
    public static function findIdentity($id): ?User
    {
        if (Yii::$app->getSession()->has('user-' . $id)) {
            return new self(Yii::$app->getSession()->get('user-' . $id));
        }

        return self::findOne([
            self::COL_ID           => $id,
            self::COL_IS_ACTIVATED => self::ACTIVATED,
            self::COL_IS_DELETED   => self::NOT_DELETED,
        ]);
    }

    /**
     * Находит идентификатор пользователя по токену
     *
     * @param mixed $token Токен для поиска
     * @param mixed $type Тип токена
     *
     * @return IdentityInterface|User|null
     *
     * Null возвращается в случае, если идентификатор пользователя не найден
     * или он находится в не активном состоянии или выставлен флаг удален (например activate=0 и/или deleted=1)
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" не реализован.');
    }

    /**
     * Проверка пароля
     *
     * @param string $password пароль для проверки
     *
     * @return bool Если пароль является допустимым для текущего пользователя
     * @throws \yii\base\InvalidParamException
     */
    public function validatePasswordHash($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Проверяет заданный ключ аутентификации.
     * Это необходимо, если [[User::enableAutoLogin]] включен.
     *
     * @param string $authKey ключ аутентификации
     *
     * @return bool действителен ли данный ключ аутентификации.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Генерирует хэш от пароля и записывает его текущему пользователю
     *
     * @param string $password
     *
     * @throws \yii\base\Exception
     * @throws \InvalidArgumentException
     */
    public function setPassword($password)
    {
        if (\is_string($password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        } else {
            throw new InvalidArgumentException('Пароль должен быть строкой');
        }
    }

    /**
     * Генерация нового токена для восстановления пароля
     *
     * @throws \yii\base\Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Удаление токена для восстановления пароля
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
        $this->reset_token_date = time();
    }

    /**
     * Создает ключ аутентификации для «запомнить меня»
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Возвращает всех участников партнерской программы, которых привел данный пользователь
     *
     * @return User[]|array|ActiveRecord[]
     */
    public function getReferrals(): array
    {
        return self::find()->getReferrals($this->getId());
    }

    public function getReferrer()
    {
        return self::find()->getReferrer($this->getId());
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->username, $this->email);
    }

}
