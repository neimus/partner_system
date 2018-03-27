<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Партнерская система</h1>
    <br>
</p>

Реферальная система: регистрация пользователя с привязкой к другому пользователю.

Постановка:

   1. Пользователь регистрируется на сайте и авторизуется,
   2. Пользователь (допустим, пользователь А) может сгенерирвать реферальную ссылку, пройдя по которой и зарегистрировавшись, другой пользователь (допустим пользователь Б) "привязывается" к тому пользователю, который эту ссылку сгенерировал (к пользователю А). То есть пользователь Б считается приглашенным пользователем А.
   3. Далее, пользователь Б может сгенерировать ссылку и передать её другому пользователю (допустим пользователю В). Так получается "дерево" привязок.


 **Обратите внимание что:**
 - реферальная ссылка постоянная, по ней может зарегистрироваться любое количество пользователей.
 - регистрация доступна как по реферальной ссылке (с привязкой к другому пользователю), так и обычная регистрация без привязки.


Пример:

   1. Если идет регистрация по реферальной ссылке, то на странице регистрации надо выводить: вы пришли от (почта),
   2. В личном кабинете (допустим, страница редактирования профиля) выводить информацию о том, от кого зарегистрировался пользователь, и о том, кого зарегистрировал пользователь, пример:

Вы пришли от (почта) -- в случае регистрации пользователем через реферальную ссылку

От вас пришли:
1) Почта А
2) Почта Б
3) …
-- список тех пользователей, которые зарегистрировались по ссылке текущего пользователя


Фреймворк: Yii2 
 
[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-basic.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-basic)


REQUIREMENTS
------------

PHP 7.1, Nginx


INSTALLATION
------------


Установите [Composer](http://getcomposer.org/), инструкция по установке
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

Для установки настройте соединение с БД (см. CONFIGURATION) и запустите:

~~~
php composer.phar install
php yii migrate/up
~~~

CONFIGURATION
-------------

### Database

Создайте копию файлов `config/params.php` и `config/db.php`. Настройте соединение с БД `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```
