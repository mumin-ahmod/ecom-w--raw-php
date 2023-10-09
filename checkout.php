<?php 
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
    header('location:home.php');

}


?>

<!-- checkout.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    
    <script src="https://js.stripe.com/v3/"></script>
    
    <style>
        /* Add your CSS styles for the checkout page here */
    </style>
</head>
<body>
    <!-- Include your checkout form HTML here -->
    <h1>Checkout</h1>
    <form id="payment-form">
        <!-- Payment form elements (e.g., card element, name, address) go here -->
    </form>

    <div id="card-errors" role="alert"></div>

    <button id="submit-button">Pay Now</button>

    <script>
        var stripe = Stripe('pk_test_51NzCHGSJmr02EggB23gy85uS8Rz1AB6HTWblMOB4XiLkGwP0LUHhjhLBgXQLRR9JzfHZ2VkMVsD4knsltKwiUKGL00pip3C6kq');
        var elements = stripe.elements();

        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        var form = document.getElementById('payment-form');
        var submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Disable the submit button to prevent multiple submissions
            submitButton.disabled = true;

            stripe.createToken(cardElement).then(function (result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
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
                            alert('Payment Successful');

                            // Redirect the user to a thank you page or another appropriate page
                            window.location.href = 'thank_you.php';
                        });
                }
            });
        });
    </script>
</body>
</html>
