<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

?>
<style>
    .digit-group input{
		width: 30px;
		height: 50px;
        padding: 2px;
		border: 1px solid #000;
		line-height: 50px;
		text-align: center;
		font-size: 24px;
		/* font-family: "Raleway", sans-serif; */
		font-weight: 200;
		color: #000;
		margin: 0 2px;
	}


</style>
<section class="section section--gray section--page">
    <div class="container container--fixed">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="box -skin">
                    <div class="box__head -align-center">
                        <h4 class="-border-title"><?php echo Label::getLabel('LBL_LOGIN'); ?></h4>
                    </div>
                    <div class="box__body -padding-40 div-login-form">
                        <div class="prompt">
                            Enter the code generated on your mobile device below to log in!
                        </div>
                        <?php
                            $frm->setFormTagAttribute('class', 'form two-factor-form digit-group');
                            $frm->setFormTagAttribute('action', '');
                            $frm->setFormTagAttribute('onSubmit', 'setupTwoFactor(this); return(false);');
                            $fld1= $frm->getField('digit_1');
                            $fld2 = $frm->getField('digit_2');
                            $fld3 = $frm->getField('digit_3');
                            $fld4 = $frm->getField('digit_4');
                            $fld5 = $frm->getField('digit_5');
                            $fld6 = $frm->getField('digit_6');
                            
                            $fld1->setFieldTagAttribute('data-next', 'digit-2');
                            $fld2->setFieldTagAttribute('data-next', 'digit-3');
                            $fld3->setFieldTagAttribute('data-next', 'digit-4');
                            $fld4->setFieldTagAttribute('data-next', 'digit-5');
                            $fld5->setFieldTagAttribute('data-next', 'digit-6');

                            $fld2->setFieldTagAttribute('data-previous', 'digit-1');
                            $fld3->setFieldTagAttribute('data-previous', 'digit-2');
                            $fld4->setFieldTagAttribute('data-previous', 'digit-3');
                            $fld5->setFieldTagAttribute('data-previous', 'digit-4');
                            $fld6->setFieldTagAttribute('data-previous', 'digit-5');
                    
                            echo $frm->getFormTag(); 
                            echo $frm->getFieldHtml('digit_1'); 
                            echo $frm->getFieldHtml('digit_2'); 
                            echo $frm->getFieldHtml('digit_3'); 
                            echo $frm->getFieldHtml('digit_4'); 
                            echo $frm->getFieldHtml('digit_5'); 
                            echo $frm->getFieldHtml('digit_6'); ?>
                            <span class="-gap"></span><span class="-gap"></span>
                       
                        <div class="-align-center">
                            <div>
                                <?php echo $frm->getFieldHtml('btn_submit'); ?>
                            </div>
                            <div>
                                <?php echo $frm->getFieldHtml('resend_auth_code'); ?>
                            </div>
                        </div>
                        </form>
                        <?php 
                         echo $frm->getExternalJs();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>