<?php
require_once __DIR__ . "/../Model/db.php";
require_once __DIR__ . "/../Model/UserModel.php";

//

class AdminController {
    private $conn;
    private $userModel;
  
    

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->userModel    = new UserModel($this->conn);

    }

        
    

    public function loadSection($section) {
       switch($section) {
           case 'users':
              

            case 'products':
           


            case 'orders':
             

            case 'payments':
             

            case 'reviews':
              

            case 'queries':
              
         


              

        }
    }
}