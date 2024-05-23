<ul class="checkbox-list autosort">

    <?php foreach ( $field->options as $option ) : ?>
    <?php
      $option->slug = $field->prefix . '' . $option->slug;
      $option->slug = strtolower($option->slug);
      $valueString = str_replace('<', '', $option->value) . '&' . str_replace('<', '', $option->label);
      $vString = htmlentities($valueString)
    ?>

    <li class="flex justify-between w-full px-4 py-2 <?php echo $option->association ? 'hidden' : ''; ?>" data-association="<?php echo $option->association; ?>" data-sortvalue="<?php echo $option->label; ?>">

        <label class="filter-input-label" for="<?php echo $option->slug; ?>"><?php echo $option->label; ?>

        <input
            class="filter-input"
            type="radio"
            name="<?php echo $field->parameter; ?>"
            id="<?php echo $option->slug; ?>"
            value="<?php echo $vString; ?>"
            data-label="<?php echo $option->label; ?>"
            <?php echo( $_GET && isset( $_GET[$field->parameter] ) && $_GET[$field->parameter] == $option->value ? 'checked' : '' ) ?>>
        </label>

    </li>
    <?php endforeach; ?>
</ul>
