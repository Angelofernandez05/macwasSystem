<?php
// Define variables and initialize with empty values
$message = "";
$message_err = "";

$url = htmlspecialchars($_SERVER["PHP_SELF"]);
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $message = trim($_POST["message"]);
    $consumer_id = $_SESSION["id"];
    
    // Check input errors before inserting in database
    if(empty($message_err)){
        // Prepare an insert statement
        if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
            $sql = "UPDATE complaints SET message=?, consumer_id=? WHERE id=?";
        }else{
            $sql = "INSERT INTO complaints (message, consumer_id) VALUES (?, ?)";
        }
         
        if($stmt2 = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
                $id =  trim($_GET["id"]);
                mysqli_stmt_bind_param($stmt2, "ssi", $message, $consumer_id, $id);
            }else{
                mysqli_stmt_bind_param($stmt2, "ss", $message, $consumer_id);
            }
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt2)){
                // Records created successfully. Redirect to landing page
                header("location: complaint.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt2);
    }
    
    // Close connection
    // mysqli_close($link);
}else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $url = htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET["id"];
        
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM complaints WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $message = $row["message"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: complaint.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
}
?>

<form action="<?php echo $url; ?>" method="post">
    <div class="form-group">
        <label>Message</label>
        <textarea rows="6" required name="message" class="form-control <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>"><?php echo $message; ?></textarea>
        <span class="invalid-feedback"><?php echo $message_err;?></span>
    </div>
    <input type="submit" class="btn btn-block btn-primary" value="<?php echo isset($_GET["id"]) && !empty($_GET["id"]) ? 'Update' : 'Submit' ?>">
    <?php
        if(isset($_GET["id"]) && !empty($_GET["id"])){
            ?>
                <a class="btn btn-link btn-block" href="complaint.php">Cancel</a>
            <?php
        }
    ?>
</form>