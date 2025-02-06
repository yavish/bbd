<?php

namespace lmy\humhub\modules\bbd;

use Yii;
use yii\helpers\Url;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\space\models\Space;
use humhub\modules\content\components\ContentContainerModule;

class Module extends ContentContainerModule
{
    /**
    * @inheritdoc
    */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
        ];
    }

    /**
    * @inheritdoc
    */
    public function getConfigUrl()
    {
        return Url::to(['/bbd/admin']);
    }

    

    /**
    * @inheritdoc
    */
    public function disable()
    {
        // Cleanup all module data, don't remove the parent::disable()!!!
        parent::disable();
    }

   /**
     * @inheritdoc
     */
    public function enableContentContainer(ContentContainerActiveRecord $container)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('bbd');

        parent::enableContentContainer($container);
    }

 
    /**
    * @inheritdoc
    */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        // Clean up space related data, don't remove the parent::disable()!!!
        parent::disableContentContainer($container);
    }

    /**
    * @inheritdoc
    */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('BbdModule.base', 'Bbd');
    }

    /**
    * @inheritdoc
    */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        return Yii::t('BbdModule.base', 'Building Business Directory');
    }


    public function getPermissions($contentContainer = null)
{
    if ($contentContainer instanceof Space) {
        return [
            new permissions\AdminContents(),
            new permissions\ManageContents(),
            new permissions\ApplyBusiness(),
            new permissions\ViewContents()
        ];
    } elseif ($contentContainer instanceof User) {
        // This module does not provide yn user level permission
        return [];
    }

    // return [
    //     new permissions\MyGroupPermission()
    // ];
}
}
