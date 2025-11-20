<?php
include('connect.php');

// Initialize $myJSON to an empty array in case no results are found
$myJSON = "[]";  

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($connection, $_GET['keyword']);
    
    // Update the query to use prepared statements
    $query = "SELECT * FROM crimelog WHERE CONCAT(crime_id, description, title) REGEXP ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt === false) {
        // If the query preparation failed, show an error message
        die('MySQL prepare failed: ' . mysqli_error($connection));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "s", $keyword);

    // Execute the query
    $exec_result = mysqli_stmt_execute($stmt);
    
    if ($exec_result) {
        $strSQL = mysqli_stmt_get_result($stmt);  // Get the result set from the prepared statement
        $data = array();

        // Check if any rows are returned
        if (mysqli_num_rows($strSQL) > 0) {
            while ($row = mysqli_fetch_row($strSQL)) {
                $rowdata = array(
                    "id" => $row[0],
                    "label" => $row[1],
                    "lat" => $row[2],
                    "long" => $row[3],
                    "time" => $row[4],
                    "description" => $row[5],
                    "reportedby" => $row[6],
                    "dontknow" =>  $row[7],
                    "yes" => $row[8],
                    "no" => $row[9]
                );
                array_push($data, $rowdata);
            }
            $myJSON = json_encode($data);
        }
    } else {
        // If the query execution failed, show an error message
        die('Query execution failed: ' . mysqli_error($connection));
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}
?>

