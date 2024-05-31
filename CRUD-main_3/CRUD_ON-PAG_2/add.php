
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "all.php";
    


    $tourImgg = $tourImgg2 =$tourImgg3 =$tourImgg4 = "";

if (!empty($_FILES["tourImgg"]) && $_FILES['tourImgg']['error'] == UPLOAD_ERR_OK ) {

  
if (!empty($_POST["tourName"])) {

    if (!empty($_FILES["tourImgg2"]) && $_FILES['tourImgg2']['error'] == UPLOAD_ERR_OK ) {
         $tourImgg2 = file_get_contents($_FILES['tourImgg2']['tmp_name']);}

    if (!empty($_FILES["tourImgg3"]) && $_FILES['tourImgg3']['error'] == UPLOAD_ERR_OK ) {
         $tourImgg3 = file_get_contents($_FILES['tourImgg3']['tmp_name']);}

    if (!empty($_FILES["tourImgg4"]) && $_FILES['tourImgg4']['error'] == UPLOAD_ERR_OK ) {
         $tourImgg4 = file_get_contents($_FILES['tourImgg4']['tmp_name']);}
        


            $tourImgg = file_get_contents($_FILES['tourImgg']['tmp_name']);
            // IMGES
            $tourName = filter($_POST["tourName"]);
            $tourPrice = filter($_POST["tourPrice"]);
            $tourDescription = filter($_POST["tourDescription"]);
            $touradddescription = filter($_POST["touradddescription"]);

            try {
                $con = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
                
                $insert = $con->prepare("INSERT INTO $tbname(tour_name, tour_price, tour_description, tour_adddescription, tour_img ,tour_img2 ,tour_img3 ,tour_img4) VALUES(:tourName, :tourPrice, :tourDescription, :touradddescription, :tourImgg , :tourImgg2,:tourImgg3,:tourImgg4)");
                $insert->bindParam(":tourName", $tourName);
                $insert->bindParam(":tourPrice", $tourPrice);
                $insert->bindParam(":tourDescription", $tourDescription);
                $insert->bindParam(":touradddescription", $touradddescription); 
                $insert->bindParam(":tourImgg", $tourImgg, PDO::PARAM_LOB); 
                $insert->bindParam(":tourImgg2", $tourImgg2, PDO::PARAM_LOB); 
                $insert->bindParam(":tourImgg3", $tourImgg3, PDO::PARAM_LOB); 
                $insert->bindParam(":tourImgg4", $tourImgg4, PDO::PARAM_LOB); 


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
