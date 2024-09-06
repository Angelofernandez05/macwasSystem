<?php 

if(isset($_GET['id'])){
    $id = $_GET['id'];
}

if(isset($_POST["create"])) {
    $ref = $_POST['ref'];

    $shot = $_FILES['gcash_shot']['name'];
    $target_shot = "../uploads/".basename($shot);
    
    $sql = "INSERT INTO readings (ref, shot, fee) VALUES ('$ref', '$shot', '0')";


    if ($dbconnection->query($sql) === TRUE) {
        echo '<script type="text/javascript">alert("Successfully Created Advertisement, Wait the admin to approve your advertisement");</script>';
        move_uploaded_file($_FILES['gcash_shot']['tmp_name'], $target_shot);


        // Upload files and store in database
		if(move_uploaded_file($_FILES["gcash_shot"]["tmp_name"][$i],'../uploads/'.$filename)){
            // Image db insert sql
            $insert = "INSERT into shot (file_name,readings) values('$filename','$readings')";
            mysqli_query($dbconnection, $insert);
     }

    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title> 
    <?php include 'includes/links.php'; ?>
     <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link rel="icon" href="logo.png" type="image/icon type">
</head>
<body class="bg-light">
    <section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="background-color: lightblue";>
            <div class="card-body p-md-2">
              <div class="row justify-content-center">

    <div class="container pt-5">
        <!-- <?php include 'includes/bill-template.php'; ?> -->

        <div class="text-center">
            <img class="img-fluid" src="logo.png" alt="" width=150>
            <p class="text-uppercase text-center mb-0">madridejos community waterworks system</p>
            <p class="text-uppercase text-center">
                <small class="text-muted">municipality of madridejos</small><br />
                <small class="text-muted">madridejos, cebu</small>
            </p>
        </div>
       
        <h3>GCASH DETAILS</h3>
     
<br/>

<h6>GCASH NAME: SAMUEL U. MULLE JR.</h6>
<h6>GCASH NUMBER: 09309631219 </h6>

<br/>
<br/>
<form action="upload.php" method="POST" enctype="multipart/form-data">
<div class="row">
  <div class="col">
    <input type="hidden" name="id" value="<?php echo $id ?>">
     <div class="form-group">
    <label><h4>Reference Number</h4></label>
    <input class="form-control" type="text" name="ref">
  </div>
  </div>
  <div class="col">
      <div class="form-group">
    <label><h4>Screenshot</h4></label>
    <input class="form-control" type="file" name="image" >
  </div>
  </div>
</div>
<br/>
<br/>
<br/>
<br/>
<br/>

<button type="submit" name="image" class="btn btn-danger" href="reading.php"><i class="fa fa-plus-circle" aria-hidden="true"></i> SUBMIT</button>
</form>

 <!-- Bootstrap JavaScript Libraries -->
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>
</html>