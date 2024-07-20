<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group">
        <label>Name</label>
        <input required type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
        <span class="invalid-feedback"><?php echo $name_err;?></span>
    </div>
    <div class="form-group">
        <label>Email <small class="text-muted">(optional)</small></label>
        <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
        <span class="invalid-feedback"><?php echo $email_err;?></span>
    </div>
    <div class="form-group">
        <label>Contact No. <small class="text-muted">(9123456789)</small></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="phone">+63</span>
            </div>
            <input required pattern="[9][0-9]{9}" maxlength="10" aria-describedby="phone" required type="text" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
            <span class="invalid-feedback"><?php echo $phone_err;?></span>
        </div>
    </div>
    <div class="form-group">
        <label>Barangay</label>
        <select required name="barangay" class="form-control <?php echo (!empty($barangay_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $barangay; ?>">
            <option <?php echo $barangay == "" ? "selected" : ""; ?> value="">Select Brgy</option>
            <option <?php echo $barangay == "Bunakan" ? "selected" : ""; ?> value="Bunakan">Bunakan</option>
            <option <?php echo $barangay == "Kangwayan" ? "selected" : ""; ?> value="Kangwayan">Kangwayan</option>
            <option <?php echo $barangay == "Kaongkod" ? "selected" : ""; ?> value="Kaongkod">Kaongkod</option>
            <option <?php echo $barangay == "Kodia" ? "selected" : ""; ?> value="Kodia">Kodia</option>
            <option <?php echo $barangay == "Maalat" ? "selected" : ""; ?> value="Maalat">Maalat</option>
            <option <?php echo $barangay == "Malbago" ? "selected" : ""; ?> value="Malbago">Malbago</option>
            <option <?php echo $barangay == "Mancilang" ? "selected" : ""; ?> value="Mancilang">Mancilang</option>
            <option <?php echo $barangay == "Pili" ? "selected" : ""; ?> value="Pili">Pili</option>
            <option <?php echo $barangay == "Poblacion" ? "selected" : ""; ?> value="Poblacion">Poblacion</option>
            <option <?php echo $barangay == "San Agustin" ? "selected" : ""; ?> value="San Agustin">San Agustin</option>
            <option <?php echo $barangay == "Tabagak" ? "selected" : ""; ?> value="Tabagak">Tabagak</option>
            <option <?php echo $barangay == "Talangnan" ? "selected" : ""; ?> value="Talangnan">Talangnan</option>
            <option <?php echo $barangay == "Tarong" ? "selected" : ""; ?> value="Tarong">Tarong</option>
            <option <?php echo $barangay == "Tugas" ? "selected" : ""; ?> value="Tugas">Tugas</option>
        </select>
        <span class="invalid-feedback"><?php echo $barangay_err;?></span>
    </div>
    <div class="form-group">
        <label>Account No.</label>
        <input required type="text" name="account_num" class="form-control <?php echo (!empty($account_num_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $account_num; ?>">
        <span class="invalid-feedback"><?php echo $account_num_err;?></span>
    </div>
    <div class="form-group">
        <label>Registration No.</label>
        <input required type="text" name="registration_num" class="form-control <?php echo (!empty($registration_num_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $registration_num; ?>">
        <span class="invalid-feedback"><?php echo $registration_num_err;?></span>
    </div>
    <div class="form-group">
        <label>Meter No.</label>
        <input required type="text" name="meter_num" class="form-control <?php echo (!empty($meter_num_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $meter_num; ?>">
        <span class="invalid-feedback"><?php echo $meter_num_err;?></span>
    </div>
    <div class="form-group">
        <label>Type</label>
        <select required name="type" class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $type; ?>">
            <option <?php echo $type == "" ? "selected" : ""; ?> value="">Select Type</option>
            <option <?php echo $type == "Commercial" ? "selected" : ""; ?> value="Commercial">Commercial</option>
            <option <?php echo $type == "Residential" ? "selected" : ""; ?> value="Residential">Residential</option>
        </select>
        <span class="invalid-feedback"><?php echo $type_err;?></span>
    </div>
    <?php
        if(isset($id) && !empty($id)){
            echo '<input type="hidden" name="id" value="'.$id.'"/>';
        }
    ?>
    <input type="submit" class="btn btn-primary" value="Submit">
    <a href="consumer.php" class="btn btn-secondary ml-2">Cancel</a>
</form>