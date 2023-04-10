<?php
    require_once('db.php');
    session_start();

    if(isset($_SESSION['user_login'])){
        header("location: welcome.php");
    }
    //เช็คข้อมูลในการล็อกอิน
    if(isset($_REQUEST['btn_login'])){ 
        $username = strip_tags($_REQUEST['txt_username_email']);
        $email = strip_tags($_REQUEST['txt_username_email']);
        $password = strip_tags($_REQUEST['txt_password']);

        if(empty($username)){
            $errorMsg[] = "Please enter username or email";
        }elseif(empty($email)){
            $errorMsg[] = "Please enter username or email";
        }elseif (empty($password)){
            $errorMsg[] = "Please enter password";
        }else{
            try{
                $select_stmt = $conn->prepare("SELECT * FROM tbl_user WHERE username = :uname OR email = :uemail");
                $select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email));
                $row  = $select_stmt->fetch(PDO::FETCH_ASSOC);

                if($select_stmt->rowCount()>0){
                    if($username==$row['username'] OR $email==$row['email']){
                        if(password_verify($password, $row['password'])){
                            $_SESSION['user_login']=$row['id'];
                            $loginMsg="Successfully Login...";
                            header("refresh:2; welcome.php");
                        }else{
                            $errorMsg[]="Wrong password";
                        }
                    }else{
                        $errorMsg[]="Wrong username or email";
                    }
                }else{
                    $errorMsg[]="Wrong username or email";
                }
            }catch(PDOException $e){
                $e->getMessage();
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
    <title>Login</title>
</head>

<body>

    <div class="container ">
        <h1 class="mt-5 text-center">Login Page</h1>
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
                if(isset($loginMsg)){//ใส่การแจ้งเตือนในหน้า register
            ?>
                <div class="alert alert-success">
                    <strong><?php echo $loginMsg; ?></strong>
                </div>
            <?php
                    }
            ?>
            <div class="row mb-3">
                <label for="username" class="form-label">Username or Email</label>
                <input type="text" name="txt_username_email" class="form-control" id="exampleInputEmail1" placeholder="Enter your Username or Email....">
            </div>
            <div class="row mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="txt_password"   class="form-control" id="exampleInputPassword1" placeholder="Enter your Password">
            </div>

            <button type="submit" name="btn_login" class="btn btn-primary" value="Login">Submit</button>
            <div class="mb-3 form-check mt-5 text-center">
                <p>You don't have account? <a href="register.php">Register here!</a></p>
            </div>
        </form>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>

