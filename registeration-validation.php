<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

$errors = [];
$olddata= [];
  
if (empty($_POST["username"])) {
    $errors["username"] = "Username is required";
  } elseif(!preg_match("/^[a-zA-Z-' ]*$/",$_POST["username"])) {
    $errors["username"] = "Only letters and white space allowed";
}
  else {
    $olddata["username"] = $_POST['username'];
}
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        
        $olddata["email"] = $_POST["email"];
    }else{
        $errors["email"] = "Invalid Email format";
    }
   


    $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
    $lowercase = preg_match('@[a-z]@',$_POST["password"]);
    $number    = preg_match('@[0-9]@', $_POST["password"]);
    $specialChars = preg_match('@[^\_]@', $_POST["password"]);
    
    

    if (empty($_POST["password"])){
        $errors["password"]="Password is required";
    }elseif($uppercase || !$lowercase || $number  || strlen($password) > 8) {
        $errors["password"]='Password should be at most only 8 characters in length and should not include  upper case letter or number.';
    }
    else{
        $olddata["password"] = $_POST["password"];
    }
    

    if (empty($_POST["room"])){
        $errors["room"]="Please select your room";
    }else{
        $olddata["room"] = $_POST["room"];
    }


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



$target_dir = "files/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $olddata['image'] = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $errors['image'] = "File is not an image.";
        $uploadOk = 0;
    }
}

if (file_exists($target_file)) {
    $errors['image'] = "Sorry, file already exists.";
    $uploadOk = 0;
}

if ($_FILES["image"]["size"] > 5000000) {
    $errors['image'] = "Sorry, your file is too large.";
    $uploadOk = 0;
}

if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
) {
    $errors['image'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    $errors['image'] = "Sorry, your file was not uploaded.";
 
} else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $olddata['image'] = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
    } else {
        $errors['image'] = "Sorry, there was an error uploading your file.";
    }
}


    if (count($errors)> 0){

        $err=json_encode($errors);

        if(count($olddata) > 0) {
            var_dump($olddata);
            $old = json_encode($olddata);

            header("Location:registeration.php?errors={$err}&olddata={$old}");
        }else {
            header("Location:registeration.php?errors={$err}"); 
        }
    } else {

        $user = implode(":", $_POST);
    
        try {
            
            $userfile = fopen("users.txt", "a");
            fwrite($userfile, $user . PHP_EOL);
            fclose($userfile);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        header("Location:login.php");
    }


 