
<?php 
require_once ('db.php');

if(isset($_POST['image']))
{

$date_paid = date('Y-m-d');


$ref = $_POST['ref'];

move_uploaded_file($_FILES["image"]["tmp_name"],"uploads/" . $_FILES["image"]["name"]);         
$location1=$_FILES["image"]["name"];

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = '86';

$picture = "Picture Done";
$sql = "UPDATE readings SET ref ='$ref', shot = '$location1', date_paid = '$date_paid' WHERE id = '$id' ";

$conn->exec($sql);
$query_run = mysqli_query($conn, $sql);
if($query_run)
    {
        header('Location: reading.php');
    }
    else
    {
        header('Location: reading.php');        
    }

}


?>