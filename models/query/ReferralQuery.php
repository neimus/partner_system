<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.2018 */

namespace app\models\query;

use app\models\Referral;
use yii\db\ActiveQuery;

/**
 * ActiveQuery для [[Referral]].
 * @see Referral
 */
class ReferralQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Referral[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Referral|null
     */
    public function one($db = null): ?Referral
    {
        return parent::one($db);
    }
}
