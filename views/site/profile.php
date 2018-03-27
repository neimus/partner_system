<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $user app\models\User */

use app\models\User;
use yii\helpers\Html;

$this->title = 'Профиль пользователя';
$this->params['breadcrumbs'][] = $this->title;
$referrals = $user->getReferrals();
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
		<div class="col-md-12">
            <?php if (!empty($referrals)): ?>
				Вы уже пригласили:
				<ul>
                    <?php foreach ($referrals as $referral): ?>
						<li><span class="text-primary"><?= $referral->username ?></span></li>
                    <?php endforeach; ?>
				</ul>
            <?php else: ?>
				Вы еще никого не пригласили :-(
            <?php endif; ?>
		</div>
	</div>
</div>
