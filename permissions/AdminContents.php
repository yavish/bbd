<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace lmy\humhub\modules\bbd\permissions;

use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;

/**
 * Page Administration Permission
 */
class AdminContents extends \humhub\libs\BasePermission
{
    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
        Space::USERGROUP_OWNER, //User is the owner of the space.
        Space::USERGROUP_ADMIN, //User is member of the space administrator group.
        User::USERGROUP_SELF,
    ];

    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        Space::USERGROUP_USER,
        Space::USERGROUP_GUEST,
    ];

    /**
     * @inheritdoc
     */
    protected $title = "Admin Contents";

    /**
     * @inheritdoc
     */
    protected $description = "Allows the user to admin  Contents (create/edit/delete/ business and floor，approve business)";

    /**
     * @inheritdoc
     */
    protected $moduleId = 'bbd';

}
