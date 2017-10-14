<?php

namespace app\controllers;

use Yii;
use app\models\Employee;
use app\models\EmployeeSearch;
use app\models\User;
use app\models\JobTitle;
use app\models\Department;
use app\models\Attachment;
use app\models\UserAttachment;

use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * EmployeesController implements the CRUD actions for Employee model.
 */
class EmployeesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete', 'create', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['delete', 'index', 'create'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create'],
                        'roles' => ['employer'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sql = "SELECT u.first_name, u.surname, u.email, u.username, e.address, e.cellphone, e.date_appointed, e.id_number FROM user AS u INNER JOIN employee AS e ON u.id=e.user_id";
        //$totalCount = Yii::$app­->db­->createCommand("SELECT COUNT(*) FROM ". $sql ." AS [c]")­->queryScalar();
        $totalCount = 1;
        $dataProvider =  new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => ( int )$totalCount,
            'pagination' => [
                'pageSize' => 20,
            ],          
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $employee = $this->findModel($id);
        return $this->render('view', [
            'employee' => $employee,
            'user' => $employee->user,
            'job_title' => $employee->jobTitle,
            'department' => $employee->department,
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $employee = new Employee();
        $user = new User();
        $attachment = new Attachment();

        // set fake user_id in the employee model to avoid validatoin error
        $employee->user_id = 0;

        /*/ Set the date fields to the DB format before validation
        $employee->date_appointed = Yii::$app->formatter->asDate(date_create_from_format('d/m/Y', $_POST["Employee[date_appointed]"], 'php:Y-m-d');
        $employee->date_of_birth = Yii::$app->formatter->asDate(date_create_from_format('d/m/Y', $_POST["Employee[date_of_birth]"], 'php:Y-m-d');
        */

        if (
            $employee->load(Yii::$app->request->post()) 
            && 
            $user->load(Yii::$app->request->post())
            &&
            $employee->validate()
            && 
            $user->validate()
        ) {
            
            $dbTrans = Yii::$app->db->beginTransaction();
            
            // Save user first
            $userSaved = $user->save();

            if($userSaved) {
                $employee->user_id = $user->id;

                // Take care of profile pic here and move it the upload folder
                $profileImage = UploadedFile::getInstance($employee, 'profile_pic');

                // Get the extension
                $ext = end((explode(".", $profileImage->name)));

                $randomStrFilename = Yii::$app->security->generateRandomString() .'.'. $ext;
                $employee->profile_pic = $randomStrFilename;

                $path_to_save_file = Yii::getAlias('@uploadedfilesdir') . '/profilepics/'. $employee->profile_pic;

                if($employee->uploadProfilePic($profileImage, $path_to_save_file)) {
                    // upload stored
                }
                
                $employeeSaved = $employee->save();

                if($employeeSaved)
                {
                    // Take care of any attachments and move them to the upload folder
                    $attachedFiles[] = UploadedFile::getInstances($attachment, 'filenames');
                    $attachmentsBasePath = Yii::getAlias('@uploadedfilesdir') . '/attachments/';

                    foreach ($attachedFiles as $attFile) {
                        // Get the extension
                        $ext = end((explode(".", $attFile->name)));

                        $randomName = Yii::$app->security->generateRandomString() .'.'. $ext;
                        $attachment->filename = $randomName;
                        $attachment->size = $attFile->size;
                        $attachment->type = $attFile->type;
                        $attachment->date_uploaded = date('Y:M:j H:i:s');

                        $attachmentSaved = $attachment->save();

                        if($attachmentSaved) {
                            $user_attachment = new UserAttachment;
                            $user_attachment->employee_id = $employee->id;
                            $user_attachment->attachment_id = $attachment->id;

                            $userAttachmentSaved = $$user_attachment->save();

                            if($userAttachmentSaved) {

                            } else {
                                $this->session->setFlash('warning', 'Error saving one or more of your attachments');
                            }
                        }
                    }

                    if($attachment->upload($attachmentsBasePath, $attachedFiles)) {
                        // uploads stored
                    }

                    $dbTrans->commit();
                } else {
                    $dbTrans->rollback();
                }

                // return $this->redirect(['view', 'id' => $employee->id]);
            } else {
                $dbTrans->rollback();

                return $this->render('create', [
                    'employee' => $employee,
                    'user' => $user,
                    'attachment' => $attachment,
                ]);
            }
        } else {
            return $this->render('create', [
                'employee' => $employee,
                'user' => $user,
                'attachment' => $attachment,
            ]);
        }
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $employee = $this->findModel($id);
        //$attachment = Attachment::find()
        $attachment = new Attachment;

        if ($employee->load(Yii::$app->request->post()) && $employee->save()) {
            return $this->redirect(['view', 'id' => $employee->id]);
        } else {
            return $this->render('update', [
                'employee' => $employee,
                'user' => $employee->user,
                'attachment' => $attachment
            ]);
        }
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($employee = Employee::findOne($id)) !== null) {
            return $employee;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
