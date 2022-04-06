<?php
$users = file("users.txt");
$users = implode(":", $users);
$users = explode(":", $users);

    $errors = [];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($email)) {
        $errors["email"] = "Email is required";
    }
    if (empty($password)) {
        $errors["password"] = "Password is required";
    }
    if (count($errors) > 0) {
        $err = json_encode($errors);
        header("Location:login.php?errors={$err}");
        return;
    } else {

    if (isset($_POST['email'])) {
        $dir = "users.txt";
        $datafile = fopen($dir, 'r');
        while (($line = fgets($datafile)) !== false) {
            $userArray = explode(":", $line);
            $emailtemp = trim($_POST["email"]);
           
            if ($emailtemp === $userArray[1]) {
                $passwordtemp = $userArray[2];
                var_dump($passwordtemp);
                if ($passwordtemp === trim($_POST["password"])) {
        
                    echo "Started session";
                    session_start();
                    $_SESSION["email"] = $emailtemp;
                    header("Location:homepage.php");
                    return;
                }
            } else {
                continue;
            };
        }
    }
    $errors["password"] = "Incorrect password or email";
    $err = json_encode($errors);
    header("Location:login.php?errors={$err}");
}

    
  
