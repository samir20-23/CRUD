<?php
include "all.php";
try {
    $search = "";
    if (isset($_POST["search"])) {
        $search = $_POST['search'];
    }
    $con = new PDO($sql, $user, $pass);
    $select = $con->prepare("SELECT * FROM $tbname WHERE tour_id LIKE :search OR tour_name LIKE :search OR tour_description LIKE :search OR tour_adddescription LIKE :search");
    $select->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $select->execute();
    $fetchAll = $select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["edit"])) {
        try {
            $con = new PDO($sql, $user, $pass);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $tourId = filter($_POST["tourId"]);
            $tourName = filter($_POST["tourName"]);
            $tourPrice = filter($_POST["tourPrice"]);
            $tourDescription = filter($_POST["tourDescription"]);
            $tourAdddescription = filter($_POST["tourAdddescription"]);

            // Check if the image fields are set and not empty
            $tourImg = isset($_POST["tourimg"]) && !empty($_POST["tourimg"]) ? filter($_POST["tourimg"]) : null;
            $tourImg2 = isset($_POST["tourImg2"]) && !empty($_POST["tourImg2"]) ? filter($_POST["tourImg2"]) : null;
            $tourImg3 = isset($_POST["tourImg3"]) && !empty($_POST["tourImg3"]) ? filter($_POST["tourImg3"]) : null;
            $tourImg4 = isset($_POST["tourImg4"]) && !empty($_POST["tourImg4"]) ? filter($_POST["tourImg4"]) : null;

            $updateQuery = "UPDATE $tbname SET tour_name=:tourName, tour_price=:tourPrice, tour_description=:tourDescription, tour_adddescription=:tourAdddescription";
            
            // Append image fields to the update query only if they are not empty
            if ($tourImg !== null) {
                $updateQuery .= ", tour_img=:tourimg";
            }
            if ($tourImg2 !== null) {
                $updateQuery .= ", tour_img2=:tourImg2";
            }
            if ($tourImg3 !== null) {
                $updateQuery .= ", tour_img3=:tourImg3";
            }
            if ($tourImg4 !== null) {
                $updateQuery .= ", tour_img4=:tourImg4";
            }

            $updateQuery .= " WHERE tour_id=:tourId";
            
            $update = $con->prepare($updateQuery);
            $update->bindParam(":tourId", $tourId);
            $update->bindParam(":tourName", $tourName);
            $update->bindParam(":tourPrice", $tourPrice);
            $update->bindParam(":tourDescription", $tourDescription);
            $update->bindParam(":tourAdddescription", $tourAdddescription);
            
            // Bind image parameters only if they are not empty
            if ($tourImg !== null) {
                $update->bindParam(":tourimg", $tourImg);
            }
            if ($tourImg2 !== null) {
                $update->bindParam(":tourImg2", $tourImg2);
            }
            if ($tourImg3 !== null) {
                $update->bindParam(":tourImg3", $tourImg3);
            }
            if ($tourImg4 !== null) {
                $update->bindParam(":tourImg4", $tourImg4);
            }
            
            $update->execute();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    foreach ($fetchAll as $v) {
        if (isset($_POST["delete" . $v["tour_id"]])) {
            try {
                $con = new PDO($sql, $user, $pass);
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $delete = $con->prepare("DELETE FROM $tbname WHERE tour_id=:tourId");
                $delete->bindParam(":tourId", $v["tour_id"]);
                $delete->execute();

                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="croad_TOUR.css">
    <title>Document</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }
        tr {
            border: 2px solid black;
        }
        th, td {
            border: 2px solid black;
            text-align: center;
        }
        input {
            text-align: center;
        }
        p {
            color: red;
            text-shadow: 0 0 12px red;
        }
        #allcrodtableselecte {
            background: rgb(72, 252, 13, 0.5);
        }
        img {
            width: 100px;
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="search">
        <input type="submit" name="submit" value="Search">
    </form>
    <div id="allcrodtableselecte">
        <table>
            <tr>
                <th>tour_id</th>
                <th id='tour_name'>tour_name</th>
                <th id="tour_price">tour_price</th>
                <th id="tour_description">tour_description</th>
                <th id="tour_adddescription">tour_adddescription</th>
                <th id="tour_img">tour_img</th>
                <th id="tour_img2">tour_img2</th>
                <th id="tour_img3">tour_img3</th>
                <th id="tour_img4">tour_img4</th>
                <th id="edit">EDIT</th>
                <th id="delete">DELETE</th>
                <th id="add"><a href="add.html">ADD</a></th>
            </tr>
            <?php if (!empty($fetchAll)) : ?>
                <?php foreach ($fetchAll as $v) : ?>
                    <tr>
                        <form action="" method="post">
                            <td><input type="text" name="tourId" value="<?php echo $v['tour_id']; ?>"></td>
                            <td><input type="text" id="tourName" name="tourName" value="<?php echo $v['tour_name']; ?>"></td>
                           
         <td><input type="text" id="tourPrice" name="tourPrice" value="<?php echo $v['tour_price']; ?>"></td>
                            <td><input type="text" id="tourDescription" name="tourDescription" value="<?php echo $v['tour_description']; ?>"></td>
                            <td><input type="text" id="tourAdddescription" name="tourAdddescription" value="<?php echo $v['tour_adddescription']; ?>"></td>
                            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($v['tour_img']); ?>" alt="Tour Image" id="tourimg" name="tourimg"></td>
                            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($v['tour_img2']); ?>" alt="Tour Image" id="tourImg2" name="tourImg2"></td>
                            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($v['tour_img3']); ?>" alt="Tour Image" id="tourImg3" name="tourImg3"></td>
                            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($v['tour_img4']); ?>" alt="Tour Image" id="tourImg4" name="tourImg4"></td>
                            <td><input type="submit" name="edit" id="edit" value="Edit"></td>
                            <td><input type="submit" name="delete<?php echo $v['tour_id']; ?>" id="delete" value="Delete"></td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
