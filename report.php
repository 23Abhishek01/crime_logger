<?php
session_start();
include_once('connect.php');

// Ensure session variables 'name' and 'email' are set
if(isset($_SESSION['name']) && isset($_SESSION['email'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
} else {
    // If not logged in, show an alert and exit
    echo '<div class="alert alert-danger" id="notify">Please <a href="index.php">login</a> and come back</div>';
    exit;
}

// Verify if user account is active
$strSQL = mysqli_query($connection, "SELECT active FROM crimeuser WHERE email='$email';");
if(mysqli_fetch_row($strSQL)[0] == 0){
    die('<div class="alert alert-danger" id="notify">First activate your account from an email sent to you. <a href="index.php">Go to home page</a></div>');
}

// Fetch crime user data for nearby alerts
$strSQL = mysqli_query($connection, "SELECT id, lat, lng FROM crimeuser;");
$ids = array();
if(mysqli_num_rows($strSQL) > 0){
    while ($row = mysqli_fetch_assoc($strSQL)){
        $ids[] = array(
            "id" => $row['id'],
            "lat" => $row['lat'],
            "long" => $row['lng'],
        );
    }
}

// Handle crime report submission
if(isset($email)){
    if(isset($_POST['title'])){
        $title = mysqli_real_escape_string($connection, $_POST['title']);
        $lat = mysqli_real_escape_string($connection, $_POST['lat']);
        $long = mysqli_real_escape_string($connection, $_POST['long']);
        $time = mysqli_real_escape_string($connection, $_POST['time']);
        $desc = mysqli_real_escape_string($connection, $_POST['desc']);
        $by = $name;
        $desc = "Time of Crime: ".$time." as reported,<br>".$desc;
        
        // Insert crime report into the database
        $strSQL = mysqli_query($connection, "INSERT IGNORE INTO crimelog (title, latitude, longitude, description, postedby, yes) VALUES('$title','$lat','$long','$desc','$by',1)");
        
        if($strSQL){
            $lastInsertIdResult = mysqli_query($connection, "SELECT LAST_INSERT_ID()");
            $lastInsertId = mysqli_fetch_row($lastInsertIdResult)[0];
            
            echo '<div class="alert alert-success" id="notify">Crime Reported</div>';
            echo "<script>document.cookie = '$lastInsertId=yes';</script>";
            
            // Notify nearby users about the new crime report
            $emailid = explode(',', $_POST['nearby']);
            foreach ($emailid as $id) {
                $result = mysqli_query($connection, "SELECT email FROM crimeuser WHERE id='$id'");
                $recipientEmail = mysqli_fetch_row($result)[0];
                mail($recipientEmail, "New crime recorded in your area", "$name has reported a crime in your area. Please view the report at <a href='http://localhost/crimelogger/searchcrime.php?keyword=$title'>This Link</a><br> and don't forget to vote.", "From:Crime Logger <admin@local.host>\r\nContent-Type: text/html\r\n");
            }
            
            echo "<script>window.location = 'report.php';</script>";
        } else {
            echo '<div class="alert alert-danger" id="notify">Error reporting crime</div>';
        }
    }
} else {
    echo "Please Log In First";
    echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 1000);</script>";
}
?>
