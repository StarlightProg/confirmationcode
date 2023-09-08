$(document).ready(function() {
    const $methodSelect = $('#method-select');
    const $inputEmail = $('#input-email');
    const $inputPhone = $('#input-phone');
    const $inputTelegram = $('#input-telegram');

    $methodSelect.on('change', function() {
        $inputEmail.hide();
        $inputPhone.hide();
        $inputTelegram.hide();

        switch ($methodSelect.val()) {
            case 'email':
                $inputEmail.show();
                break;
            case 'sms':
                $inputPhone.show();
                break;
            case 'telegram':
                $inputTelegram.show();
                break;
        }
    });
});