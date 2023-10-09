<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
    ///// Redirects to home page if the user is not logged in
}

// Handle the POST request from the "Add to Cart" button here
if (isset($_POST['add_to_cart'])) {
    // Retrieve and sanitize the posted data
    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $image = $_POST['image'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $quantity = $_POST['quantity'];
    $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);

    // You can now save this data to the cart table or perform any other necessary actions
    // For example, insert the data into the cart table in your database
    $insert_cart = $connect->prepare("INSERT INTO `cart`(user_id, pid, name, price, image, quantity) VALUES(?,?,?,?,?,?)");
    $insert_cart->execute([$user_id, $pid, $name, $price, $image, $quantity]);

    // You can add further logic or redirect the user as needed after adding to the cart
}

// Fetch cart items for the logged-in user
$select_cart_items = $connect->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$select_cart_items->execute([$user_id]);

// Initialize an array to store cart items
$cart_items = [];

while ($row = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <!-- Add your CSS styles here -->
    <style>
        .card {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 300px;
            display: inline-block;
            vertical-align: top;
        }

        .card img {
            max-width: 100%;
            height: auto;
        }

        .checkout-button {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .payment-form {
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!--- Font Awesome Plug-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!--- CSS Link-->
    <link rel="stylesheet" href="css/style.css">

    <?php include 'components/user_header.php';?>

    <!-- Add your HTML content for the cart page here -->
    <div class="cart-items">
        <?php
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    foreach ($cart_items as $item) {
        echo "<div class='card'>";
        echo "<img src='" . $item['image'] . "' alt='" . $item['name'] . "'>";
        echo "<h2>" . $item['name'] . "</h2>";
        echo "<p>Price: $" . $item['price'] . "</p>";
        echo "<p>Quantity: " . $item['quantity'] . "</p>";
        echo "<button class='checkout-button' data-item-id='" . $item['id'] . "'>Checkout Now</button>";

        // Add a payment form for each item with a unique data attribute
        echo "<form class='payment-form' data-item-id='" . $item['id'] . "' method='post' action='checkout.php'>";
        echo "Payment Form for " . $item['name'] . "<br>";
        echo "<div class='form-group'>";
        echo "<label for='card-element'>";
        echo "Credit or debit card";
        echo "</label>";
        echo "<div id='card-element-" . $item['id'] . "'>";
        echo "<!-- A Stripe Element will be inserted here. -->";
        echo "</div>";
        echo "<!-- Used to display form errors. -->";
        echo "<div id='card-errors-" . $item['id'] . "' role='alert'></div>";
        echo "</div>";
        echo "<input type='hidden' name='item_id' value='" . $item['id'] . "'>";
        echo "</form>";

        // Add a "Pay" button for each payment form
        echo "<button class='pay-button' data-item-id='" . $item['id'] . "'>Pay</button>";

        echo "</div>";
    }
}
?>
    </div>

    <?php include 'components/footer.php';?>

    <!-- Add your JavaScript code for handling the checkout button click event here -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('YOUR_PUBLISHABLE_KEY');
        var elements = stripe.elements();

        // Handle the checkout button click event for each item
        var checkoutButtons = document.querySelectorAll('.checkout-button');

        checkoutButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var itemId = this.getAttribute('data-item-id');
                var paymentForm = document.querySelector('.payment-form[data-item-id="' + itemId + '"]');
                var cardElement = elements.create('card');
                cardElement.mount('#card-element-' + itemId);
                var form = paymentForm.querySelector('form');

                // Toggle visibility of the payment form for this item
                paymentForm.style.display = 'block';
            });
        });

        // Handle the "Pay" button click event for each item
        var payButtons = document.querySelectorAll('.pay-button');

        payButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var itemId = this.getAttribute('data-item-id');
                var paymentForm = document.querySelector('.payment-form[data-item-id="' + itemId + '"]');
                var cardElement = elements.create('card');
                cardElement.mount('#card-element-' + itemId);
                var form = paymentForm.querySelector('form');

                // Serialize form data into a JSON object
                var formData = new FormData(form);
                var formDataObject = {};
                formData.forEach(function (value, key) {
                    formDataObject[key] = value;
                });

                stripe.createToken(cardElement).then(function (result) {
                    if (result.error) {
                        var errorElement = paymentForm.querySelector('#card-errors-' + itemId);
                        errorElement.textContent = result.error.message;
                    } else {
                        // Add the token to the form data
                        formDataObject['token'] = result.token.id;

                        // Send the form data to your server to charge the user.
                        fetch('checkout.php', {
                            method: 'POST',
                            body: JSON.stringify(formDataObject),
                            headers: {
                                'Content-Type': 'application/json',
                            },
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
        });
    </script>
</body>
</html>
