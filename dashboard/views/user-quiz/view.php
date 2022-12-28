<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$frm->addFormTagAttribute('class', 'form height-100');
$btnSubmit = $frm->getField('btn_submit');
$btnSubmit->setFieldTagAttribute('class', 'btn btn--primary btnNextJs');
$btnSkip = $frm->getField('btn_skip');
if (count($attemptedQues) == $question['qulinqu_order']) {
    $frm->addFormTagAttribute('onsubmit', 'save(this); return false;');
    $btnSubmit->value = Label::getLabel('LBL_SAVE');
} else {
    $frm->addFormTagAttribute('onsubmit', 'saveAndNext(this); return false;');
    $btnSkip->setFieldTagAttribute('onclick', 'skipAndNext(' . $data['quizat_id'] . ');');
    $btnSkip->setFieldTagAttribute('class', 'btn btn--transparent border-0 color-black style-italic ');
}
$fld = $frm->getField('ques_answer');
$aqIdFld = $frm->getField('quatqu_id');
?>
<div class="flex-layout__large">
    <?php echo $frm->getFormTag(); ?>
    <div class="box-view box-view--space box-flex">
        <div class="box-view__head margin-bottom-8">
            <small class="style-italic"><?php echo Label::getLabel('LBL_MARKS:') . ' ' . $question['qulinqu_marks']; ?></small>
            <h4 class="margin-bottom-2">
                <?php echo str_replace('{number}', $question['qulinqu_order'], Label::getLabel('LBL_Q{number}.')) . ' ' . $question['qulinqu_title']; ?>
            </h4>
            <p><?php echo CommonHelper::renderHtml($question['qulinqu_detail']) ?></p>
            <?php if (isset($quesFile)) { ?>
                <div class="source">
                    <audio src="<?php echo MyUtility::makeUrl('Image', 'showVideo', [Afile::TYPE_QUESTION_AUDIO, $question['qulinqu_ques_id']], CONF_WEBROOT_FRONTEND) . '?time=' . time() ?>" controls playsinline noplaybackrate controlsList="nodownload" volume=1 autostart=0></audio>
                </div>
            <?php } ?>
        </div>
        <div class="box-view__body">
            <?php if (in_array($question['qulinqu_type'], [Question::TYPE_SINGLE, Question::TYPE_MULTIPLE]) && count($options) > 0) { ?>
                <div class="option-list">
                    <?php
                    $type = ($question['qulinqu_type'] == Question::TYPE_SINGLE) ? 'radio' : 'checkbox';
                    foreach ($options as $option) {
                    ?>
                        <label class="option">
                            <input type="<?php echo $type; ?>" name="ques_answer[]" class="option__input" value="<?php echo $option['queopt_id']; ?>" <?php echo (in_array($option['queopt_id'], $fld->value)) ? 'checked="checked"' : ''; ?>>
                            <span class="option__item">
                                <span class="option__icon">
                                    <?php if ($question['qulinqu_type'] == Question::TYPE_MULTIPLE) { ?>
                                        <svg class="icon-correct" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M4 3h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm7.003 13l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z"></path>
                                        </svg>
                                        <svg class="icon-incorrect" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M-2196-558h-16a1,1,0,0,1-1-1v-16a1,1,0,0,1,1-1h16a1,1,0,0,1,1,1v16A1,1,0,0,1-2196-558Zm-8.125-8.042h0l3.791,3.791,1.083-1.084-3.792-3.792,3.792-3.792-1.083-1.084-3.792,3.793-3.792-3.793-1.083,1.084,3.792,3.792-3.792,3.792,1.083,1.084,3.791-3.791Z" transform="translate(2216 579)"></path>
                                        </svg>
                                    <?php } else { ?>
                                        <svg class="icon-correct" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" />
                                        </svg>
                                        <svg class="icon-incorrect" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-11.414L9.172 7.757 7.757 9.172 10.586 12l-2.829 2.828 1.415 1.415L12 13.414l2.828 2.829 1.415-1.415L13.414 12l2.829-2.828-1.415-1.415L12 10.586z" />
                                        </svg>
                                    <?php } ?>
                                </span>
                                <span class="option__value"><?php echo $option['queopt_title'] ?></span>
                            </span>
                        </label>
                    <?php } ?>
                </div>
            <?php } elseif ($question['qulinqu_type'] == Question::TYPE_TEXT) { ?>
                <div class="option-list">
                    <?php echo $fld->getHtml(); ?>
                </div>
            <?php } elseif ($question['qulinqu_type'] == Question::TYPE_AUDIO) { ?>
                <?php
                $class = $src = '';
                if (isset($file)) {
                    $class = 'hasFile';
                    $src = 'src="' . $file . '"';
                }
                ?>
                <div class="source-view margin-top-5">
                    <h5><?php echo Label::getLabel('LBL_RECORD_YOUR_RESPONSE'); ?></h5>
                    <div class="source recordrtc <?php echo $class; ?>">
                        <div class="source__field audioRecorderJs">
                            <audio <?php echo $src; ?> controls playsinline noplaybackrate volume=1 controlsList="nodownload" id="audio1" autostart=0></audio>
                        </div>
                        <div class="source__field audioRecordingJs" style="display:none;"></div>
                        <div class="source__actions">
                            <a href="javascript:void(0)" class="btn btn--equal btn--transparent color-black is-hover btnRecordJs" data-status="<?php echo Label::getLabel('LBL_START_RECORDING'); ?>">
                                <svg class="icon icon--recording btnStartJs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 1a5 5 0 0 1 5 5v4a5 5 0 0 1-10 0V6a5 5 0 0 1 5-5zM3.055 11H5.07a7.002 7.002 0 0 0 13.858 0h2.016A9.004 9.004 0 0 1 13 18.945V23h-2v-4.055A9.004 9.004 0 0 1 3.055 11z" />
                                </svg>
                                <div class="tooltip tooltip--top bg-black labelStartJs"><?php echo Label::getLabel('LBL_START_RECORDING'); ?></div>
                                <svg class="icon icon--stop btnStopJs" style="display:none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M16.425 17.839A8.941 8.941 0 0 1 13 18.945V23h-2v-4.055A9.004 9.004 0 0 1 3.055 11H5.07a7.002 7.002 0 0 0 9.87 5.354l-1.551-1.55A5 5 0 0 1 7 10V8.414L1.393 2.808l1.415-1.415 19.799 19.8-1.415 1.414-4.767-4.768zm2.95-2.679l-1.443-1.442c.509-.81.856-1.73.997-2.718h2.016a8.95 8.95 0 0 1-1.57 4.16zm-2.91-2.909l-8.78-8.78A5 5 0 0 1 17 6l.001 4a4.98 4.98 0 0 1-.534 2.251z" />
                                </svg>
                                <div class="tooltip tooltip--top bg-black labelStopJs" style="display:none"><?php echo Label::getLabel('LBL_STOP_RECORDING'); ?></div>
                            </a>
                            <a href="javascript:void(0)" style="display:<?php echo !empty($class) ? 'inline-flex' : 'none'; ?>" onclick="removeRecording('<?php echo $data['quizat_id'] ?>', '<?php echo $aqIdFld->value; ?>');" class="btn btn--equal btn--transparent color-black is-hover btnRemoveJs">
                                <svg class="icon icon--sorting" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-11.414L9.172 7.757 7.757 9.172 10.586 12l-2.829 2.828 1.415 1.415L12 13.414l2.828 2.829 1.415-1.415L13.414 12l2.829-2.828-1.415-1.415L12 10.586z" />
                                </svg>
                                <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_REMOVE_RECORDING'); ?></div>
                            </a>
                            <?php echo $frm->getFieldHtml('audio_filename'); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if (!empty($question['qulinqu_hint'])) { ?>
            <div class="option-hint">
                <span class="d-inline-flex align-items-center">
                    <span class="option-hint__title d-inline-flex align-items-center margin-right-1">
                        <strong class="d-inline-flex align-items-center">
                            <svg class="icon icon--dashboard margin-right-2 icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#hint'; ?>"></use>
                            </svg>
                            <?php echo Label::getLabel('LBL_HINT:'); ?>
                        </strong>
                    </span>
                    <span class="option-hint__content"><?php echo CommonHelper::renderHtml($question['qulinqu_hint']) ?></span>
                </span>
            </div>
        <?php } ?>
        <div class="box-view__footer">
            <div class="box-actions form">
                <?php if ($question['qulinqu_order'] > 1) { ?>
                    <div class="box-actions__cell box-actions__cell-left">
                        <input type="button" name="back" value="<?php echo Label::getLabel('LBL_BACK') ?>" onclick="previous('<?php echo $data['quizat_id'] ?>')" class="btn btn--bordered-primary btnPrevJs">
                    </div>
                <?php } ?>
                <div class="box-actions__cell box-actions__cell-right">
                    <?php
                    if ($question['qulinqu_order'] < count($attemptedQues)) {
                        echo $btnSkip->getHtml();
                    }

                    echo $frm->getFieldHtml('ques_type');
                    echo $frm->getFieldHtml('ques_id');
                    echo $frm->getFieldHtml('ques_attempt_id');
                    echo $aqIdFld->getHtml();
                    echo $btnSubmit->getHtml();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<?php echo $frm->getExternalJs(); ?>
</div>
<div class="flex-layout__small">
    <div class="box-view box-view--space box-flex">
        <div class="box-view__head margin-bottom-5">
            <h4><?php echo Label::getLabel('LBL_ATTEMPT_SUMMARY'); ?></h4>
        </div>
        <div class="box-view__body">
            <nav class="attempt-list">
                <ul>
                    <?php foreach ($attemptedQues as $quest) {
                        $class = "";
                        $action = "onclick=\"getByQuesId('" . $data['quizat_id'] . "', '" . $quest['qulinqu_id'] . "')\";";
                        if (!empty($quest['quatqu_id'])) {
                            $class = "is-visited";
                        }
                        if ($data['quizat_qulinqu_id'] == $quest['qulinqu_id']) {
                            $class .= " is-current";
                            $action = "";
                        }
                    ?>
                        <li class="<?php echo $class; ?>">
                            <a href="javascript:void(0);" class="attempt-action" <?php echo $action; ?>>
                                <?php echo $quest['qulinqu_order'] ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
        <div class="box-view__footer">
            <div class="legends margin-bottom-10">
                <h6><?php echo Label::getLabel('LBL_LEGEND'); ?></h6>
                <div class="legend-list">
                    <ul>
                        <li class="is-answered"><span class="legend-list__item">
                                <?php echo Label::getLabel('LBL_ANSWERED'); ?>
                            </span></li>
                        <li class="is-skip"><span class="legend-list__item">
                                <?php echo Label::getLabel('LBL_NOT_ANSWERED'); ?>
                            </span></li>
                    </ul>
                </div>
            </div>
            <div class="box-actions form">
                <input type="button" value="<?php echo Label::getLabel('LBL_SUBMIT_&_FINISH') ?>" class="btn btn--bordered-primary btn--block" onclick="saveAndFinish();">
            </div>
        </div>
    </div>
</div>
<?php if ($question['qulinqu_type'] == Question::TYPE_AUDIO) { ?>
    <script>
        getPlayer();
        $(document).ready(function() {
            setTimeout(function() {
                $('#audio1').attr('autoplay', '0');
            }, 3000);

        });
    </script>
<?php } ?>