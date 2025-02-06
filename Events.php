<?php

namespace  lmy\humhub\modules\bbd;

use Yii;
use yii\helpers\Url;

class Events
{
   
    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label' => 'Bbd',
            'url' => Url::to(['/bbd/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-book"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'bbd' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 99999,
        ]);
    }

 

        /**
     * On build of a Space Navigation, check if this module is enabled.
     * When enabled add a menu item
     *
     * @param type $event
     */
    public static function onSpaceMenuInit($event)
    {

        $space = $event->sender->space;
        if ($space->moduleManager->isEnabled('bbd') ) {
            $event->sender->addItem([
                'label' => Yii::t('BbdModule.base', 'Bbd'),
                'group' => 'modules',
                'url' => $space->createUrl('/bbd/bbd'),
                'icon' => '<i class="fa fa-map-signs"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'bbd'),
            ]);
        }
    }

    public static function onRestApiAddRules()
    {
        /* @var humhub\modules\rest\Module $restModule */
        $restModule = Yii::$app->getModule('rest');
        $restModule->addRules([
            ['pattern' => 'bbd/business/<id:\d+>', 'route' => 'bbd/rest/business/view', 'verb' => ['GET', 'HEAD']],
            ['pattern' => 'bbd/floor/container/<containerId:\d+>', 'route' => 'bbd/rest/floor/find-by-container', 'verb' => 'GET'],
        ]);
    }

}
