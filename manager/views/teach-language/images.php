<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if (!empty($images)) {
    ?>
    <ul class="grids--onethird">
        <li>
            <div class="logoWrap">
                <div class="logothumb"> <img src="<?php echo MyUtility::makeUrl('image', 'show', [Afile::TYPE_TEACHING_LANGUAGES, $images['file_record_id'], Afile::SIZE_SMALL]) . '?' . time(); ?>" title="<?php echo $images['file_name']; ?>" alt="<?php echo $images['file_name']; ?>">
                    <?php if ($canEdit) { ?>
                        <a class="deleteLink white" href="javascript:void(0);" title="Delete <?php echo $images['file_name']; ?>" onclick="removeImage(<?php echo $tlang_id; ?>);" class="delete"><i class="ion-close-round"></i></a>
                        <?php } ?>
                </div>
                <small class=""><strong> <?php echo Label::getLabel('LBL_Language'); ?>:</strong> <?php echo Label::getLabel('LBL_All'); ?></small>
            </div>
        </li>
    </ul>
<?php } ?>