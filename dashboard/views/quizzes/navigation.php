<nav class="tabs tabs--line padding-left-8 padding-right-8">
    <ul>
        <li class="<?php echo ($active == 1) ? 'is-active' : ''; ?>" <?php echo ($quizId > 0 && $active != 1) ? 'onclick="form(\'' . $quizId . '\');"' : ''; ?>>
            <a href="javascript:void(0)"><?php echo Label::getLabel('LBL_General'); ?></a>
        </li>
        <li class="<?php echo ($active == 2) ? 'is-active' : ''; ?>">
            <a href="javascript:void(0)" <?php echo ($quizId > 0 && $active != 2) ? 'onclick="questions(\'' . $quizId . '\');"' : ''; ?>>
                <?php echo Label::getLabel('LBL_QUESTIONS'); ?>
            </a>
        </li>
        <li class="<?php echo ($active == 3) ? 'is-active' : ''; ?>">
            <a href="javascript:void(0)" <?php echo ($quizId > 0 && $active != 3) ? 'onclick="settings(\''. $quizId .'\');"' : ''; ?>>
                <?php echo Label::getLabel('LBL_SETTINGS'); ?>
            </a>
        </li>
    </ul>
</nav>