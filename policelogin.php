<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <title>Police Login</title>
    <?php
    session_start();

    if (isset($_POST['s'])) {
        $_SESSION['x'] = 1;

        // Establish database connection
        $conn = new mysqli("localhost", "root", "", "crime_portal");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // If form is submitted, process the login
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $conn->real_escape_string($_POST['email']);
            $pass = $conn->real_escape_string($_POST['password']);

            // Query the database
            $result = $conn->query("SELECT p_id, p_pass FROM police WHERE p_id='$name' AND p_pass='$pass'");

            // Check login credentials
            if ($result && $result->num_rows > 0) {
                $_SESSION['pol'] = $name;
                header("Location: police_pending_complain.php");
                exit();
            } else {
                echo "<script>alert('Id or Password not Matched.');</script>";
            }
        }

        // Close the connection
        $conn->close();
    }
    ?>
    <script>
        function f1() {
            var emailField = document.getElementById("exampleInputEmail1").value;
            var passwordField = document.getElementById("exampleInputPassword1").value;
            
            if (emailField.includes(' ')) {
                alert("Space Not Allowed in Email");
                document.getElementById("exampleInputEmail1").value = "";
                document.getElementById("exampleInputEmail1").focus();
            }
            if (passwordField.includes(' ')) {
                alert("Space Not Allowed in Password");
                document.getElementById("exampleInputPassword1").value = "";
                document.getElementById("exampleInputPassword1").focus();
            }
        }
    </script>
</head>
<body style="color: black; background-image: url(locker.jpeg); background-size: 100%; background-repeat: no-repeat;">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="home.php"><b>Crime Portal</b></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="official_login.php">Official Login</a></li>
                    <li class="active"><a href="policelogin.php">Police Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div align="center">
        <div class="form" style="margin-top: 15%">
            <form method="post">
                <div class="form-group" style="width: 30%">
                    <label for="exampleInputEmail1"><h1 style="color:white">Police Id</h1></label>
                    <input type="text" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter user id" required onfocusout="f1()">
                </div>
                <div class="form-group" style="width:30%">
                    <label for="exampleInputPassword1"><h1 style="color:white">Password</h1></label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required onfocusout="f1()">
                </div>
                <button type="submit" class="btn btn-primary" name="s">Submit</button>
            </form>
        </div>
    </div>
    <div style="position: fixed; left: 0; bottom: 0; width: 100%; height: 30px; background-color: rgba(0,0,0,0.8); color: white; text-align: center;">
        <h4 style="color: white;">&copy; <b>Crime Portal 2018</b></h4>
    </div>
</body>
</html>
