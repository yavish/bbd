<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace lmy\humhub\modules\bbd\helpers;
    
use humhub\modules\rest\definitions\ContentDefinitions;
use humhub\modules\rest\definitions\UserDefinitions;
use lmy\humhub\modules\bbd\models\Floor;
use lmy\humhub\modules\bbd\models\Business;
 

/**
 * Class RestDefinitions
 *
 * @package humhub\modules\rest\definitions
 */
class RestDefinitions
{
    public static function getFloor(Floor $floor)
    {
        return [
            'id' => $floor->id,
            'title' => $floor->title,
            'description' => $floor->description,
            'sort_order' => $floor->sort_order, 
            'content' => ContentDefinitions::getContent($floor->content),
        ];
    }
    public static function getBusiness(Business $business)
    {
        return [
            'id' => $business->id,
            'room_number' => $business->room_number,
            'title' => $business->title,
            'description' => $business->description,
            'href' => $business->href,
            'sort_order' => $business->sort_order, 
            'floor_id' => self::getFloor($business->floor),        
            'content' => ContentDefinitions::getContent($business->content),
             
        ];
    }

  

}
