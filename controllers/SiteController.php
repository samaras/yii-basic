<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionInitAuth()
    {
        $auth = Yii::$app->authManager;

        // Reset all
        $auth->removeAll();

        // add "createEmployee" permission
        $permCreateEmployee = $auth->createPermission('createEmployee');
        $permCreateEmployee->description = 'Create an employee user';
        $auth->add($permCreateEmployee);

        // add "updateEmployee" permission
        $permUpdateEmployee = $auth->createPermission('updateEmployee');
        $permUpdateEmployee->description = 'Update employee user';
        $auth->add($permUpdateEmployee);

        // add "deleteEmployee" permission
        $permDeleteEmployee = $auth->createPermission('deleteEmployee');
        $permDeleteEmployee->description = 'Delete employee user';
        $auth->add($permDeleteEmployee);

        // add "createDepartment" permission
        $permCreateDepartment = $auth->createPermission('createDepartment');
        $permCreateDepartment->description = 'Create department';
        $auth->add($permCreateDepartment);

        // add "updateDepartment" permission
        $permUpdateDepartment = $auth->createPermission('updateDepartment');
        $permUpdateDepartment->description = 'Update department';
        $auth->add($permUpdateDepartment);

        // add "deleteDepartment" permission
        $permDeleteDepartment = $auth->createPermission('deleteDepartment');
        $permDeleteDepartment->description = 'Delete department';
        $auth->add($permDeleteDepartment);


        // add "createJobTitle" permission
        $permCreateJobTitle = $auth->createPermission('createJobTitle');
        $permCreateJobTitle->description = 'Create a job title';
        $auth->add($permCreateJobTitle);

        // add "updateJobTitle" permission
        $permUpdateJobTitle = $auth->createPermission('updateJobTitle');
        $permUpdateJobTitle->description = 'Update a job title';
        $auth->add($permUpdateJobTitle);

        // add "deleteEmployee" permission
        $permDeleteJobTitle = $auth->createPermission('deleteJobTitle');
        $permDeleteJobTitle->description = 'Delete a job title';
        $auth->add($permDeleteJobTitle);

        // end of permissions

        // add "employer" role and give this role the "createEmployee" permission
        $roleEmployer = $auth->createRole('employer');
        $auth->add($roleEmployer);
        $auth->addChild($roleEmployer, $permCreateEmployee);
        $auth->addChild($roleEmployer, $permUpdateEmployee);
        $auth->addChild($roleEmployer, $permCreateDepartment);
        $auth->addChild($roleEmployer, $permUpdateDepartment);
        $auth->addChild($roleEmployer, $permCreateJobTitle);
        $auth->addChild($roleEmployer, $permUpdateJobTitle);

        // add "admin" role and give this role the "deleteEmployee" permission
        $roleAdmin = $auth->createRole('admin');
        $auth->add($roleAdmin);
        $auth->addChild($roleAdmin, $permDeleteEmployee);
        $auth->addChild($roleAdmin, $permDeleteDepartment);
        $auth->addChild($roleAdmin, $permDeleteJobTitle);
        $auth->addChild($roleAdmin, $roleEmployer);

        $auth->assign($roleEmployer, 2);
        $auth->assign($roleAdmin, 1);
    }
}
