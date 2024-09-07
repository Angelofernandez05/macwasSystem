<div class="col-12 col-md-6 col-lg-4 mb-3">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Meter #: <?php echo htmlspecialchars($row['meter_num']); ?></h5>
            <blockquote class="blockquote">
                <p class="mb-0"><?php echo htmlspecialchars($row['message']); ?></p>
                <footer class="blockquote-footer">
                    <small>
                        <?php echo htmlspecialchars($row['name']); ?> <cite title="Source Title"><?php echo date_format(date_create($row['date']), "Y/m/d H:i:s"); ?></cite>
                    </small>
                </footer>
            </blockquote>
            <?php
                if ($row['is_resolved'] == 0) {
                    echo '<a href="mark-as-resolved.php?id=' . htmlspecialchars($row['complaint_id']) . '" class="btn btn-sm btn-primary">Mark as resolved</a>';
                } elseif ($row['is_resolved'] == 1) {
                    // echo '<form action="archive_complaint.php" method="POST" style="display:inline;">
                    //     <input type="hidden" name="complaint_id" value="' . htmlspecialchars($row['complaint_id']) . '">
                    //     <button type="submit" class="btn btn-sm btn-danger">Archive</button>
                    // </form>';
                }
            ?>
            <form action="delete_complaint.php" method="POST" style="display:inline;">
                <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars($row['complaint_id']); ?>">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
