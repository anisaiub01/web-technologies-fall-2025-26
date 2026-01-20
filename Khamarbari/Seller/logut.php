<?php
session_start();
session_unset();
session_destroy();


header("Location: /web-technologies-fall-2025-26/Khamarbari/Seller/main.php?page=shop");
exit;