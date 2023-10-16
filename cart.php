<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
    ///// Redirects to the home page if the user is not logged in
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
        echo "<img src='uploaded_img/" . $item['image'] . "' alt='" . $item['name'] . "'>";
        echo "<h2>" . $item['name'] . "</h2>";
        echo "<p>Price: $" . $item['price'] . "</p>";
        echo "<p>Quantity: " . $item['quantity'] . "</p>";
        // Add a "Delete" button for each item with a link to delete_from_cart.php
        echo "<a class='btn btn-info btn-sm' href='delete_from_cart.php?item_id=" . $item['id'] . "'>Delete</a>";
        echo " <br>";
        echo "<a class='checkout-button' href='checkout.php?item_id=" . $item['id'] . "'>Checkout Now</a>";
        echo "</div>";
    }
}
?>
    </div>

    <?php include 'components/footer.php';?>

    <!--- JS Link -->
    <script src="js/script.js"></script>

<script>
    // Add a click event listener for all "Delete from Cart" buttons
    const deleteButtons = document.querySelectorAll('.delete-from-cart-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            // Get the item ID from the data attribute
            const itemId = button.getAttribute('data-item-id');

            // Send a request to delete the item from the cart using AJAX
            fetch('delete_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ itemId: itemId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Item was successfully deleted, remove the card element from the page
                    const card = button.closest('.card');
                    card.remove();
                } else {
                    // Handle the deletion error, show an alert or error message
                    alert('Failed to delete item from the cart.');
                }
            })
            .catch(error => {
                // Handle any network or request errors
                console.error('Error:', error);
            });
        });
    });
</script>
</body>
</html>
