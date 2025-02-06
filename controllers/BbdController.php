<?php

namespace lmy\humhub\modules\bbd\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\components\access\ControllerAccess;
use humhub\modules\admin\permissions\ManageSpaces;

use humhub\modules\content\components\ContentContainerControllerAccess;
use lmy\humhub\modules\bbd\models\Floor;
use lmy\humhub\modules\bbd\models\Business;
use lmy\humhub\modules\bbd\models\ConfigureForm;
use lmy\humhub\modules\bbd\Module;

use lmy\humhub\modules\bbd\permissions\AdminContents;
use lmy\humhub\modules\bbd\permissions\ManageContents;
use lmy\humhub\modules\bbd\permissions\ApplyBusiness;
use lmy\humhub\modules\bbd\permissions\ViewContents;

use humhub\modules\user\models\User;
use humhub\modules\space\models\Space;
use Yii;
use yii\web\HttpException;

/**
 *  
 * Use Cases of Permissions
 * admin and owner of the space  can manage content (create/edit/delete/ business and floor，approve business)  :3
 * moderator of the space  can create/edit all  business and floor :2
 * member of the space create and edit own business (status :  Pending, need to be approved by  admin or owner of the space):1
 * user of network ,guest can view  floor and business(public content) :0
 */


class BbdController extends ContentContainerController
{

     /** access level of the user currently logged in. 0 -> no write access / 1 -> create businesses and edit own businesses / 2 -> full write access. * */
     public $accessLevel = 0;

      /**
     * @inheritdoc
     */
    protected function getAccessRules()
    {
        // return [
          
        //    // [ContentContainerControllerAccess::RULE_USER_GROUP_ONLY => [Space::USERGROUP_GUEST]],

            
        // ];

        return [
            [ControllerAccess::RULE_POST => ['sort', 'delete-floor', 'approve-business']],
            [ControllerAccess::RULE_PERMISSION => [AdminContents::class], 'actions' => ['delete-floor','delete-business','approve-business']],
            [ControllerAccess::RULE_PERMISSION => [AdminContents::class, ManageContents::class], 'actions' => ['edit_floor','edit-business',]],
            [ControllerAccess::RULE_PERMISSION => [AdminContents::class, ManageContents::class, ApplyBusiness::class], 'actions' => ['edit-business']],
          
            [ControllerAccess::RULE_PERMISSION => [ViewContents::class], 'actions' => ['index']],
            [ControllerAccess::RULE_PERMISSION => [ManageSpaces::class], 'actions' => ['config']],
        ];
    }

    /**
     * Automatically loads the underlying contentContainer (User/Space) by using
     * the uguid/sguid request parameter
     */
    public function init()
    {
        parent::init();
        
        $this->accessLevel = $this->getAccessLevel();
    }
      /**
     * Get the acces level to the bbd of the currently logged in user.
     * @return number 0 -> no write access / 1 -> create businesses and edit own businesses / 2 -> full write access
     */
    private function getAccessLevel()
    {
        if ($this->contentContainer instanceof \humhub\modules\space\models\Space) {
         
            if($this->contentContainer->can(new AdminContents()))
            {return 3;}
            if($this->contentContainer->can(new  ManageContents()))
            {return 2;}
            if($this->contentContainer->can(new ApplyBusiness()))
            {return 1;}
            if($this->contentContainer->can(new ViewContents()))
            {return 0;}

            
        }
    }
    //public $subLayout = "@bbd/views/layouts/default";
    /**
     * By default a ContentContainerController will block requests without a given cguid request parameter. If you need to implement a controller which should be able to handle container related as well as global requests you'll have to set the ContentContainerController::requireContainer field to false.
     */


   // public $requireContainer = false;
    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
       //return $this->render('index');
       $floors = Floor::find()
       ->contentContainer($this->contentContainer)
       ->readable()
       ->orderBy(['sort_order' => SORT_ASC])
       ->all();

      $businesses = [];

      foreach ($floors as $floor) {
          $businesses[$floor->id] = Business::find()
              ->where(['floor_id' => $floor->id])
              ->readable()
              ->orderBy(['sort_order' => SORT_ASC])
              ->all();
      }
   
