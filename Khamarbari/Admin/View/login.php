<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khamarbari Login</title>
    <link rel="stylesheet" href="../public/style3.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <nav>
        <div class="nav-logo">
            <h3 class="nav-title">Khamar<span>bari</span></h3>
        </div>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="register.php">Sign Up</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="register-section">

        <!-- Message -->
        <?php if(isset($_SESSION['login-message'])) { ?>
            <p class="msg"><?php echo $_SESSION['login-message']; unset($_SESSION['login-message']); ?></p>
        <?php } ?>

        <div class="register-box">

            <h1>Login</h1>
            <p>Welcome back! Please login to your account</p>

            <form action="../Controller/logauth.php" method="post">
<label for="userId">User ID</label><br>
                            <input type="text" id="userId" name="userId" class="login-inp" required placeholder="Write your User-id "><br>
                
                            <label for="password">Password</label><br>
                            <input type="password" id="password" name="password" class="login-inp" required placeholder="Write Your Password"><br>
                            <a href="forgot_password.php" class="forgotPassBtn">Forgot Password?</a>
                            
                            <button type="submit" class="login-btn">LOG IN</button> 

            </form>

            <p class="login-text">
                Don't have an account?
                <a href="register.php">Sign Up</a>
            </p>

        </div>
    </div>
</main>



</body>
</html>
