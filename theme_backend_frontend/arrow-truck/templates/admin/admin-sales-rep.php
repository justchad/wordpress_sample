<div class="arrow-admin">
  <h1 class="title">Arrow Employees</h1>

  <div class="arrow-admin-body">
    <table class="arrow-admin-table">
      <tbody>
        <tr>
          <td class="text-hdg">Total from API</td>
          <td class="text-right"><?php echo $totals->api; ?></td>
        </tr>
        <tr>
          <td class="text-hdg">
            Total on Website
            <div>
              <a class="link" href="<?php echo home_url(); ?>/wp-admin/users.php">Manage Users</a>
            </div>
          </td>
          <td class="text-right" id="arrow-sync-count-all"><?php echo $totals->sum; ?></td>
          <td class="text-right hidden" id="arrow-sync-count"><?php echo $totals->sum; ?></td>
        </tr>

        <tr>
          <td class="text-hdg">
            Total Sales Reps
            <div>
              <a class="link" href="<?php echo home_url() ?>/wp-admin/users.php?role=arrow_sales_rep">Manage Reps</a>
            </div>
          </td>
          <td class="text-right" id="arrow-sync-count-sales-reps"><?php echo $totals->reps; ?></td>
        </tr>

        <tr>
          <td class="text-hdg">
            Total Buyers
            <div>
              <a class="link" href="<?php echo home_url() ?>/wp-admin/users.php?role=arrow_buyer">Manage Buyers</a>
            </div>
          </td>
          <td class="text-right" id="arrow-sync-count-buyers"><?php echo $totals->buyer; ?></td>
        </tr>

        <tr>
          <td class="text-hdg">
            Total Finance Managers
            <div>
              <a class="link" href="<?php echo home_url() ?>/wp-admin/users.php?role=arrow_fandi_manager">Manage Finance Managers</a>
            </div>
          </td>
          <td class="text-right" id="arrow-sync-count-finance-managers"><?php echo $totals->manager; ?></td>
        </tr>

        <?php if( $show_customers === true ) :?>

            <tr>
              <td class="text-hdg">
                Total Customers
                <div>
                  <a class="link" href="<?php echo home_url() ?>/wp-admin/users.php?role=customer">Manage Customers</a>
                </div>
              </td>
              <td class="text-right" id="arrow-sync-count-customers"><?php echo $totals->customer; ?></td>
            </tr>

        <?php endif; ?>

      </tbody>
    </table>
  </div>

  <div class="arrow-admin-action">

    <button class="btn" id="arrow-admin-sync">
      <i class="fas fa-sync"></i>
      <span>Sync</span>
    </button>

    <span class="btn hidden" id="arrow-admin-sync-placeholder">
      <i class="fas fa-sync fa-spin"></i>
      <span>Sync</span>
    </span>


    <button class="btn" id="arrow-admin-purge">
      <i class="fas fa-sync"></i>
      <span>Purge</span>
    </button>

    <span class="btn hidden" id="arrow-admin-purge-placeholder">
      <i class="fas fa-sync fa-spin"></i>
      <span>Purge</span>
    </span>

  </div>
</div>
