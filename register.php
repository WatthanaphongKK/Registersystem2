<?php

    require_once('db.php');

    if(isset($_REQUEST['btn_register'])){
        $username = strip_tags($_REQUEST['txt_username']);
        $email = strip_tags($_REQUEST['txt_email']);
        $password = strip_tags($_REQUEST['txt_password']);

        if(empty($username)){
            $errorMsg[] = "Please enter username";
        }elseif(empty($email)){
            $errorMsg[] = "Please enter email";
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errorMsg[] = "Please enter a valid email address";
        }elseif(empty($password)){
            $errorMsg[] = "Please enter password";
        }elseif(strlen($password) < 6 ||strlen($password)>20){
            $errorMsg[] = "Password must be atleast 6 to 20 charlecters";
        }else {
            try{
                $select_stmt = $conn->prepare("SELECT username, email FROM tbl_user WHERE username = :uname OR email = :uemail");
                $select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email)); //แทนที่ตัวแปลเก็บข้อมูลเป็น array
                $row  = $select_stmt->fetch(PDO::FETCH_ASSOC);
                
                if($row['username']==$username){
                    $errorMsg[] = "Sorry username already exists";
                }elseif($row['email']==$email){
                    $errorMsg[] = "Sorry email already exists";
                }elseif(!isset($errorMsg)){
                    $new_password = password_hash($password, PASSWORD_DEFAULT);
                    $insert_stmt = $conn->prepare("INSERT INTO tbl_user (username, email, password) VALUES(:uname, :uemail, :upassword)");
                    if ($insert_stmt->execute(array(
                        ':uname' => $username,
                        ':uemail' => $email,
                        ':upassword' => $new_password
                    ))) {
                        $registerMsg = "Register successfully";
                    }
                }
            } catch(PDOException $e){
                echo $e->getMessage();            
            }
        }
    }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Register</title>
</head>

<body>

    <div class="container ">
        <h1 class="mt-5 text-center">Register Page</h1>
        <form action="" class="form-horizontal" method="post">
            <?php
                if(isset($errorMsg)){//ใส่การแจ้งเตือนในหน้า register
                    foreach($errorMsg as $error) {
            
            ?>
                <div class="alert alert-danger">
                    <strong><?php echo $error; ?></strong>
                </div>
            <?php
                    }
                }
            ?>
            <?php
                if(isset($registerMsg)){//ใส่การแจ้งเตือนในหน้า register
            ?>
                <div class="alert alert-success">
                    <strong><?php echo $registerMsg; ?></strong>
                </div>
            <?php
                    }
            ?>
            <div class="row mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="txt_username" class="form-control" placeholder="Enter your Username">
            </div>
            <div class="row mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="txt_email" class="form-control" placeholder="Enter your Email">
            </div>
            <div class="row mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="txt_password"  class="form-control" id="exampleInputPassword1" placeholder="Enter your Password">
            </div>
            <button type="submit" name="btn_register" class="btn btn-primary" value="register">Register</button>

            <div class="mb-3 form-check mt-5 text-center">
                <p>You have account? <a href="index.php">Login here!</a></p>
            </div>
        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>