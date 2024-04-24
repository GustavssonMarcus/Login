<?php
require_once ('lib/PageTemplate.php');
require_once ('Database.php');


if (!isset($TPL)) {
    $TPL = new PageTemplate();
    $TPL->PageTitle = "Login";
    $TPL->ContentBody = __FILE__;
    include "layout.php";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Felaktig e-postadress.');</script>";
    } else {
        $query = "SELECT * FROM userdetails WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['Id'];
                $_SESSION['user_email'] = $user['email'];
                echo "<script>alert('Inloggad');</script>";
            } else {
                echo "<script>alert('Fel lösenord.');</script>";
            }
        } else {
            echo "<script>alert('Användaren finns inte.');</script>";
        }
    }
}

?>

<p>
<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="newsletter">
                <p>User<strong>&nbsp;LOGIN</strong></p>
                <form action="AccountLogin.php" method="post">
                    <input class="input" type="email" name="email" placeholder="Enter Your Email">
                    <br />
                    <br />
                    <input class="input" type="password" name="password" placeholder="Enter Your Password">
                    <br />
                    <br />
                    <button class="newsletter-btn"><i class="fa fa-envelope"></i> Login</button>
                </form>
                <a href="">Lost password?</a>
            </div>
        </div>
    </div>
</div>
</p>