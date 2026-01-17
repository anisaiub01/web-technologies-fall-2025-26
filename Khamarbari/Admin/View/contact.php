<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../public/style5.css?v=<?php echo time(); ?>">
  <title>Contact Us-AgriConnect</title>
</head>
<body>
<header>
  <nav>
    <div class="nav-logo">
      <h3 class="nav-title"><span>Khamarbari</h3>
    </div>
    <ul>
      <li><a href="../index.php">Home</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="register.php">Sign Up</a></li>
    </ul>
  </nav>
</header>

<main>
  <div class="contact-container">
    <h2>Contact Us</h2>


    <form>
      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" required placeholder="">

      <label for="email">Your Email</label>
      <input type="email" id="email" name="email" required placeholder="">

      <label for="phone">Phone Number</label>
      <input type="text" id="phone" name="phone" placeholder="">

      <label for="subject">Subject</label>
      <input type="text" id="subject" name="subject" required placeholder="t">

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="5" required placeholder=""></textarea>

      <button type="submit" class="contact-btn">Send Message</button>
    </form>
  </div>
</main>

<footer>
  <div class="footer-container">
    <div class="footer-text">
      <div class="footer-logo">
        <h3>Khamarbari</h3>
      </div>
      <p>Khamarbari is a localized platform that connects farmers, suppliers, and consumers, making agriculture more accessible, efficient, and sustainable.</p>
    </div>
    <hr/>
    <div class="footer-copyright">
      <p>&copy; Khamarbari</p>
    </div>
  </div>
</footer>
</body>
</html>