<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="page__cell">
    <div class="container container--narrow">
        <div class="box box--white">
            <figure class="logo"><img src="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_ADMIN_LOGO, 0, Afile::SIZE_SMALL, $siteLangId]); ?>" alt=""></figure>
            <div class="-align-center">
                <h3><?php echo Label::getLabel('LBL_Reset_Password'); ?> </h3>
                <!--<p><?php echo Label::getLabel('LBL_Enter_The_E-mail_Address_Associated_With_Your_Account') ?></p>-->
            </div>
            <div class="box__centered box__centered--form">
                <?php echo $frmResetPassword->getFormTag(); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-set">
                            <div class="field_cover field_cover--lock"><?php echo $frmResetPassword->getFieldHTML('new_pwd'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-set">
                            <div class="field_cover field_cover--lock">
                                <div class="captcha-wrap">
                                    <?php echo $frmResetPassword->getFieldHTML('confirm_pwd'); ?>
                                    <?php echo $frmResetPassword->getFieldHTML('apr_id'); ?> 
                                    <?php echo $frmResetPassword->getFieldHTML('token'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-set">
                            <?php echo $frmResetPassword->getFieldHTML('btn_reset'); ?>
                        </div>
                    </div>
                </div>
                <div class="row -align-center">
                    <div class="col-md-12">
                        <div class="field-set">
                            <a href="<?php echo MyUtility::makeUrl('adminGuest', 'loginForm'); ?>" class="-link-underline -txt-uppercase"><?php echo Label::getLabel('LBL_Back_to_Login'); ?></a>
                        </div>
                    </div>
                </div>
                <?php echo $frmResetPassword->getExternalJS(); ?>
                </form>
            </div>
        </div>
    </div>
</div>
