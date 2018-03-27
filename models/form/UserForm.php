<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.18
 */

namespace app\models\form;

use app\models\Referral;
use app\models\User;
use Yii;
use yii\helpers\ArrayHelper;

class UserForm extends User
{
    const COL_PASSWORD = 'password';

    public $password;

    /**
     * @var User пользоватиель, который пригласил другого участника
     */
    private $referrer;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                [
                    self::COL_EMAIL,
                    self::COL_USERNAME,
                ],
                'required',
            ],
            [
                [
                    self::COL_EMAIL,
                ],
                'string',
                'max' => 255,
            ],
            [[self::COL_EMAIL], 'email'],
            [[self::COL_EMAIL, self::COL_USERNAME], 'unique'],
            [[self::COL_USERNAME], 'string', 'min' => 2, 'max' => 55],
            [[self::COL_PASSWORD], 'string', 'min' => 6, 'max' => 32],
            [[self::COL_PASSWORD], 'validatePassword'],
        ];

    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            self::COL_PASSWORD => 'Пароль',
        ]);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        return ArrayHelper::merge(parent::attributeHints(), [
            self::COL_PASSWORD => 'минимальная длина пароля 6 символов',
        ]);
    }

    /**
     * @param $attribute
     */
    public function validatePassword($attribute): void
    {
        if ($this->isNewRecord && empty($this->password)) {
            $this->addError(self::COL_PASSWORD, 'Необходимо заполнить "Пароль".');
        }
        if ($this->password !== null) {
            $len = \strlen($this->password);
            if ($len < 6 || $len > 32) {
                $this->addError(self::COL_PASSWORD, 'Длина пароля должна быть от 6 до 32 символов');
            }
        }
    }

    /**
     * Задает пользователя, который пригласил другого участника
     *
     * @param User|null $user
     */
    public function setReferrer(?User $user): void
    {
        $this->referrer = $user;
    }

    /**
     * @inheritDoc
     * @throws \yii\db\Exception
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->setPassword($this->password);
            $this->generateAuthKey();

            if (parent::save($runValidation, $attributeNames)) {
                $this->saveReferral();

                $transaction->commit();
            } else {
                throw new \LogicException('Не удалось сохранить пользователя.');
            }
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage(), 'registration');
            $transaction->rollBack();

            return false;
        }

        return true;
    }

    /**
     * Осуществляет "вход" пользователя на сайт через LoginForm
     *
     * @return bool
     *
     * @throws \yii\base\InvalidArgumentException
     */
    public function login(): bool
    {
        if (!$this->isNewRecord) {
            $loginForm = new LoginForm(
                [
                    'email'      => $this->email,
                    'password'   => $this->password,
                    'rememberMe' => true,
                ]);

            return $loginForm->login();
        }

        return false;
    }

    /**
     * Сохраняет участника в партнерскую программу
     *
     * @throws \LogicException
     */
    private function saveReferral(): void
    {
        if ($this->referrer !== null) {
            $referral = new Referral();
            $referral->user_id = $this->referrer->id;
            $referral->referral_id = $this->id;
            if (!$referral->validate() || !$referral->save()) {
                throw new \LogicException('Не удалось добавить участника в партнерскую программу');
            }
        }
    }
}
