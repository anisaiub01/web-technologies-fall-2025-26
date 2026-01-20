<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khamarbari</title>
    <link rel="stylesheet" href="public/style.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <nav>
        <div class="nav-logo">
            <h3 class="nav-title"><span>Khamarbari</span></h3>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="View/about.php">About</a></li>
            <li><a href="View/contact.php">Contact</a></li>
            <li><a href="View/register.php">Sign Up</a></li>
        </ul>
    </nav>
</header>

<section class="banner">
    <div class="banner-content">
        <p class="welcome">WELCOME TO Khamarbari, a place of organic taste</p>
        <h1>Khamarbari</h1>
        <p class="tagline">Empowering rural dreams, nurturing agricultural growth from the roots up</p>
        <div class="buttons">
            <a href="#" class="btn buy-btn">Buy Here</a>
            <a href="#" class="btn sell-btn">Sell Here</a>
        </div>
    </div>
</section>

<main>
    <section class="products-section">
        <div class="section-header">
            <h2>Featured Products</h2>
            <p>Fresh products directly from farmers</p>
        </div>
        
        <div class="product-grid">
            <div class="product-card">
                <img src="https://cdn.britannica.com/68/143268-050-917048EA/Beef-loin.jpg" alt="Fresh Meat">
                <h3>Meat</h3>
                <p>Premium quality meat from local farms</p>
            </div>

            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e" alt="Fresh Vegetables">
                <h3>Vegetables</h3>
                <p>Organic vegetables grown with care</p>
            </div>

            <div class="product-card">
                <img src="https://acemarket.ph/cdn/shop/files/051524-272_JOLLY-COW_PACKAGING-UPDATE_BLUE-CAP_FRESH-MILK.png?v=1740979406&width=1946" alt="Fresh Milk">
                <h3>Milk</h3>
                <p>Pure milk from healthy dairy farms</p>
            </div>
        </div>
    </section>

    <section class="why-section">
        <div class="section-header">
            <h2>Why Choose Khamarbari?</h2>
            <p>We are committed to supporting farmers and serving fresh food to you</p>
        </div>
        
        <div class="feature-grid">
            <div class="feature-card">
                <h3>Organic & Fresh</h3>
                <p>Directly sourced from local farmers ensuring natural freshness</p>
            </div>

            <div class="feature-card">
                <h3>Fast Delivery</h3>
                <p>Quick and safe delivery to your doorstep</p>
            </div>

            <div class="feature-card">
                <h3>Fair Trade</h3>
                <p>Empowering farmers with fair prices and better opportunities</p>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="footer-container">
        <div class="footer-text">
            <div class="footer-logo">
                <h3><span>Khamarbari</span></h3>
            </div>
            <p>Khamarbari is a localized platform that connects farmers, suppliers, and consumers, making agriculture more accessible, efficient, and sustainable.</p>
        </div>
        <hr/>
        <div class="footer-copyright">
            <p>&copy; 2025 Khamarbari. All Rights Reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>