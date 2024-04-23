<?php
require_once ('lib/PageTemplate.php');
require_once ("Validator.php");
require_once 'database.php';



$message = "";
$username = "";
$registeredOk = false;
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat_password'];
    $name = $_POST['name'];
    $street = $_POST['street'];
    $postal = $_POST['postal'];
    $city = $_POST['city'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);





    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ogiltig e-postadress";
    }

    if (strlen($password) < 6) {
        $errors[] = "Lösenordet måste vara minst 6 tecken långt";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Lösenordet måste innehålla minst en stor bokstav";
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Lösenordet måste innehålla minst en liten bokstav";
    }

    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors[] = "Lösenordet måste innehålla minst ett specialtecken";
    }

    if ($password !== $repeatPassword) {
        $errors[] = "Lösenorden matchar inte";
    }

    if (empty($name)) {
        $errors[] = "Namnet får inte vara tomt";
    }

    if (empty($street)) {
        $errors[] = "Gatunamnet får inte vara tomt";
    }

    if (!preg_match('/^\d{5}$/', $postal)) {
        $errors[] = "Ogiltigt postnummer";
    }

    if (empty($city)) {
        $errors[] = "Staden får inte vara tomt";
    }

    if (empty($errors)) {
        $registeredOk = true;
    }

}

?>

<?php if (!isset($TPL)): ?>
    <?php
    $TPL = new PageTemplate();
    $TPL->PageTitle = "Register";
    $TPL->ContentBody = __FILE__;
    include "layout.php";
    exit;
?>
<?php endif; ?>

<?php if ($registeredOk): ?>
    <div>Tack för din registrering! Kontrollera din e-post för att slutföra processen.</div>
<?php else: ?>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <p>
    <div class="row">
        <div class="col-md-12">
            <div class="newsletter">
                <p>User<strong>&nbsp;REGISTER</strong></p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input class="input" type="email" name="email" placeholder="Enter Your Email" required>
                    <br /><br />
                    <input class="input" type="password" name="password" placeholder="Enter Your Password" required>
                    <br /><br />
                    <input class="input" type="password" name="repeat_password" placeholder="Repeat Password" required>
                    <br /><br />
                    <input class="input" type="text" name="name" placeholder="Name" required>
                    <br /><br />
                    <input class="input" type="text" name="street" placeholder="Street address" required>
                    <br /><br />
                    <input class="input" type="text" name="postal" placeholder="Postal code" required>
                    <br /><br />
                    <input class="input" type="text" name="city" placeholder="City" required>
                    <br /><br />
                    <button type="submit" class="newsletter-btn"><i class="fa fa-envelope"></i> Register</button>
                </form>
            </div>
        </div>
    </div>
    </p>
<?php endif; ?>