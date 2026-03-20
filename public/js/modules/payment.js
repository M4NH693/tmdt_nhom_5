export function initPayment() {
    // === Payment option highlight ===
    const paymentOptions = document.querySelectorAll('.payment-option');
    paymentOptions.forEach(function (option) {
        option.addEventListener('click', function () {
            paymentOptions.forEach(function (o) { o.classList.remove('selected'); });
            option.classList.add('selected');
            const radio = option.querySelector('input[type=radio]');
            if(radio) radio.checked = true;
        });
    });
}
