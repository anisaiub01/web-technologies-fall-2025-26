<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khamarbari Login</title>
    <link rel="stylesheet" href="../public/style3.css?v=<?php echo time(); ?>">
    <style>
        .error {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        .input-error {
            border-color: #e74c3c !important;
        }
    </style>
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

        <?php if(isset($_SESSION['login-error_message'])) { ?>
            <p class="msg"><?php echo $_SESSION['login-error_message']; unset($_SESSION['login-error_message']); ?></p>
        <?php } ?>

        <div class="register-box">

            <h1>Login</h1>
            <p>Welcome to Khamarbari! Please Login</p>

            <form id="loginForm" action="../Controller/logauth.php" method="post" novalidate>
                <label for="userId">User ID</label><br>
                <input type="text" id="userId" name="userId" class="login-inp" placeholder=""><br>
                <span id="userIdError" class="error"></span>
                
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" class="login-inp" placeholder=""><br>
                <span id="passwordError" class="error"></span>
                
              
                
                <button type="submit" class="login-btn">LOG IN</button> 
            </form>

            <p class="login-text">
                Don't have an account?
                <a href="register.php">Sign Up</a>
            </p>

        </div>
    </div>
</main>

<script>
    const loginForm = document.getElementById('loginForm');
    const userIdInput = document.getElementById('userId');
    const passwordInput = document.getElementById('password');
    const userIdError = document.getElementById('userIdError');
    const passwordError = document.getElementById('passwordError');

    // Real-time validation
    userIdInput.addEventListener('blur', validateUserId);
    passwordInput.addEventListener('blur', validatePassword);

    userIdInput.addEventListener('input', function() {
        if (userIdError.textContent) validateUserId();
    });

    passwordInput.addEventListener('input', function() {
        if (passwordError.textContent) validatePassword();
    });

    // Form submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isUserIdValid = validateUserId();
        const isPasswordValid = validatePassword();

        if (isUserIdValid && isPasswordValid) {
            loginForm.submit();
        }
    });

    function validateUserId() {
        const userId = userIdInput.value.trim();
        
        if (userId === '') {
            showError(userIdInput, userIdError, 'User ID is required');
            return false;
        }
        
   

        if (userId.length > 50) {
            showError(userIdInput, userIdError, 'User ID must not exceed 50 characters');
            return false;
        }

        // Check for valid characters 
        const validPattern = /^[a-zA-Z0-9_-]+$/;
        if (!validPattern.test(userId)) {
            showError(userIdInput, userIdError, 'User ID can only contain letters, numbers, underscore, and hyphen');
            return false;
        }
        
        clearError(userIdInput, userIdError);
        return true;
    }

    function validatePassword() {
        const password = passwordInput.value;
        
        if (password === '') {
            showError(passwordInput, passwordError, 'Password is required');
            return false;
        }
        
        if (password.length < 6) {
            showError(passwordInput, passwordError, 'Password must be at least 4 characters');
            return false;
        }

        if (password.length > 100) {
            showError(passwordInput, passwordError, 'Password is too long');
            return false;
        }
        
        clearError(passwordInput, passwordError);
        return true;
    }

    function showError(input, errorElement, message) {
        input.classList.add('input-error');
        errorElement.textContent = message;
    }

    function clearError(input, errorElement) {
        input.classList.remove('input-error');
        errorElement.textContent = '';
    }
</script>

</body>
</html>