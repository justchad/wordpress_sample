<?php
    $inventory = new ArrowApiInventory();
?>

<div class="arrow-admin">
  <h1 class="title">Inventory</h1>

  <div class="arrow-admin-body">
    <table class="arrow-admin-table">
      <tbody>
        <tr>
          <td class="text-hdg">Total from API</td>
          <td class="text-right"><?php echo $inventory->getAll()->count() ?>

          </td>
        </tr>
        <tr>
          <td class="text-hdg">
            Total on Website
            <div>
              <a class="link" href="<?php echo home_url() ?>/wp-admin/edit.php?post_type=ll_inventory">Manage Inventory</a>
            </div>
          </td>
          <td class="text-right" id="arrow-sync-count"><?php echo count( $inventory_on_site ); ?></td>
        </tr>
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
      <span>Purge Inventory</span>
    </button>

    <span class="btn hidden" id="arrow-admin-purge-placeholder">
      <i class="fas fa-sync fa-spin"></i>
      <span>Purge Inventory</span>
    </span>


  </div>
</div>