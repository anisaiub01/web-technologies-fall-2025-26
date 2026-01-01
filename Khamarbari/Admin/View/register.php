<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Khamarbari Registration</title>
    <link rel="stylesheet" href="../public/style2.css">
</head>
<body>

<!-- HEADER -->
<header>
    <nav>
        <div class="nav-logo">
            <h3 class="nav-title">Khamar<span>bari</span></h3>
        </div>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<!-- MAIN -->
<main>
    <div class="register-section">

        <!-- Message -->
        <?php if(isset($_SESSION['message'])) { ?>
            <p class="msg"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php } ?>

        <div class="register-box">

            <h1>Create Account</h1>
            <p>Join Khamarbari today</p>

          <form action="../Controller/registerauth.php" method="post" onsubmit="return validateForm()">
                        <label for="userId">User ID</label><br>
                        <input type="text" class="inp" id="userId" name="userId" required><br>
                        <div id="userId-error" class="mbT"></div>

                        <label for="name">Name</label><br>
                        <input type="text" class="inp" id="name" name="name"  required><br>
                        <div id="name-error" class="mbT"></div>

                        <label for="email">E-Mail</label><br>
                        <input type="email" class="inp" id="email" name="email" " required><br>
                        <div id="email-error" class="mbT"></div>

                        <label for="phone">Phone Number</label><br>
                        <input type="text" class="inp" id="phone" name="phone"  required><br>
                        <div id="phone-error" class="mbT"></div>

                        <label for="password">Password</label><br>
                        <input type="password" class="inp" id="password" name="password" required><br>
                        <div id="password-error" class="mbT"></div>

                        <label for="userType">User Type</label><br>
                        <select name="userType" class="inp" id="userType" onchange="toggleNid()">
                            <option value="">Select</option>
                            <option value="consumer">Consumer</option>
                            <option value="farmer">Farmer</option>
                        </select><br>
                        <div id="userType-error" class="mbT"></div>

                        <label for="address">Address</label><br>
                        <textarea name="address" class="address-inp" id="address" required></textarea><br>
                        <div id="address-error" class="mbT"></div>

                        <div id="nidField" style="display: none;">
                            <label for="nid">NID Number</label><br>
                            <input type="text" class="inp" id="nid" name="nid">
                        </div>
                        <div id="nid-error" class="mbT"></div>

                        <button type="submit" class="reg-btn">Register</button>
                    </form>

            <p class="login-text">
                Already have an account?
                <a href="../View/login.php">Login</a>
            </p>

        </div>
    </div>
</main>

</body>
</html>
