<nav class="tabs tabs--line padding-left-8 padding-right-8 tabs-step">
    <ul>
        <li class="generalTabJs is-error <?php echo ($active == 1) ? 'is-active' : ''; ?>" <?php echo ($quizId > 0 && $active != 1) ? 'onclick="form(\'' . $quizId . '\');"' : ''; ?>>
            <a href="javascript:void(0)"><?php echo Label::getLabel('LBL_General'); ?>
                <span class="step-sign"></span>
            </a>
        </li>
        <li class="questionsTabJs is-error <?php echo ($active == 2) ? 'is-active' : '';  ?>">
            <a href="javascript:void(0)" <?php echo ($quizId > 0 && $active != 2) ? 'onclick="questions(\'' . $quizId . '\');"' : ''; ?>>
                <?php echo Label::getLabel('LBL_QUESTIONS'); ?><span class="step-sign"></span>
            </a>
        </li>
        <li class="settingsTabJs is-error <?php echo ($active == 3) ? 'is-active' : ''; ?>">
            <a href="javascript:void(0)" <?php echo ($quizId > 0 && $active != 3) ? 'onclick="settings(\'' . $quizId . '\');"' : ''; ?>>
                <?php echo Label::getLabel('LBL_SETTINGS'); ?><span class="step-sign"></span>
            </a>
        </li>
    </ul>
</nav>