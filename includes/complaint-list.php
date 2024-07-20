<div class="col-12 col-md-6 col-lg-4 mb-3">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Meter #: <?php echo $row['meter_num']; ?></h5>
            <blockquote class="blockquote">
                <p class="mb-0"><?php echo $row['message']; ?></p>
                <footer class="blockquote-footer">
                    <small>
                        <?php echo $row['name']; ?> <cite title="Source Title"><?php echo date_format(date_create($row['date']), "Y/m/d H:i:s"); ?></cite>
                    </small>
                </footer>
            </blockquote>
            <?php
                if($row['is_resolved'] == 0){
                    ?>
                        <a href="complaint.php?id=<?php echo $row["complaint_id"]; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete-complaint.php?id=<?php echo $row["complaint_id"]; ?>" class="btn btn-sm btn-danger">Delete</a>
                    <?php
                }
            ?>
        </div>
    </div>
</div>