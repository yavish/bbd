<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2019 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace lmy\humhub\modules\bbd\controllers\rest;

use lmy\humhub\modules\bbd\models\Floor;
use humhub\modules\bbd\permissions\ManageContents;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\ContentContainer;
use humhub\modules\rest\components\BaseContentController;
use Yii;

class FloorController extends BaseContentController
{
    public static $moduleId = 'bbd';

    /**
     * {@inheritdoc}
     */
    public function getContentActiveRecordClass()
    {
        return Floor::class;
    }

    /**
     * {@inheritdoc}
     */
    public function returnContentDefinition(ContentActiveRecord $contentRecord)
    {
        /** @var Floor $contentRecord */
        return RestDefinitions::getFloor($contentRecord);
    }

    
}
