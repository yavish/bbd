<?php

namespace lmy\humhub\modules\bbd\widgets;

use lmy\humhub\modules\bbd\models\Floor;
use lmy\humhub\modules\bbd\models\Business;
use lmy\humhub\modules\bbd\Module;
use Yii;

/**
 * BbdSidebarWidget displaying a list of businesses.
 *
 * It is attached to the sidebar of the space/user, if the module is enabled in the settings.
 *
 * @package humhub.modules.bbd.widgets
 * @author Sebastian Stumpf
 */
class Sidebar extends \humhub\components\Widget
{
    public $contentContainer;

    public function run()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('bbd');

        $container = $this->contentContainer;
        if (!(bool)$module->settings->contentContainer($container)->get('enableWidget')) {
            return;
        }
        $floorBuffer = Floor::find()
            ->contentContainer($this->contentContainer)
            ->readable()
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();
        $floors = [];
        $businesses = [];
        $render = false;

        foreach ($floorBuffer as $floor) {
            $businessBuffer = Business::find()
                ->where(['floor_id' => $floor->id])
                ->readable()
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();
            // categories are only displayed in the widget if they contain at least one link
            if (!empty($businessBuffer)) {
                $floors[] = $floor;
                $businesses[$floor->id] = $businessBuffer;
                $render = true;
            }
        }

        // if none of the floors contains a business, the bbd widget is not rendered.
        if ($render) {
            return $this->render('bbdPanel', ['container' => $container, 'floors' => $floors, 'businesses' => $businesses]);
        }
    }

}
