<?php

define('DB_SERVER', 'localhost'); 
define('DB_USERNAME', 'username'); 
define('DB_PASS', 'password'); 
define('DB_NAME', 'database_name'); 

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed");
}
$email  = '';
$errors = [];

if (isset($_POST['submit'])) {
    $email = htmlspecialchars($_POST['email']);
     

    if (empty($email)) {
        $errors['email'] = 'Please enter your email address';
    }


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sqlquery = "INSERT INTO BookDownloads (`Email`) VALUES ('$email')";

    if ($conn->query($sqlquery) === TRUE) {
        $successMessage = "";
    } else {
        echo "Error: " . $sqlquery . "<br>" . $conn->error;
    }

    $sql = "SELECT * FROM BookDownloads ORDER BY id DESC LIMIT 1";
    $latestRecord = $conn->query($sql)->fetch_assoc();

    $record .= "Email: " . $latestRecord['Email'] . "\n";
 

    $toEmail = "info@moongulzar.net";
    $subject = "Latest Book Downloaded";
    $headers = "From: webmaster@moongulzar.net";

    if (mail($toEmail, $subject, $record, $headers)) {
        $successMessage .= "Your email submitted successfully and your downloads begins!";
    } else {
        echo "Email could not be sent.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
  
<link rel="stylesheet" href="include/styles.css">


</head>

<body>
  
  <?php include 'include/header.php'; ?>

  <br><br>
  <div class="container mt-5">
    <h1>Enter Your Email To Download the Book</h1>
    <div class="d-flex flex-row p-2 bd-highlight bd-highlight mb-3 justify-content-center">
      <div class="col-md-6">
      <div class="container mt-5">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $email = $_POST['email'];
                  
          $fileToDownload = 'pdf/Jacob.pdf';

          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename=' . basename($fileToDownload));
            header('Content-Length: ' . filesize($fileToDownload));
            readfile($fileToDownload);
            exit;
          } else {
            echo '<div class="alert alert-danger" role="alert">Invalid email address</div>';
          }
        }
        ?>
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
          <div class="mb-2 p-2 ms-0 ps-0">
            <label for="email" class="form-label">Please submit your email to download the pdf booklet.</label>
          </div>
          <div class="mb-2 p-2">
          <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" value="<?php echo $email ?>">
          </div>

          <div>
            <input type="submit" name="submit" value="Submit" class="btn btn-warning">
          </div>
        </form>
      </div>
      <div class="p-2 bd-highlight">
        <div class="image">
          <img src="images/Jacob.jpg" alt="" width="200" height="300">
        </div>
      </div>
    </div>
  </div>
  
  <?php include 'include/footer.php'; ?>
  
</body>

</html>