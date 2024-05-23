<div class="arrow-admin">
  <h1 class="title">Filters Display</h1>
  <form class="arrow-admin-body" method="post">
    <?php foreach ($taxonomies as $taxonomy ) : ?>
      <div class="arrow-admin-action border-none">
        <h2><?php echo $taxonomy->label; ?></h2>

        <p class="text-hdg">Display Type</p>
        <div>
          <label>
            <input name="<?php echo $taxonomy->name ?>_display_type" type="radio" value="list" <?php checked( $display_data[$taxonomy->name.'_display_type'], 'list' ); ?>>
            List
          </label>
        </div>

        <div>
          <label>
            <input name="<?php echo $taxonomy->name ?>_display_type" type="radio" value="button" <?php checked( $display_data[$taxonomy->name.'_display_type'], 'button' ); ?>>
            Buttons
          </label>
        </div>

        <div>
          <label>
            <input name="<?php echo $taxonomy->name ?>_display_type" type="radio" value="range" <?php checked( $display_data[$taxonomy->name.'_display_type'], 'range' ); ?>>
            Range
          </label>
        </div>

        <p class="text-hdg">Display Context</p>
        <div>
          <label>
            <input name="<?php echo $taxonomy->name ?>_display_context" type="radio" value="main" <?php checked( $display_data[$taxonomy->name.'_display_context'], 'main' ); ?>>
            Main List
          </label>
        </div>

        <div>
          <label>
            <input name="<?php echo $taxonomy->name ?>_display_context" type="radio" value="additional" <?php checked( $display_data[$taxonomy->name.'_display_context'], 'additional' ); ?>>
            Additional
          </label>
        </div>
      </div>
    <?php endforeach ?>

    <input type="hidden" name="action" value="arrow-filter-admin">
    <input type="hidden" name="_arrow_nonce" value="<?php echo wp_create_nonce( 'arrow-filter-admin' ); ?>" />

    <div class="arrow-admin-action">
      <button class="btn" type="submit">
        <i class="fas fa-save"></i>
        <span>Save</span>
      </button>
    </div>
  </form>

</div>