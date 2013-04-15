<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','game','MakeMove','StartGame','CreateBoard'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
    public function actionNotifyNode(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://localhost');

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_PORT, 8000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);

        curl_setopt($ch, CURLOPT_POST, true);

        $fields_string = "";
        $fields = array(
            "_domain"=>"rfq",
            "_name"=>"_moveMade"
        );
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

    }
    public function actionGame(){

        if(!Yii::app()->user->isGuest){
            $currentUser = Yii::app()->user->getUser();

            //If logged in show gameboard and buttons
            $fields = array(
                "_domain"=>"rfq",
                "_name"=>"_boardRequest",
                "esl"=>$currentUser->Esl
            );
            $result = FSCore::sendEventToServer($fields);

            $result = json_decode($result);
            if($result->success == "true"){
                if($result->gameOver == 'true' && $result->isMyTurn == 'Y'){

                    Yii::app()->user->setFlash("success","Winner!");
                } else if($result->gameOver == 'true' && $result->isMyTurn == 'N'){

                    Yii::app()->user->setFlash("notice","You lost!");
                }
                $board = $result->board;
                $this->render("game",array("board"=>$board,"player"=>$result->player,'myTurn'=>$result->isMyTurn));
            } else
                $this->render("game");
        }

    }
    public function actionStartGame(){
        if(!Yii::app()->user->isGuest){
            $currentUser = Yii::app()->user->getUser();

            //If logged in show gameboard and buttons
            $fields = array(
                "_domain"=>"rfq",
                "_name"=>"_startGame",
                "esl"=>$currentUser->Esl
            );
            $result = json_decode(FSCore::sendEventToServer($fields));

            if($result->success == false){
                Yii::app()->user->setFlash('error',$result->message);
            } else if($result->success == true){
                Yii::app()->user->setFlash('success',$result->message);
            }
            $this->redirect(array('users/game'));
        }
    }
    public  function actionMakeMove(){
        $column = Yii::app()->request->getParam('column');
        if(!Yii::app()->user->isGuest){
            $currentUser = Yii::app()->user->getUser();

            //If logged in show gameboard and buttons
            $fields = array(
                "_domain"=>"rfq",
                "_name"=>"_makeMove",
                "esl"=>$currentUser->Esl,
                "number"=>$column
            );
            $result = json_decode(FSCore::sendEventToServer($fields));
           //esult = FSCore::sendEventToServer($fields);
           //ho var_dump($result);
           //turn;
            //$result = FSCore::sendEventToServer($fields);
            //echo var_dump($result);
            //return;
            if($result->success == false){
                Yii::app()->user->setFlash('error',$result->message);
            }
            //$this->actionNotifyNode();
            //$this->redirect(array('users/game'));
        }
    }

    public function actionCreateBoard(){
        $currentUser = Yii::app()->user->getUser();
        $fields = array(
            "_domain"=>"rfq",
            "_name"=>"_boardRequest",
            "esl"=>$currentUser->Esl
        );
        $result = FSCore::sendEventToServer($fields);

        $result = json_decode($result);

            $board = $result->board;
            $this->renderpartial("board",array("board"=>$board,"player"=>$result->player,'myTurn'=>$result->isMyTurn));
    }
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
