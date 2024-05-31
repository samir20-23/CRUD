<?php
include "all.php";

try {
    $search = isset($_POST["search"]) ? $_POST['search'] : '';

    $con = new PDO($sql, $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["edit"])) {
            $tourId = $_POST["tourId"];
            $tourName = $_POST["tourName"];
            $tourPrice = $_POST["tourPrice"];
            $tourDescription = $_POST["tourDescription"];
            $tourAdddescription = $_POST["tourAdddescription"];

            // Handle image updates
            $tourImg = !empty($_FILES["tourimg"]["tmp_name"]) ? file_get_contents($_FILES["tourimg"]["tmp_name"]) : null;
            $tourImg2 = !empty($_FILES["tourImg2"]["tmp_name"]) ? file_get_contents($_FILES["tourImg2"]["tmp_name"]) : null;
            $tourImg3 = !empty($_FILES["tourImg3"]["tmp_name"]) ? file_get_contents($_FILES["tourImg3"]["tmp_name"]) : null;
            $tourImg4 = !empty($_FILES["tourImg4"]["tmp_name"]) ? file_get_contents($_FILES["tourImg4"]["tmp_name"]) : null;

            $update = $con->prepare("UPDATE $tbname SET tour_name=:tourName, tour_price=:tourPrice, tour_description=:tourDescription, tour_adddescription=:tourAdddescription, tour_img=:tourImg, tour_img2=:tourImg2, tour_img3=:tourImg3, tour_img4=:tourImg4 WHERE tour_id=:tourId");
            $update->bindParam(":tourId", $tourId);
            $update->bindParam(":tourName", $tourName);
            $update->bindParam(":tourPrice", $tourPrice);
            $update->bindParam(":tourDescription", $tourDescription);
            $update->bindParam(":tourAdddescription", $tourAdddescription);
            $update->bindParam(":tourImg", $tourImg, PDO::PARAM_LOB);
            $update->bindParam(":tourImg2", $tourImg2, PDO::PARAM_LOB);
            $update->bindParam(":tourImg3", $tourImg3, PDO::PARAM_LOB);
            $update->bindParam(":tourImg4", $tourImg4, PDO::PARAM_LOB);
            $update->execute();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit(); // Ensure no further code is executed
        }

        foreach ($fetchAll as $v) {
            $tourIdd = $v["tour_id"];
            if (isset($_POST["delete$tourIdd"])) {
                $delete = $con->prepare("DELETE FROM $tbname WHERE tour_id=:tourId");
                $delete->bindParam(":tourId", $tourIdd);
                $delete->execute();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit(); // Ensure no further code is executed
            }
        }
    }

    $query = "SELECT * FROM $tbname WHERE tour_id LIKE :search OR tour_name LIKE :search OR tour_description LIKE :search OR tour_adddescription LIKE :search";
    $select = $con->prepare($query);
    $select->execute([':search' => "%$search%"]);

    $fetchAll = $select->fetchAll();
} catch (PDOException $e) {
    echo "Error 404: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }
        tr, th, td {
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
            background: rgba(72, 252, 13, 0.5);
        }
        img {
            width: 100px;
        }
    </style>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="search">
        <input type="submit" name="submit" value="search">
    </form>
    <div id="allcrodtableselecte">
        <table>
            <tr>
                <th>tour_id</th>
                <th>tour_name</th>
                <th>tour_price</th>
                <th>tour_description</th>
                <th>tour_adddescription</th>
                <th>tour_img</th>
                <th>tour_img2</th>
                <th>tour_img3</th>
                <th>tour_img4</th>
                <th>EDIT</th>
                <th>DELETE</th>
                <th><a href="add.html">ADD</a></th>
            </tr>
            <?php foreach ($fetchAll as $v): ?>
                <tr>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <td><input type="text" name="tourId" value="<?php echo htmlspecialchars($v['tour_id']); ?>"></td>
                        <td><input type="text" name="tourName" value="<?php echo htmlspecialchars($v['tour_name']); ?>"></td>
                        <td><input type="text" name="tourPrice" value="<?php echo htmlspecialchars($v['tour_price']); ?>"></td>
                        <td><input type="text" name="tourDescription" value="<?php echo htmlspecialchars($v['tour_description']); ?>"></td>
                        <td><input type="text" name="tourAdddescription" value="<?php echo htmlspecialchars($v['tour_adddescription']); ?>"></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($v['tour_img']); ?>" alt="Tour Image"><input type="file" name="tourimg"></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($v['tour_img2']); ?>" alt="Tour Image"><input type="file" name="tourImg2"></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($v['tour_img3']); ?>" alt="Tour Image"><input type="file" name="tourImg3"></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($v['tour_img4']); ?>" alt="Tour Image"><input type="file" name="tourImg4"></td>
                        <td><input type="submit" name="edit" value="edit"></td>
                        <td><input type="submit" name="delete<?php echo $v['tour_id']; ?>" value="delete"></td>
                    </form>
                    
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
<!-- ############## -->
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
            $tourImg = isset($_POST["tourimg"]) ? filter($_POST["tourimg"]) : null;
            $tourImg2 = isset($_POST["tourImg2"]) ? filter($_POST["tourImg2"]) : null;
            $tourImg3 = isset($_POST["tourImg3"]) ? filter($_POST["tourImg3"]) : null;
            $tourImg4 = isset($_POST["tourImg4"]) ? filter($_POST["tourImg4"]) : null;

            $update = $con->prepare("UPDATE $tbname SET tour_name=:tourName, tour_price=:tourPrice, tour_description=:tourDescription, tour_adddescription=:tourAdddescription, tour_img=:tourimg, tour_img2=:tourImg2, tour_img3=:tourImg3, tour_img4=:tourImg4 WHERE tour_id=:tourId");
            $update->bindParam(":tourId", $tourId);
            $update->bindParam(":tourName", $tourName);
            $update->bindParam(":tourPrice", $tourPrice);
            $update->bindParam(":tourDescription", $tourDescription);
            $update->bindParam(":tourAdddescription", $tourAdddescription);
            $update->bindParam(":tourimg", $tourImg);
            $update->bindParam(":tourImg2", $tourImg2);
            $update->bindParam(":tourImg3", $tourImg3);
            $update->bindParam(":tourImg4", $tourImg4);
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
                <th id="xx">xx</th>
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
<!-- ###########################################* -->
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

            // Check if image files are provided and handle uploads
            $uploadDir = "uploads/";
            $uploadFiles = [];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            for ($i = 1; $i <= 4; $i++) {
                $fileInputName = "tourImg$i";
                if (!empty($_FILES[$fileInputName]['name']) && in_array($_FILES[$fileInputName]['type'], $allowedTypes)) {
                    $uploadFile = $uploadDir . basename($_FILES[$fileInputName]['name']);
                    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadFile)) {
                        $uploadFiles[$fileInputName] = $uploadFile;
                    } else {
                        // Handle file upload error
                    }
                }
            }

            // Update query
            $updateQuery = "UPDATE $tbname SET tour_name=:tourName, tour_price=:tourPrice, tour_description=:tourDescription, tour_adddescription=:tourAdddescription";
            foreach ($uploadFiles as $fileInputName => $uploadFile) {
                $columnName = "tour_img$i";
                $updateQuery .= ", $columnName=:uploadFile";
            }
            $updateQuery .= " WHERE tour_id=:tourId";

            $update = $con->prepare($updateQuery);
            $update->bindParam(":tourId", $tourId);
            $update->bindParam(":tourName", $tourName);
            $update->bindParam(":tourPrice", $tourPrice);
            $update->bindParam(":tourDescription", $tourDescription);
            $update->bindParam(":tourAdddescription", $tourAdddescription);
            foreach ($uploadFiles as $fileInputName => $uploadFile) {
                $update->bindParam(":uploadFile", $uploadFile);
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
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="search">
        <input type="submit" name="submit" value="Search">
    </form>
    <div id="allcrodtableselecte">
        <table>
            <tr>
                <th>tour_id</th>
                <th id='tour_name'>tour_name</th>
                < id="tour_price">tour_price</    th>
                <th id="tour_description">tour_description</th>
                <th id="tour_adddescription">tour_adddescription</th>
                <th id="tour_img">tour_img</th>
                <th id="tour_img2">tour_img2</th>
                <th id="tour_img3">tour_img3</th>
                <th id="tour_img4">tour_img4</th>
                <th id="xx">xx</th>
                <th id="edit">EDIT</th>
                <th id="delete">DELETE</th>
                <th id="add"><a href="add.html">ADD</a></th>
            </tr>
            <?php if (!empty($fetchAll)) : ?>
                <?php foreach ($fetchAll as $v) : ?>
                    <tr>
                    <form action="" method="post" enctype="multipart/form-data">
    <td><input type="text" name="tourId" value="<?php echo $v['tour_id']; ?>"></td>
    <td><input type="text" id="tourName" name="tourName" value="<?php echo $v['tour_name']; ?>"></td>
    <td><input type="text" id="tourPrice" name="tourPrice" value="<?php echo $v['tour_price']; ?>"></td>
    <td><input type="text" id="tourDescription" name="tourDescription" value="<?php echo $v['tour_description']; ?>"></td>
    <td><input type="text" id="tourAdddescription" name="tourAdddescription" value="<?php echo $v['tour_adddescription']; ?>"></td>
    <td><input type="file" name="tourImg1"></td>
    <td><input type="file" name="tourImg2"></td>
    <!-- Add file input fields for tourImg3 and tourImg4 here -->
    <td><input type="submit" name="edit" value="Edit"></td>
    <td><input type="submit" name="delete<?php echo $v['tour_id']; ?>" value="Delete"></td>
</form>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>

<!-- mmohim bzaf  -->
