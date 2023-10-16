<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

// Check if an item ID is provided in the request
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // Verify that the item belongs to the logged-in user (for security)
    $check_item = $connect->prepare("SELECT * FROM `cart` WHERE id = ? AND user_id = ?");
    $check_item->execute([$item_id, $user_id]);

    if ($check_item->rowCount() > 0) {
        // The item belongs to the user, so delete it from the cart
        $delete_item = $connect->prepare("DELETE FROM `cart` WHERE id = ?");
        $delete_item->execute([$item_id]);

        // Redirect back to the cart page after deletion
        header('location:cart.php');
    } else {
        // Redirect to the cart page with an error message if the item does not belong to the user
        header('location:cart.php?error=Item does not belong to the user.');
    }
} else {
    // Redirect to the cart page with an error message if no item ID is provided
    header('location:cart.php?error=No item ID provided.');
}
?>
