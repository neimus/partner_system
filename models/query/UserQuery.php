<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.2018 */

namespace app\models\query;

use app\models\Referral;
use app\models\User;
use yii\db\ActiveQuery;

/**
 * ActiveQuery для [[User]].
 * @see User
 */
class UserQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return User[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return User|null
     */
    public function one($db = null): ?User
    {
        return parent::one($db);
    }

    public function getByEmail($email): ?User
    {
        return $this->where(['=', User::COL_EMAIL, $email])
            ->andWhere(['=', User::COL_IS_ACTIVATED, User::ACTIVATED])
            ->andWhere(['=', User::COL_IS_DELETED, User::NOT_DELETED])
            ->one();
    }

    public function getById($id): ?User
    {
        return $this->where(['=', User::COL_ID, $id])
            ->andWhere(['=', User::COL_IS_ACTIVATED, User::ACTIVATED])
            ->andWhere(['=', User::COL_IS_DELETED, User::NOT_DELETED])
            ->one();
    }

    /**
     * @param $id
     *
     * @return array|User[]
     */
    public function getReferralsByUserId($id): array
    {
        return $this->innerJoin(Referral::tableName(),
            User::columnName(User::COL_ID) . '=' . Referral::columnName(Referral::COL_REFERRAL_ID))
            ->where(['=', Referral::columnName(Referral::COL_USER_ID), $id])
            ->andWhere(['=', User::columnName(User::COL_IS_ACTIVATED), User::ACTIVATED])
            ->andWhere(['=', User::columnName(User::COL_IS_DELETED), User::NOT_DELETED])
            ->all();
    }
}
