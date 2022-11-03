<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$frm->addFormTagAttribute('class', 'form height-100');
$btnSubmit = $frm->getField('btn_submit');
$btnSubmit->setFieldTagAttribute('class', 'btn btn--primary');
$btnSkip = $frm->getField('btn_skip');
if (count($attemptedQues) == $question['qulinqu_order']) {
    $frm->addFormTagAttribute('onsubmit', 'save(this); return false;');
    $btnSubmit->value = Label::getLabel('LBL_SAVE');
} else {
    $frm->addFormTagAttribute('onsubmit', 'saveAndNext(this); return false;');
    $btnSkip->setFieldTagAttribute('onclick', 'skipAndNext(' . $data['quizat_id'] . ');');
    $btnSkip->setFieldTagAttribute('class', 'btn btn--transparent border-0 color-black style-italic');
}
$fld = $frm->getField('ques_answer');
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
        </div>
        <div class="box-view__body">
            <div class="option-list">
                <?php
                if ($question['qulinqu_type'] != Question::TYPE_MANUAL && count($options) > 0) {
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
                <?php } else { ?>
                    <?php echo $fld->getHtml(); ?>
                <?php } ?>
            </div>
            <?php if (!empty($question['qulinqu_hint'])) { ?>
                <div class="option-hint">
                    <span class="d-inline-flex align-items-center">
                        <span class="option-hint__title d-inline-flex align-items-center margin-right-1">
                            <strong class="d-inline-flex align-items-center">
                                <svg class="icon icon--hint icon--small margin-right-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 383.34">
                                    <g>
                                        <g>
                                            <path d="M195,0c6.82,2.93,9.42,8.08,9.09,15.45-.4,8.96-.06,17.95-.11,26.93-.04,7.48-5.33,13-12.18,12.86-6.77-.14-11.76-5.53-11.79-12.87-.05-8.98,.28-17.97-.1-26.93-.31-7.35,2.23-12.53,9.09-15.43h6Z"></path>
                                            <path d="M384,194.62c-2.95,6.79-8.1,9.4-15.48,9.08-8.98-.39-17.99-.06-26.99-.11-7.35-.04-12.75-5.04-12.88-11.79-.13-6.9,5.33-12.1,12.88-12.14,9-.05,18.01,.3,26.99-.11,7.39-.33,12.52,2.31,15.48,9.08v5.99Z"></path>
                                            <path d="M0,188.63c2.91-6.84,8.09-9.39,15.46-9.07,8.98,.39,17.99,.06,26.99,.1,7.5,.04,13.03,5.29,12.9,12.14-.13,6.77-5.52,11.75-12.88,11.79-9,.05-18.01-.29-26.98,.11-7.39,.33-12.54-2.28-15.48-9.08v-5.99Z"></path>
                                            <path d="M240.65,311.23h-97.76c-2.12-10.18-6.29-19.23-13.84-26.45-2.25-2.15-4.76-4.03-7.14-6.05-34.93-29.64-48.8-67.34-38.96-111.76,9.58-43.29,37.44-71.59,80.35-83.29,67.12-18.29,134.34,28.88,140.15,98.12,3.24,38.65-10.68,70.89-40.35,96.04-9.42,7.98-17.53,16.67-21.12,28.78-.46,1.54-.89,3.08-1.32,4.61Zm-42.48-191.43c-8.23-.16-13.92,4.48-14.2,11.59-.27,6.99,5.32,12.12,13.49,12.36,23.03,.68,41.84,19.51,42.51,42.56,.23,7.94,5.23,13.43,12.14,13.33,7.12-.1,11.99-5.83,11.85-13.95-.59-35.18-30.55-65.19-65.79-65.89Z"></path>
                                            <path d="M239.36,335.61c0,8.61,.92,17.06-.21,25.23-1.76,12.73-13.43,22.15-26.33,22.34-13.87,.21-27.74,.21-41.61,0-13.42-.2-24.46-10.33-26.38-24.59-1-7.48-.8-15.13-1.16-22.99h95.69Z"></path>
                                            <path d="M66.34,52.47c1.57,.86,4.79,1.88,7.01,3.97,7.47,7,14.63,14.32,21.8,21.63,4.98,5.08,4.96,12.36,.17,17.1-4.73,4.67-11.9,4.8-16.85,0-7.52-7.31-14.93-14.73-22.27-22.23-3.67-3.75-4.47-8.3-2.41-13.12,2.02-4.72,5.89-6.9,12.54-7.34Z"></path>
                                            <path d="M331.42,66.14c-.84,1.56-1.85,4.79-3.93,7.01-7.01,7.45-14.34,14.61-21.67,21.76-5.15,5.03-12.31,5.04-17.12,.26-4.79-4.76-4.81-12.02,.17-17.09,7.26-7.39,14.6-14.71,22.01-21.96,3.76-3.68,8.3-4.5,13.14-2.46,4.73,1.99,6.95,5.84,7.4,12.49Z"></path>
                                            <path d="M331.37,317.05c-.28,6.27-2.33,10.08-6.78,12.27-4.59,2.25-9.25,1.92-12.92-1.56-8.06-7.65-15.94-15.49-23.6-23.53-4.4-4.62-3.82-11.83,.69-16.24,4.51-4.4,11.65-4.77,16.29-.34,7.85,7.5,15.55,15.18,23.03,23.05,1.85,1.94,2.59,4.93,3.29,6.34Z"></path>
                                            <path d="M99.59,297.1c-1.44,2.67-2.4,5.79-4.42,7.92-7.11,7.53-14.51,14.8-21.94,22.02-5.08,4.94-12.36,4.78-17.11-.09-4.58-4.7-4.57-11.91,.25-16.82,7.26-7.4,14.6-14.71,22-21.96,3.85-3.78,8.83-4.58,13.47-2.47,4.33,1.97,7.13,6.35,7.75,11.4Z"></path>
                                        </g>
                                    </g>
                                </svg>
                                <?php echo Label::getLabel('LBL_HINT:'); ?>
                            </strong>
                        </span>
                        <span class="option-hint__content"><?php echo CommonHelper::renderHtml($question['qulinqu_hint']) ?></span>
                    </span>
                </div>
            <?php } ?>
        </div>
        <div class="box-view__footer">
            <div class="box-actions form">
                <?php if ($question['qulinqu_order'] > 1) { ?>
                    <div class="box-actions__cell box-actions__cell-left">
                        <input type="button" name="back" value="<?php echo Label::getLabel('LBL_BACK') ?>" onclick="previous('<?php echo $data['quizat_id'] ?>')" class="btn btn--bordered-primary">
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
                    echo $frm->getFieldHtml('quatqu_id');
                    echo $btnSubmit->getHtml();
                    ?>
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