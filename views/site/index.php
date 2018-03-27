<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

	<div class="jumbotron">
		<h1>Добро пожаловать!</h1>

		<p class="lead">Партнерская система.</p>

		<p>
            <?= Html::a('Зарегистрироваться', ['site/registration'], ['class' => 'btn btn-lg btn-success']) ?>
		</p>
	</div>

	<div class="body-content">

		<div class="row">
			<div class="col-lg-4">
				<h2>Выгодно</h2>

				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
				   et
				   dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
				   aliquip
				   ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
				   eu
				   fugiat nulla pariatur.</p>

				<p>
                    <?= Html::a('Регистрация', ['site/registration'], ['class' => 'btn btn-default']) ?>
				</p>
			</div>
			<div class="col-lg-4">
				<h2>Интересно</h2>

				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
				   et
				   dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
				   aliquip
				   ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
				   eu
				   fugiat nulla pariatur.</p>

				<p>
                    <?= Html::a('Регистрация', ['site/registration'], ['class' => 'btn btn-default']) ?>
				</p>
			</div>
			<div class="col-lg-4">
				<h2>Прибыльно</h2>

				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
				   et
				   dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
				   aliquip
				   ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
				   eu
				   fugiat nulla pariatur.</p>

				<p>
                    <?= Html::a('Регистрация', ['site/registration'], ['class' => 'btn btn-default']) ?>
				</p>
			</div>
		</div>

	</div>
</div>
