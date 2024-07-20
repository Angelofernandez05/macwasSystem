<?php
$id =  trim($_GET["id"]);

if(isset($_POST["id"]) && !empty($_POST["id"])){
    require_once "config.php";

    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            $sql2 = "UPDATE readings SET ref=?, shot=?, status=? WHERE id=?";
        
            if($stmt2 = mysqli_prepare($link, $sql2)){
                $id =  trim($_POST["id"]);
                mysqli_stmt_bind_param($stmt2, "sssi", $param_ref, $param_filename, $param_status, $id);
                
                // Set parameters
                $param_ref = $_POST["ref"];
                $param_status = 2;
                $param_filename = str_replace(' ', '_', $filename);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt2)){
                    move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $filename);
                    header("location: reading.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
                
            // Close statement
            mysqli_stmt_close($stmt2);

            
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } else{
        echo "Error: " . $_FILES["photo"]["error"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title> 

     <!-- Bootstrap CSS v5.2.1 -->
     <link rel="stylesheet" href="harry.css">
      <link rel="icon" href="logo.png" type="image/icon type">
</head>
<div class="container">

<div class="card-container">

    <div class="front">
        <div class="image">
            <img src="image/gcashtext.png" alt="">
            <img src="image/gcashlogo.png" alt="">
            
            
        </div>
        <div class="card-number-box">

        </div>
        <div class="flexbox">
            <div class="box">
                <h2>GCASH Details:</h2>
                <span>ROQUE C. ZAPA JR.</span>
                <div class="card-holder-name">09309631219</div>
            </div>
        </div>
    </div>


</div>

<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $id ?>">
    <div class="inputBox">
        <span>Reference Number</span>
        <input class="form-control" type="text" name="ref" maxlength="13">
    </div>
    <div class="inputBox">
        <span>Screenshot</span>
        <input class="form-control" type="file" name="photo">
    </div>
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
    <button type="submit" class="submit-btn">SUBMIT</button>
</form>

</div>    

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>
</html>