       return $this->render('index', [
        'contentContainer' => $this->contentContainer,
        'floors' => $floors,
        'businesses' => $businesses,
        'accessLevel' => $this->getAccessLevel(),
    ]);
       
    }

    /**
     * Action that renders the view to add or edit a floor.<br />
     * The request has to provide the id of the floor to edit in the url parameter 'floor_id'.
     * @see views/bbd/editFloor.php
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionEditFloor()
    {

        // if ($this->accessLevel == 0 || $this->accessLevel == 1) {
        //     throw new HttpException(404, Yii::t('BbdModule.base', 'You miss the rights to edit this floor!'));
        // }

        $floor_id = (int) Yii::$app->request->get('floor_id');
        $floor = Floor::find()
            ->where(['bbd_floor.id' => $floor_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        if ($floor == null) {
            $floor = new Floor();
            $floor->content->container = $this->contentContainer;
        }

        if ($floor->load(Yii::$app->request->post()) && $floor->validate() && $floor->save()) {
            $this->redirect($this->contentContainer->createUrl('/bbd/bbd/index'));
        }
        return $this->render('editFloor', ['floor' => $floor]);
    }

       /**
     * Action that deletes a given floor.<br />
     * The request has to provide the id of the floor to delete in the url parameter 'floor_id'.
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionDeleteFloor()
    {
        // if ($this->accessLevel == 0 || $this->accessLevel == 1) {
        //     throw new HttpException(404, Yii::t('BbdModule.base', 'You miss the rights to delete this floor!'));
        // }

        $floor_id = (int) Yii::$app->request->get('floor_id');
        $floor = Floor::find()
            ->where(['bbd_floor.id' => $floor_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        if ($floor == null) {
            throw new HttpException(404, Yii::t('BbdModule.base', 'Requested floor could not be found.'));
        }

        if ($floor->delete()) {
            $this->view->success(Yii::t('BbdModule.base', 'Deleted'));
        }

        $this->redirect($this->contentContainer->createUrl('/bbd/bbd/index'));
    }

    /**
     * Action that renders the view to add or edit a business.<br />
     * The request has to provide the id of the floor the business should be created in, in the url parameter 'floor_id'.<br />
     * If an existing business should be edited, the business's id has to be given in 'business_id'.<br />
     * @see views/bbd/editBusiness.php
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionEditBusiness()
    {
        $business_id = (int) Yii::$app->request->get('business_id');
        $floor_id = (int) Yii::$app->request->get('floor_id');

        $business = Business::find()
            ->where(['bbd_business.id' => $business_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        // access level 0 may neither create nor edit
        if ($this->accessLevel == 0) {
            throw new HttpException(404, Yii::t('BbdModule.base', 'You miss the rights to add/edit businesses!'));
        } elseif ($business == null) {
            // access level 1 + 2 may create
            $business = new Business();
            $floorExists = Floor::find()
                ->where(['bbd_floor.id' => $floor_id])
                ->contentContainer($this->contentContainer)
                ->readable()
                ->exists();
            if (!$floorExists) {
                throw new HttpException(404, Yii::t('BbdModule.base', 'The floor you want to create your business in could not be found!'));
            }
            $business->floor_id = $floor_id;
            $business->content->container = $this->contentContainer;
        } elseif ($this->accessLevel == 1 && $business->content->created_by != Yii::$app->user->id) {
            // access level 1 may edit own businesses, 2 all businesses
            throw new HttpException(404, Yii::t('BbdModule.base', 'You miss the rights to edit this business!'));
        }

        if ($business->load(Yii::$app->request->post()) && $business->validate() && $business->save()) {
            return $this->redirect($this->contentContainer->createUrl('/bbd/bbd/index'));
        }

        return $this->render('editBusiness', ['business' => $business]);
    }

     /**
     * Action that deletes a given business.<br />
     * The request has to provide the id of the business to delete in the url parameter 'business_id'.
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionDeleteBusiness()
    {
        $business_id = (int) Yii::$app->request->get('business_id');
        $business = Business::find()
            ->where(['bbd_business.id' => $business_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        if ($business == null) {
            throw new HttpException(404, Yii::t('BbdModule.base', 'Requested business could not be found.'));
        }
        // access level 1 may delete own businesses, 2 all businesses
        elseif ($this->accessLevel == 0 || $this->accessLevel == 1 && $business->content->created_by != Yii::$app->user->id) {
            throw new HttpException(404, Yii::t('BbdModule.base', 'You miss the rights to delete this business!'));
        }

        if ($business->delete()) {
            $this->view->success(Yii::t('BbdModule.base', 'Deleted'));
        }

        return $this->redirect($this->contentContainer->createUrl('/bbd/bbd/index'));
    }

    /**
     * Action that approve a given business.<br />
     * The request has to provide the id of the business to approve in the url parameter 'business_id'.
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionApproveBusiness()
    {
        $business_id = (int) Yii::$app->request->get('business_id');
        $business = Business::find()
            ->where(['bbd_business.id' => $business_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        if ($business == null) {
            throw new HttpException(404, Yii::t('BbdModule.base', 'Requested business could not be found.'));
        }
        // access level 1 may delete own businesses, 2 all businesses
        elseif ($this->accessLevel == 0 || $this->accessLevel == 1 && $business->content->created_by != Yii::$app->user->id) {
            throw new HttpException(404, Yii::t('BbdModule.base', 'You miss the rights to delete this business!'));
        }

        
        if ($business->approve()) {
            $this->view->success(Yii::t('BbdModule.base', 'Approved'));
        }

        return $this->redirect($this->contentContainer->createUrl('/bbd/bbd/index'));
    }


    /**
     * Space Configuration Action for Admins
     */
    public function actionConfig()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('bbd');

        $settings = $module->settings->contentContainer($this->contentContainer);

        $form = new ConfigureForm();
       // $form->enableDeadLinkValidation = $settings->get('enableDeadLinkValidation');
        $form->enableWidget = $settings->get('enableWidget');


        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $settings->set('enableDeadLinkValidation', $form->enableDeadLinkValidation);
            $settings->set('enableWidget', $form->enableWidget);
            $this->view->saved();

            return $this->redirect($this->contentContainer->createUrl('/linklist/linklist/config'));
        }

        return $this->render('config', ['model' => $form]);
    }



}

