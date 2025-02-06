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
 * Edit page Permission
 */
class ViewContents extends \humhub\libs\BasePermission
{
    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
        Space::USERGROUP_OWNER, //User is the owner of the space.
        Space::USERGROUP_ADMIN, //User is member of the space administrator group.
        Space::USERGROUP_MODERATOR, //User is member of the space moderator group.
        Space::USERGROUP_MEMBER, //User is a simple member of the space.
        Space::USERGROUP_USER, //User is not a member of the space but a member of the network.
        Space::USERGROUP_GUEST, //User is not a member of the space nor a member of the network.
    ];
    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        Space::USERGROUP_GUEST,

    ];
    /**
     * @inheritdoc
     */
    protected $title = "View Contents";

    /**
     * @inheritdoc
     */
    protected $description = "Allows the user to view the contents (floor and business of bbd) ";

    /**
     * @inheritdoc
     */
    protected $moduleId = 'bbd';

}
