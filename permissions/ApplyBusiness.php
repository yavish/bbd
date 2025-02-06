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
 * Edit Business Permission
 */
class ApplyBusiness extends \humhub\libs\BasePermission
{
    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [

        Space::USERGROUP_OWNER, //User is the owner of the space.
        Space::USERGROUP_ADMIN, //User is member of the space administrator group.
        Space::USERGROUP_MODERATOR, //User is member of the space moderator group.
        Space::USERGROUP_MEMBER, //User is a simple member of the space.
     
        
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
    protected $title = "Apply businesses";

    /**
     * @inheritdoc
     */
    protected $description = "Allows the user to apply business (create and  edit businesses)";

    /**
     * @inheritdoc
     */
    protected $moduleId = 'bbd';

}
