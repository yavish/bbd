<?php

use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\widgets\ContentObjectLinks;
use humhub\modules\like\widgets\LikeLink;
use lmy\humhub\modules\bbd\models\Floor;
use lmy\humhub\modules\bbd\models\Business;
use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;

// Register our module assets, this could also be done within the controller
\lmy\humhub\modules\bbd\assets\Assets::register($this);

?>

<div class="panel-heading"><strong><?= Yii::t('BbdModule.base', 'Building Business Directory') ?> </strong>
 <?= Yii::t('BbdModule.base', 'overview') ?></div>
 <?php

/**
 * View to list all floors and their businesses.
 *
 * @uses $floors an array of the floors to show.
 * @uses $businesses an array of arrays of the businesses to show, indicated by the floor id.
 * @uses $accesslevel the access level of the user currently logged in.
 *
 * @author Sebastian Stumpf
 */



/* @var ContentContainerActiveRecord $contentContainer */
/* @var Floor[] $floors */
/* @var Business[] $businesses */
/* @var int $accessLevel */


?>
<div class="panel panel-default">
    <div class="panel-body">
        <div id="bbd-empty-txt" <?php if (empty($floors)) {
            echo 'style="visibility:visible; display:block"';
        }
        ?> >
            <?= Yii::t('BbdModule.base', 'There have been no businesses or floors added to this space yet.') ?> <i
                    class="fa fa-frown-o"></i>
        </div>

        <div class="bbd-floors">
            <?php foreach ($floors as $floor) { ?>
                <div id="bbd-floor_<?= $floor->id ?>"
                     class="panel panel-default panel-bbd-floor" data-id="<?= $floor->id ?>">
                    <div class="panel-heading">
                        <div class="heading">
                            <?= Html::encode($floor->title); ?>
                            <?php if ($accessLevel != 0) { ?>
                                <div class="bbd-edit-controls bbd-editable">
                                    <?php
                                    if ($accessLevel == 3) {
                                        echo Html::a(
                                            Html::tag('i', '', ['class' => ['fa', 'fa-trash-o']]),
                                            Url::to($contentContainer->createUrl("/bbd/bbd/delete-floor", ['floor_id' => $floor->id])), [
                                                'class' => 'deleteButton btn btn-xs btn-danger',
                                                'title' => Yii::t('BbdModule.base', 'Delete floor'),
                                                'data' => [
                                                    'floor_id' => $floor->id,
                                                    'action-click' => 'bbd.removeFloor',
                                                    'action-confirm-header' => Yii::t('BbdModule.base', '<strong>Confirm</strong> floor deleting'),
                                                    'action-confirm' => Yii::t('BbdModule.base', 'Do you really want to delete this floor? All connected businesses will be lost!'),
                                                    'action-confirm-text' => Yii::t('BbdModule.base', 'Delete'),
                                                    'action-cancel-text' => Yii::t('BbdModule.base', 'Cancel'),
                                                ],
                                            ]
                                        );
                                        echo Button::primary()->icon('pencil')->xs()
                                            ->title(Yii::t('BbdModule.base', 'Edit Floor'))
                                            ->link($contentContainer->createUrl('/bbd/bbd/edit-floor', [
                                                'floor_id' => $floor->id
                                            ])) . ' ';
                                    }
                                    // all users may add a business to an existing floor
                                    echo Html::a('<i class="fa fa-plus" style="font-size: 12px;"></i> ' . Yii::t('BbdModule.base', 'Add business'), $contentContainer->createUrl('/bbd/bbd/edit-business', ['business_id' => -1, 'floor_id' => $floor->id]), ['title' => 'Add Business', 'class' => 'btn btn-xs btn-info']);
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <?php if (!($floor->description == NULL || $floor->description == "")) { ?>
                                <div class="media-heading"><?= Html::encode($floor->description); ?></div>
                            <?php } ?>
                            <div class="media-body">
                                <ul class="bbd-businesses">
                                    <?php foreach ($businesses[$floor->id] as $business) { ?>
                                        <li class="bbd-business" id="bbd-business_<?= $business->id; ?>"
                                            data-id="<?= $business->id; ?>">
                                            <?php
                                                if($business->content->was_published )
                                                {
                                                    echo Html::a(
                                                        '<span class="title">' . Html::encode($business->room_number) . ' | '. Html::encode($business->title) .'</span>',
                                                        $business->href,
                                                        ['target' => '_blank']
                                                    );
                                                }else
                                                {   //<i class="icon">pending</i>';
                                                    echo Html::a(
                                                        '<span >' . Html::encode($business->room_number) . ' | '. Html::encode($business->title) .'</span>',
                                                        $business->href,
                                                        ['target' => '_blank']
                                                    );
                                                    echo '<i class="fa fa-clock-o tt" title=""   style="color: var(--warning);" data-original-title="not published"></i>';

                                                }
                                           ?>
                                            <div class="bbd-interaction-controls">
                                                <?= ContentObjectLinks::widget([
                                                    'object' => $business,
                                                    'widgetParams' => [CommentLink::class => ['mode' => CommentLink::MODE_POPUP]],
                                                    'widgetOptions' => [
                                                        CommentLink::class => ['sortOrder' => 100],
                                                        LikeLink::class => ['sortOrder' => 200],
                                                    ],
                                                    'seperator' => '&middot;',
                                                ]); ?>
                                            </div>
                                            <?php // all admins and users that created the business may edit or delete it  ?>
                                            <?php if ($accessLevel == 3 || $accessLevel == 2 || ($accessLevel == 1 && $business->content->created_by == Yii::$app->user->id)) { ?>
                                                <div class="bbd-edit-controls bbd-editable">
                                                    <?php
                                                     if ($accessLevel == 3) {
                                                       echo  Html::a(
                                                        Html::tag('i', '', ['class' => ['fa', 'fa-trash-o']]),
                                                        Url::to($contentContainer->createUrl("/bbd/bbd/delete-business", array('floor_id' => $floor->id, 'business_id' => $business->id))), [
                                                            'class' => 'deleteButton btn btn-xs btn-danger',
                                                            'title' => Yii::t('BbdModule.base', 'Delete business'),
                                                            'data' => [
                                                                'business_id' => $business->id,
                                                                'floor_id' => $floor->id,
                                                                'action-click' => 'bbd.removeBusiness',
                                                                'action-confirm-header' => Yii::t('BbdModule.base', '<strong>Confirm</strong> business deleting'),
                                                                'action-confirm' => Yii::t('BbdModule.base', 'Do you really want to delete this business?'),
                                                                'action-confirm-text' => Yii::t('BbdModule.base', 'Delete'),
                                                                'action-cancel-text' => Yii::t('BbdModule.base', 'Cancel'),
                                                            ],
                                                        ]
                                                    );
                                                    echo " ";
                                                    echo  Html::a(
                                                        Html::tag('i', '', ['class' => ['fa', 'fa-check']]),
                                                        Url::to($contentContainer->createUrl("/bbd/bbd/approve-business", array('floor_id' => $floor->id, 'business_id' => $business->id))), [
                                                            'class' => 'approveButton btn btn-xs btn-warning',
                                                            'title' => Yii::t('BbdModule.base', 'Approve business'),
                                                            'data' => [
                                                                'business_id' => $business->id,
                                                                'floor_id' => $floor->id,
                                                                'action-click' => 'bbd.approveBusiness',
                                                                'action-confirm-header' => Yii::t('BbdModule.base', '<strong>Confirm</strong> business approving'),
                                                                'action-confirm' => Yii::t('BbdModule.base', 'Do you really want to approve this business?'),
                                                                'action-confirm-text' => Yii::t('BbdModule.base', 'Approve'),
                                                                'action-cancel-text' => Yii::t('BbdModule.base', 'Cancel'),
                                                            ],
                                                        ]
                                                    ); 
                                                     }?>
                                                    <?= Button::primary()->icon('pencil')->xs()
                                                        ->title(Yii::t('BbdModule.base', 'Edit Business'))
                                                        ->link($contentContainer->createUrl('/bbd/bbd/edit-business', [
                                                            'business_id' => $business->id,
                                                            'floor_id' => $floor->id
                                                    ])); ?>
                                                </div>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if ($accessLevel == 3) { ?>
                <div class="bbd-add-floor bbd-editable">
                    <?= Html::a(Yii::t('BbdModule.base', 'Add Floor'), $contentContainer->createUrl('/bbd/bbd/edit-floor', ['floor_id' => -1]), ['class' => 'btn btn-primary']); ?>
                </div>
            <?php } ?>
        
    </div>
</div>


