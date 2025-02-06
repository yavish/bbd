<?php

/**
 * View for the wall entry widget. Displays the content shown on the wall if a business is added.
 *
 * @uses $business the business added to the wall.
 *
 * @author Sebastian Stumpf
 */

use yii\helpers\Html;
use lmy\humhub\modules\bbd\models\Business;

/* @var $business Business */
?>
<div class="media">
    <div class="media-body">
        <h4 class="media-heading"><?php echo Yii::t('BbdModule.base', 'Added a new business %business% to floor "%floor%".', [
                '%business%' => Html::a(Html::encode($business->title), $business->href, ['target' => '_blank']),
                '%floor%' => Html::encode($business->floor->title)
        ]); ?></h4>
        <?php
        if ($business->description == null || $business->description == '') {
            echo '<em>(' . Yii::t('BbdModule.base', 'No description available.') . ')</em>';
        } else {
            echo Html::encode($business->description);
        }
        ?>
    </div>
</div>