<?php
/**
 * Sidebar widget view to list all floors and their businesses.
 *
 * @uses $floors an array of the floors to show.
 * @uses $businesses an array of arrays of the businesses to show, indicated by the floor id.
 *
 * @author Sebastian Stumpf
 */

use humhub\libs\Html;
 
lmy\modules\bbd\assets\Assets::register($this);
?>
<div class="panel panel-default panel-bbd-widget">
    <div class="panel-heading">
        <strong><?php echo Yii::t('BbdModule.base', 'Link'); ?></strong> <?php echo Yii::t('BbdModule.base', 'list'); ?>
    </div>
    <div class="bbd-body">
        <div class="scrollable-content-container">
            <?php foreach ($floors as $floor) { ?>
                <div id="bbd-widget-category_<?php echo $floor->id; ?>" class="media">
                    <div class="media-heading"><?= Html::encode($floor->title); ?></div>
                    <ul class="media-list">
                        <?php foreach ($businesses[$floor->id] as $business): ?>
                            <li id="bbd-widget-business_<?= $business->id; ?>">
                                <?= Html::a(Html::encode($business->title), $business->href, ['target' => '_blank', 'title' => Html::encode($business->description)]); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
