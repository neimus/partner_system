<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.18
 */

namespace app\helpers;

class ReferralHelper
{
    const KEY_REFERRER_ID = 'referrer_id';

    public static function setReferrerId(int $referrerId)
    {
        \Yii::$app->getSession()->set(self::KEY_REFERRER_ID, $referrerId);
    }

    public static function getReferrerId()
    {
        return \Yii::$app->getSession()->get(self::KEY_REFERRER_ID, null);
    }

    public static function removeReferrer()
    {
        return \Yii::$app->getSession()->remove(self::KEY_REFERRER_ID);
    }
}