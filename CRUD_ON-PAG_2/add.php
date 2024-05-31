<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "all.php";
    
    if (!empty($_FILES["tourImgg"]) && $_FILES['tourImgg']['error'] == UPLOAD_ERR_OK) {
  
        if (!empty($_POST["tourName"])) {
            $tourImgg = file_get_contents($_FILES['tourImgg']['tmp_name']);
            $tourName = filter($_POST["tourName"]);
            $tourPrice = filter($_POST["tourPrice"]);
            $tourDescription = filter($_POST["tourDescription"]);
            $touradddescription = filter($_POST["touradddescription"]);

            try {
                $con = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
                
                $insert = $con->prepare("INSERT INTO $tbname(tour_name, tour_img, tour_price, tour_description, tour_adddescription) VALUES(:tourName, :tourImgg, :tourPrice, :tourDescription, :touradddescription)");
                $insert->bindParam(":tourName", $tourName);
                $insert->bindParam(":tourPrice", $tourPrice);
                $insert->bindParam(":tourDescription", $tourDescription);
                $insert->bindParam(":touradddescription", $touradddescription); 
                $insert->bindParam(":tourImgg", $tourImgg, PDO::PARAM_LOB); 

                $insert->execute();
                echo "verified";
            } catch(PDOException $e) {
                echo "notverified";
            }
        } else {
            echo "tournameempty";
        }
    } else {
        echo "tourimgeempty";
    }
}
?>
