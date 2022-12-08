<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form two-factor-form');
$frm->setFormTagAttribute('action', '');
$frm->setFormTagAttribute('onSubmit', 'setupTwoFactor(this); return(false);');
$fld1 = $frm->getField('digit_1');
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
$btn_submit->setFieldTagAttribute('class', 'btn btn--disabled');
$btn_submit->setFieldTagAttribute('disabled', 'disabled');
?>

<div class="box box--narrow">

    <div class="auth-screen">
        <hgroup>
            <h4 class="-border-title margin-bottom-2"><?php echo Label::getLabel('LBL_Verification_Code'); ?></h4>
            <?php echo Label::getLabel('LBL_ENTER_CODE_SENT_ON_YOUR_MAIL_TO_LOGIN'); ?>
        </hgroup>

        <?php
        echo $frm->getFormTag();
        ?>
        <div class=" digit-group">
            <?php
            echo $frm->getFieldHtml('digit_1');
            echo $frm->getFieldHtml('digit_2');
            echo $frm->getFieldHtml('digit_3');
            echo $frm->getFieldHtml('digit_4');
            echo $frm->getFieldHtml('digit_5');
            echo $frm->getFieldHtml('digit_6');
            ?>
        </div>
        <?php
        echo $frm->getFieldHtml('user_id');
        echo $frm->getFieldHtml('remember_me');
        ?>

        <div class="margin-bottom-4">
            <?php echo $frm->getFieldHtml('btn_submit'); ?>
        </div>

        <div class="auth-msg">
            <?php
            $resendText = Label::getLabel('LBL_Didn\'t_Get_The_Code?_{link}');
            $resendText1 = str_replace("{link}", '<a href="javascript:void(0)" class="-link-underline link-color" onclick="resendOtp(\'' . $userFld->value . '\'); return false;">' . Label::getLabel('LBL_RESEND_OTP') . '</a>', $resendText);
            $resendText2 = str_replace("{link}", Label::getLabel('LBL_RESEND_OTP_IN') . ' <span id="countdowntimer"></span>', $resendText);
            ?>
            <span class="resendOtpJs">
                <?php echo $resendText2; ?>
            </span>
        </div>

        </form>
        <?php
        echo $frm->getExternalJs();
        ?>
    </div>
</div>
<script>
    var uid = '<?php echo $userFld->value; ?>';

    $(document).ready(function() {
        let timerOn = true;
        resendOtp = function(userId) {
            fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'resendTwoFactorAuthenticationCode', [userId]));
            $('.resendOtpJs').html('<?php echo addslashes($resendText2) ?>');
            timer(30);
        };

        var otpFieldNo = $('.digit-group').find(':input[type=text]').length;
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 1);
            $(this).on('keypress', function(e) {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                if (!regex.test(key)) {
                    e.preventDefault();
                    return;
                }
            });
        });
        $("body").on('keyup', '.digit-group input[type=text]', function(e) {
            var filledField = 0;
            var parent = $($(this).parent());
            if (e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));
                if (prev.length) {
                    $(prev).select();
                }
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));
                if (next.length) {
                    $(next).select();
                } else {
                    if (parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
            $('.digit-group').find('input[type="text"]').each(function() {
                if ($(this).val() != '') {
                    filledField++;
                }
            });
            if (filledField == otpFieldNo) {
                $('#btn_submit').removeAttr('disabled').removeClass('btn--disabled');
            } else {
                $('#btn_submit').attr('disabled', 'disabled').addClass('btn--disabled');
            }
        });

        timer(30);

        function timer(remaining) {
            var m = Math.floor(remaining / 60);
            var s = remaining % 60;
            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            document.getElementById('countdowntimer').innerHTML = m + ':' + s;
            remaining -= 1;
            if (remaining >= 0 && timerOn) {
                setTimeout(function() {
                    timer(remaining);
                }, 1000);
                return;
            }
            if (!timerOn) {
                return;
            }
            $('.resendOtpJs').html('<?php echo addslashes($resendText1) ?>');
        }
    });
</script>