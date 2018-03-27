<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.18
 */

namespace app\models;

use app\models\query\ReferralQuery;
use yii\db\ActiveRecord;

/**
 * Class Referral
 *
 * @property integer $id
 * @property string $user_id
 * @property string $referral_id
 */
class Referral extends AbstractModel
{
    const COL_ID          = 'id';
    const COL_USER_ID     = 'user_id';
    const COL_REFERRAL_ID = 'referral_id';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    self::COL_USER_ID,
                    self::COL_REFERRAL_ID,
                ],
                'required',
            ],
            [self::COL_REFERRAL_ID, 'unique'],
            [
                [self::COL_USER_ID],
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::class,
                'targetAttribute' => [self::COL_USER_ID => User::COL_ID],
            ],
            [
                [self::COL_REFERRAL_ID],
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::class,
                'targetAttribute' => [self::COL_REFERRAL_ID => User::COL_ID],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            self::COL_ID          => 'ID',
            self::COL_USER_ID     => 'Пригласивший пользователь',
            self::COL_REFERRAL_ID => 'Участник реферальной программы',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints(): array
    {
        return [
            self::COL_ID          => 'ID',
            self::COL_USER_ID     => 'Пригласивший пользователь',
            self::COL_REFERRAL_ID => 'Участник реферальной программы',
        ];
    }

    /**
     * @inheritdoc
     * @return ReferralQuery активный запрос используемый AR классом.
     */
    public static function find(): ReferralQuery
    {
        return new ReferralQuery(static::class);
    }

    /**
     * Возвращает пользователя, который пригласил
     * @return User|ActiveRecord
     */
    public function getReferrer(): ?User
    {
        return $this->hasOne(User::class, [User::COL_ID => self::COL_USER_ID])->one();
    }

    /**
     * Возвращает участника реферальной программы
     *
     * @return User|ActiveRecord
     */
    public function getReferral(): ?User
    {
        return $this->hasOne(User::class, [User::COL_ID => self::COL_REFERRAL_ID])->one();
    }
}