<div class="sidebar bg-light border-right close">
        <div class="m-auto">
            <img class="img-fluid" src="logo.png" alt="">
            <p class="text-uppercase text-center mb-0">madridejos community waterworks system</p>
            <p class="text-uppercase text-center">
                <small class="text-muted">municipality of madridejos</small><br />
                <small class="text-muted">madridejos, cebu</small>
            </p>
        </div>

        <ul class="nav-links">
            <li>
                <a href="index.php">
                    <i class='bx bx-grid-alt' ></i>
                    <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="index.php">Dashboard</a></li>
                </ul>
            </li>
            <li>
                <a href="consumer.php">
                    <i class='bx bx-user'></i>
                    <span class="link_name">Consumers</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="consumer.php">Consumers</a></li>
                </ul>
            </li>
            <li>
                <a href="complaint.php">
                    <i class='bx bx-message-rounded-dots'></i>
                    <span class="link_name">Complaints</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="complaint.php">Complaints</a></li>
                </ul>
            </li>
            <li>
                <a data-toggle="modal" data-target="#passwordModal">
                    <i class='bx bx-cog'></i>
                    <span class="link_name">Settings</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" data-toggle="modal" data-target="#passwordModal">Settings</a></li>
                </ul>
            </li>

            <li>
              <a href="reports.php">
                <i class='bx bx-file'></i>
                <span class="link_name">Reports</span>
              </a>
              <ul class="sub-menu blank">
                <li><a class="link_name" href="reports.php">Reports</a></li>
              </ul>
            </li>
        </ul>
    </div>
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordModalLabel">Enter Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="passwordForm" method="POST" action="check-password.php">
          <div class="form-group">
            <label for="passwordInput">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password">
            <div class="invalid-feedback"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="passwordForm">Submit</button>
      </div>
    </div>
  </div>
</div>
