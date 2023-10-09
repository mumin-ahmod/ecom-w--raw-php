// cart.js
document.addEventListener('DOMContentLoaded', function () {
    var stripe = Stripe('YOUR_PUBLISHABLE_KEY');
    var elements = stripe.elements();

    var checkoutButtons = document.querySelectorAll('.checkout-button');

    checkoutButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var itemId = this.getAttribute('data-item-id');
            var paymentForm = document.querySelector('.payment-form[data-item-id="' + itemId + '"]');

            // Toggle visibility of the payment form for this item
            paymentForm.style.display = 'block';

            var cardElement = elements.create('card');
            cardElement.mount('#card-element-' + itemId);

            var form = document.getElementById('payment-form-' + itemId);

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                stripe.createToken(cardElement).then(function (result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors-' + itemId);
                        errorElement.textContent = result.error.message;
                    } else {
                        // Send the token to your server to charge the user.
                        fetch('charge.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ token: result.token.id }),
                        })
                            .then(function (response) {
                                return response.json();
                            })
                            .then(function (data) {
                                // Handle the response from your server (e.g., show a success message).
                                console.log(data);

                                // Hide the payment form after a successful payment
                                paymentForm.style.display = 'none';
                                alert('Payment Successful');
                            });
                    }
                });
            });
        });
    });
});
