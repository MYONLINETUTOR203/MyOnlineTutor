<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form two-factor-form digit-group');
$frm->setFormTagAttribute('action', '');
$frm->setFormTagAttribute('onSubmit', 'setupTwoFactor(this); return(false);');
$fld1= $frm->getField('digit_1');
$fld2 = $frm->getField('digit_2');
$fld3 = $frm->getField('digit_3');
$fld4 = $frm->getField('digit_4');
$fld5 = $frm->getField('digit_5');
$fld6 = $frm->getField('digit_6');
$userFld = $frm->getField('user_id');

$fld1->setFieldTagAttribute('id', 'digit-1');
$fld2->setFieldTagAttribute('id', 'digit-2');
$fld3->setFieldTagAttribute('id', 'digit-3');
$fld4->setFieldTagAttribute('id', 'digit-4');
$fld5->setFieldTagAttribute('id', 'digit-5');
$fld6->setFieldTagAttribute('id', 'digit-6');

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

$btn_submit = $frm->getField('btn_submit');
$btn_submit->setFieldTagAttribute('id', 'btn_submit');
$btn_submit->setFieldTagAttribute('disabled', 'disabled');
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
                            echo $frm->getFormTag(); 
                            echo $frm->getFieldHtml('digit_1'); 
                            echo $frm->getFieldHtml('digit_2'); 
                            echo $frm->getFieldHtml('digit_3'); 
                            echo $frm->getFieldHtml('digit_4'); 
                            echo $frm->getFieldHtml('digit_5'); 
                            echo $frm->getFieldHtml('digit_6'); 
                            echo $frm->getFieldHtml('user_id'); 
                        ?>
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
<script>
    var uid = '<?php echo $userFld->value; ?>';

    $(document).ready(function(){
    let timerOn = true;
        resendTwoFactorAuthenticationCode = function (userId, ele) {
            fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'resendTwoFactorAuthenticationCode', [userId]));
            $(ele).removeAttr('onclick');
            timer(30);
        };

        var otpFieldNo = $('.digit-group').find(':input[type=text]').length;
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function(e) {
                var filledField = 0;
                var parent = $($(this).parent());
                if(e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find('input#' + $(this).data('previous'));
                    if(prev.length) {
                        $(prev).select();
                    }
                }  else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                    var next = parent.find('input#' + $(this).data('next'));
                    if(next.length) {
                        $(next).select();
                    } else {
                        if(parent.data('autosubmit')) {
                            parent.submit();
                        }
                    }
                }
                $('.digit-group').find('input[type="text"]').each(function() {
                    if($(this).val() != '') {
                        filledField++;
                    } 
                });    
                if (filledField == otpFieldNo) {
                    $('#btn_submit').removeAttr('disabled');
                } else {
                    $('#btn_submit').attr('disabled', 'disabled');
                }     
            
            });
            $(this).on('keydown', function (e){
                if ((e.keyCode >= 65 && e.keyCode <= 90) ) {
                    e.preventDefault();
                    return;
                }
            })
        });
    
        timer(30);

        function timer(remaining) {
            var m = Math.floor(remaining / 60);
            var s = remaining % 60;
            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            document.getElementById('countdowntimer').innerHTML = m + ':' + s;
            remaining -= 1;
            if(remaining >= 0 && timerOn) {
                setTimeout(function() {
                    timer(remaining);
                }, 1000);
                return;
            }
            if(!timerOn) {
                return;
            }
            $('#btn_resend_otp').attr('onclick', "resendTwoFactorAuthenticationCode("+uid+", this); return false;");
        }
});
</script>
