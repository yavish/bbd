<?php

namespace lmy\humhub\modules\bbd\widgets;

use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;
use lmy\humhub\modules\bbd\models\Business;

/**
 * WallEntryWidget displaying a business content on the wall.
 *
 * @package humhub.modules.bbd.widgets
 * @author Sebastian Stumpf
 */
class WallEntry extends WallStreamModuleEntryWidget
{
    /**
     * @var Link
     */
    public $model;

    public function renderContent()
    {
        return $this->render('wallEntry', [
            'business' => $this->model,
        ]);
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return 'book';
    }

    /**
     * @return string a non encoded plain text title (no html allowed) used in the header of the widget
     */
    protected function getTitle()
    {
        return $this->model->title;
    }

}
