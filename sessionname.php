<?php
session_start();  // Make sure this is at the top

if (isset($_SESSION['name'])) {
    // Your logic for using the session variable
    echo "Hello, " . $_SESSION['name'];
} else {
    echo "Name is not set in the session.";
}
?>
