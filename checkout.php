<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

// Retrieve the item_id from the URL query parameter
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // Fetch the item information based on the item_id
    $select_item = $connect->prepare("SELECT * FROM `cart` WHERE user_id = ? AND id = ?");
    $select_item->execute([$user_id, $item_id]);

    $item = $select_item->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        // Handle the case where the item does not exist or is not in the user's cart
        header('location:cart.php');
    }
} else {
    // Handle the case where the item_id query parameter is not provided
    header('location:cart.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="https://js.stripe.com/v3/"></script>

    <style>
        /* Add custom styles here */
        .container {
            margin: 0 5%; /* 5% margin on left and right sides */
        }
        /* Add any additional custom styles as needed */
    </style>
</head>
<body>
    <!-- Use Bootstrap container for page layout -->
    <div class="container">
        <h1 class="mt-5">Checkout</h1>

        <!-- Display product details using Bootstrap cards -->
        <div class="card mt-3">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="uploaded_img/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="card-img">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Product Name: <?php echo $item['name']; ?></h5>
                        <p class="card-text">Quantity: <?php echo $item['quantity']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment form -->
      <!-- Include the amount field inside the form -->
<form id="payment-form" class="mt-3">
    <label for="card-element">
        Credit or debit card
    </label>
    <div id="card-element" class="form-control">
        <!-- A Stripe Element will be inserted here. -->
    </div>
    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>

    <!-- Include the amount field -->
    <input type="hidden" name="amount" value="<?php echo $item['price'] * 100; ?>"> <!-- Adjust the amount as needed and multiply by 100 to convert to cents -->

    <button id="submit-button" class="btn btn-primary mt-3">Pay Now</button>
</form>

<button id="submit-button" class="btn btn-info mt-3">Save Order</button>

    </div>

    <!-- Add Bootstrap JS and jQuery links here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        var stripe = Stripe('pk_test_51NzCHGSJmr02EggB23gy85uS8Rz1AB6HTWblMOB4XiLkGwP0LUHhjhLBgXQLRR9JzfHZ2VkMVsD4knsltKwiUKGL00pip3C6kq');
        var elements = stripe.elements();

        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        var form = document.getElementById('payment-form');
        var submitButton = document.getElementById('submit-button');
        var amountInput = document.getElementById('amount'); // Get the amount input field

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Disable the submit button to prevent multiple submissions
            submitButton.disabled = true;

            stripe.createToken(cardElement).then(function (result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    submitButton.disabled = false; // Re-enable the submit button
                } else {
                    // Send the token and amount to your server to charge the user.
                    fetch('charge.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            token: result.token.id,
                            amount: amountInput.value, // Get the amount from the hidden input field
                        }),
                    })
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            // Handle the response from your server (e.g., show a success message).
                            console.log(data);
                            alert('Payment Successful');

                           
                        });
                }
            });
        });
    </script>
</body>
</html>
