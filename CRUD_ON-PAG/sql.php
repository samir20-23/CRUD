
<!--                                                                                 -->

<?php
include "all.php";
try{
    $search="";
if(isset($_POST["search"])){
    $search = $_POST['search'];
}
$con =new PDO("$sql",$user,$pass);
$select = $con->prepare("SELECT * from $tbname  WHERE id LIKE '$search%' OR username LIKE '$search%' OR email LIKE '$search%' ");
$select->execute();

 $fetchAll = $select->fetchAll();


}catch(PDOException $e){
    echo "Error 404";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            display: flex;
            justify-content: center;
            flex-direction:column;
            align-items: center;
        }
        tr{
            border: 2px solid black;
        }
        th , td{
            border: 2px solid black;
            text-align: center;
        }
        input{
            text-align: center;
        }
        p{
            color: red;
            text-shadow:  0 0 12px red;
        }
    </style>
</head>
<body>
   
    <form action="" method='POST'>
         <input type="text" name="search" >
         <input type="submit" name="submit" value='serch'>
    </form>
    <table>
        <tr>

            <th>ID</th><th id='username' >USER NAME</th><th id="email" >EMAIL</th><th id="password" >PASSWORD</th><th id="edit" >EDIT</th><th id="delete" >DELETE</th><th id="add"><a href="add.html">ADD</a></th>
        </tr>
        <?php 
        
        foreach($fetchAll as $v){
        ?>
     
        <tr>
            <form action="" method="post">
                
            <td><input type="text" name="id" value=" <?php echo $v["id"]; ?>"></td>
            <td><input type="text"  name="username" value="<?php echo $v["username"]; ?>"> </td>
            <td><input type="text"  name="email" value="<?php echo $v["email"]; ?>"> </td>
            <td><input type="text"  name="password" value=" <?php echo $v["password"]; ?>"></td>
            <td><input type="submit" name="edit" id="editsubmit" value='edit'></td>
            <td><input type="submit" name="delete<?php echo $v["id"]; ?>" id="deletesubmit" value='delete'></td>
            </form>
        </tr>

        <?php 
        
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(!empty($_POST["username"])){
       $username =filter($_POST["username"]);

         if(!empty($_POST["email"])){
            $email =filter($_POST["email"]);

            if(!empty($_POST["password"])){
               $password =filter($_POST["password"]); 
                try{
                    $connect =new PDO("$sql",$user,$pass);
                    $connect->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
            
            
                    $selected = $connect->prepare("SELECT email FROM $tbname WHERE email='$email'");
                    $selected->execute();
                    $slt = $selected->fetch();

                 
                 

                    if ($slt && $email == $slt['email']) {echo "<script>document.getElementById('email').style.background='#93BCFF'</script>";} else {echo "<script>document.getElementById('edit').style.background='green'</script>";}
                    
                    if(!filter_var($_POST["email"],FILTER_VALIDATE_EMAIL)){echo "<script>document.getElementById('email').style.background='#FF9393'</script>";}else{

                        echo "<script>document.getElementById('edit').style.background='green'</script>";
                                /////////////////////////////
                                if(isset($_POST["edit"])){
                                    $id =filter($_POST["id"]);
                                    $username =filter($_POST["username"]);
                                    $email =filter($_POST["email"]);
                                    $password =filter($_POST["password"]); 
                                    echo "<script>document.getElementById('edit').style.background='green'</script>";
                                    $update = $con->prepare("UPDATE $tbname SET username=:username, email=:email, password=:password WHERE id=:id");
                                    $update->bindParam(":id", $id);
                                    $update->bindParam(":username", $username);
                                    $update->bindParam(":email", $email);
                                    $update->bindParam(":password", $password);
                                    $update->execute();
                                    echo "<script>document.getElementById('edit').style.background='green'</script>";
                                    header("Location: ".$_SERVER['PHP_SELF']);
                                   
                                }else{echo "<script>document.getElementById('edit').style.background='#FF9393'</script> ";}
                                $id = $v["id"];
                                if(isset($_POST["delete$id"])){
                                    
                                    $delete = $con->prepare("DELETE FROM $tbname WHERE id='$id' ");
                                    $delete->execute();
                                    header("Location:".$_SERVER['PHP_SELF']);
                                
                                
                               /////////////////////////////
                            
                        }else{echo "<script>document.getElementById('delete').style.background='#FF9393'</script> ";}
                    }
                    
            
            
                }catch(Exception $e){echo "<p>*</p>".$e->getMessage();}
            
            }else{echo "<script>document.getElementById('password').style.background='#FF9393'</script> ";}
         }else{echo "<script>document.getElementById('email').style.background='#FF9393'</script> ";}
    }else{echo "<script>document.getElementById('username').style.background='#FF9393'</script> ";}
   
   
}
}
?>
    </table>
</body>
</html>