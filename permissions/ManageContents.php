<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace lmy\humhub\modules\bbd\permissions;

use humhub\modules\space\models\Space;


/**
 * CreateBusiness Permission
 */
class ManageContents extends \humhub\libs\BasePermission
{
    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
 
        Space::USERGROUP_OWNER, //User is the owner of the space.
        Space::USERGROUP_ADMIN, //User is member of the space administrator group.
        Space::USERGROUP_MODERATOR, //User is member of the space moderator group.
        
       
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
    protected $title = "Manage contents";

    /**
     * @inheritdoc
     */
    protected $description = "Allows the user to manage contents (create/edit all  business and floors)";

    /**
     * @inheritdoc
     */
    protected $moduleId = 'bbd';

}
