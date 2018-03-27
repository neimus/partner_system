<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $user app\models\User */
/* @var $referrals app\models\User[] */

/* @var $referrer app\models\User */

use app\models\User;
use yii\helpers\Html;

$this->title = 'Профиль пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>

	<div class="jumbotron">
		<h1>Партнерская система</h1>

		<p class="lead">Уникальная возможность пригласить в нашу партнерскую систему любого желающего</p>

		<p>
			Ваша партнерская ссылка:
			<span class="label label-success">
				<?= Yii::$app->getUrlManager()->createAbsoluteUrl(['site/referral', 'id' => $user->id]) ?>
			</span>
		</p>
	</div>

	<div class="col-md-6">
        <?= $user->getPlaceholder(User::COL_USERNAME) ?>:
		<span class="text-muted"><?= $user->username ?></span> <br>

        <?= $user->getPlaceholder(User::COL_EMAIL) ?>:
		<span class="text-muted"><?= $user->email ?></span> <br>

        <?= $user->getPlaceholder(User::COL_DATE_CREATE) ?>:
		<span class="text-muted"><?= Yii::$app->getFormatter()->asDatetime($user->created_at) ?></span> <br>
	</div>

	<div class="col-md-6">
        <?php if ($referrer !== null): ?>
			<div class="col-md-12">
				Вы пришли от: <span class="text-info"><?= $referrer ?></span>
			</div>
        <?php endif; ?>
		<div class="col-md-12" style="padding-top: 10px">
            <?php if (!empty($referrals)): ?>
				От вас пришли:
				<ol>
                    <?php foreach ($referrals as $referral): ?>
						<li><span class="text-success"><?= $referral ?></span></li>
                    <?php endforeach; ?>
				</ol>
            <?php else: ?>
				Вы еще никого не пригласили :-(
            <?php endif; ?>
		</div>
	</div>
</div>
