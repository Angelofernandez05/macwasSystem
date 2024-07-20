<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group">
        <label>Type</label>
        <input required type="text" name="type" class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $type; ?>">
        <span class="invalid-feedback"><?php echo $type_err;?></span>
    </div>
    <div class="form-group">
        <label>Rate X</label>
        <input required type="text" name="ratex" class="form-control <?php echo (!empty($ratex_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ratex; ?>">
        <span class="invalid-feedback"><?php echo $ratex_err;?></span>
    </div>
    <div class="form-group">
        <label>Rate Y</label>
        <input required type="text" name="ratey" class="form-control <?php echo (!empty($ratey_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ratey; ?>">
        <span class="invalid-feedback"><?php echo $ratey_err;?></span>
    </div>
    <div class="form-group">
        <label>Rate Z</label>
        <input required type="text" name="ratez" class="form-control <?php echo (!empty($ratez_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ratez; ?>">
        <span class="invalid-feedback"><?php echo $ratez_err;?></span>
    </div>
    <?php
        if(isset($id) && !empty($id)){
            echo '<input type="hidden" name="id" value="'.$id.'"/>';
        }
    ?>
    <input type="submit" class="btn btn-primary" value="Submit">
    <a href="settings.php" class="btn btn-secondary ml-2">Cancel</a>
</form>