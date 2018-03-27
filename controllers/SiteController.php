<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.18
 */

namespace app\controllers;

use app\helpers\ReferralHelper;
use app\models\AbstractModel;
use app\models\form\LoginForm;
use app\models\form\UserForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Главная страница
     *
     * @return Response|string
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Страница входа
     *
     * @return Response|string
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginForm = new LoginForm();
        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goBack();
        }

        $loginForm->password = '';

        return $this->render('login', [
            'model' => $loginForm,
        ]);
    }

    /**
     * Страница сохранения реферера (пригласившего)
     *
     * @param int $id
     *
     * @return Response|string
     */
    public function actionReferral(int $id)
    {
        if (Yii::$app->user->isGuest) {
            ReferralHelper::setReferrerId($id);
        }

        return $this->redirect('site/registration');
    }

    /**
     * Страница регистрации
     *
     * @return Response|string
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\db\Exception
     */
    public function actionRegistration()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $userForm = new UserForm();
        if ($userForm->load(Yii::$app->request->post())) {

            $referrerId = ReferralHelper::getReferrerId();
            $referrer = $referrerId !== null ? $this->getUser((int)$referrerId) : null;
            $userForm->setReferrer($referrer);

            if ($userForm->save() && $userForm->login()) {
                $message = 'Спасибо за регистрацию.';
                if ($referrer !== null) {
                    $message .= ' Вас пригласил ' . $referrer;
                }
                Yii::$app->session->setFlash('success', $message);
                ReferralHelper::removeReferrer();

                return $this->goHome();
            }
            $this->addErrorForForm($userForm);
        }
        $userForm->password = '';

        return $this->render('registration', [
            'model' => $userForm,
        ]);
    }

    /**
     * Страница выхода
     *
     * @return Response|string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Страница профиля
     *
     * @return Response|string
     *
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionProfile()
    {
        try {
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            if ($user !== null) {
                return $this->render('profile', [
                    'user'      => $user,
                    'referrals' => $user->getReferrals(),
                    'referrer'  => $user->getReferrer(),
                ]);
            }

        } catch (\Exception $e) {
            Yii::error($e->getMessage(), 'profile');
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), 'profile');
        }

        return $this->goHome();
    }

    /**
     * @param AbstractModel $form
     */
    private function addErrorForForm(AbstractModel $form): void
    {
        $message = $form->hasErrors()
            ? $form->getModelErrors()
            : 'При регистрации произошла ошибка, мы уже работаем над ней. Для решения проблемы напишите нам ' . Yii::$app->params['supportEmail'];
        Yii::$app->session->setFlash('error', $message);
    }

    /**
     * @param $id
     *
     * @return User|null
     */
    private function getUser(int $id): ?User
    {
        return User::find()->getById($id);
    }
}
