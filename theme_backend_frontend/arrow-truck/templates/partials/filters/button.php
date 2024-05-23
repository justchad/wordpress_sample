<fieldset class="bg-white p-4 rounded border-solid border border-gray-200 mb-8">
  <legend class="font-bold"><?php echo $field->label ?></legend>
  <div class="inputs mt-4 button-list autosort">
    <?php foreach ( $field->options as $option ) : ?>
      <?php
        $option->slug = $field->prefix . '' . $option->slug;
        $option->slug = strtolower($option->slug);
      ?>
      <div class="flex justify-between mb-2 lisorter <?php echo $option->association ? 'hidden' : ''; ?>" data-association="<?php echo $option->association ?>" data-sortvalue="<?php echo $option->label; ?>">
        <label class="filter-input-label" for="<?php echo $option->slug; ?>"><?php echo $option->label ?></label>
        <input class="filter-input" type="radio" name="<?php echo $field->parameter; ?>" id="<?php echo $option->slug; ?>" value="<?php echo $option->value; ?>&<?php echo $option->label; ?>" data-label="<?php echo $option->label; ?>" <?php echo( $_GET && isset( $_GET[$field->parameter] ) && $_GET[$field->parameter] == $option->value ? 'checked' : '' ) ?>>

      </div>
    <?php endforeach; ?>
  </div>
</fieldset>
