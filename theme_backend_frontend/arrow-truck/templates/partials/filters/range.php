<?php
    $options = collect( $field->options );

    $sliderSet = array(
        'step' => 0,
        'first_default' => 0,
        'first' => 0,
        'last' => 0,
        'last_default' => 0,
        'limit' => 0,
        'diff' => 0,
        'default' => true,
        'format' => 'none',
        'format_text' => '',
    );

    foreach ($options as $key => $value) {

        $slug = explode('_', $value->slug);

        if ($slug[0] == 'step') {
            $sliderSet['step'] = intval($value->value);
        }
        if ($slug[0] == 'first') {
            $sliderSet['first'] = intval($value->value);
            $sliderSet['first_default'] = intval($value->value);
        }
        if ($slug[0] == 'last') {
            $sliderSet['last'] = intval($value->value);
            $sliderSet['last_default'] = intval($value->value);
        }
        if ($slug[0] == 'limit') {
            $sliderSet['limit'] = intval($value->value);
        }

        $sliderSet['format'] = $value->association_format;
    }


    $field_base = $field->parameter;
    $start_key = $field_base . '_s';
    $end_key = $field_base . '_e';
    $view_start_key = 'v_' . $field_base . '_s';
    $view_end_key = 'v_' . $field_base . '_e';
    $field_slider = 'nouislider_' . $field_base;

    $hasGet = false;

    if( isset($_GET[$start_key]) || isset($_GET[$end_key])){

        $hasGet = true;

        $sliderSet['default'] = false;

        if(isset($_GET[$start_key])){
            $sliderSet['first'] = intval($_GET[$start_key]);
        }

        if(isset($_GET[$end_key])){
            $sliderSet['last'] = intval($_GET[$end_key]);
        }

    }else{
        $hasGet = false;
    }

    $sliderSet['diff'] = $sliderSet['last'] - $sliderSet['first'];

    if($sliderSet['format'] == 'currency'){
        $sliderSet['first_text'] = "$" . number_format($sliderSet['first'], 0, ".", ",");
        $sliderSet['last_text'] = "$" . number_format($sliderSet['last'], 0, ".", ",");
        $sliderSet['first_label'] = "MN: $" . number_format($sliderSet['first'], 0, ".", ",");
        $sliderSet['last_label'] = "MX: $" . number_format($sliderSet['last'], 0, ".", ",");
    }elseif($sliderSet['format'] == 'thousands'){
        $sliderSet['first_text'] = number_format($sliderSet['first'], 0, ".", ",");
        $sliderSet['last_text'] = number_format($sliderSet['last'], 0, ".", ",");
        $sliderSet['first_label'] = "MN: " . number_format($sliderSet['first'], 0, ".", ",") . " Miles";
        $sliderSet['last_label'] = "MX: " . number_format($sliderSet['last'], 0, ".", ",") . " Miles";
    }else{
        $sliderSet['last_text'] = $sliderSet['first'];
        $sliderSet['last_text'] = $sliderSet['last'];
        $sliderSet['first_label'] = $sliderSet['first'];
        $sliderSet['last_label'] = $sliderSet['last'];
    }

    // var_dump($sliderSet);
    // echo $sliderSet['first'] . '|' . $sliderSet['first_label'];


?>

<div
    class="form-skin grid grid-cols-2 range range-inventory-slider <?php echo $field->prefix; ?>range-slider-init"
    data-parent="<?php echo $field_base; ?>"
    data-minarg="<?php echo $start_key; ?>"
    data-maxarg="<?php echo $end_key; ?>"
    data-min="<?php echo $sliderSet['first']; ?>"
    data-max="<?php echo $sliderSet['last']; ?>"
    data-minlabel="<?php echo $sliderSet['first_label']; ?>"
    data-maxlabel="<?php echo $sliderSet['last_label']; ?>"
    data-format="<?php echo $sliderSet['format']; ?>"
    data-slide="<?php echo $field_slider; ?>"
    data-param="<?php echo $field_base; ?>"
    id="range_<?php echo $field_base; ?>">


    <!-- Get check -->
    <div class="range-field-wrapper">
      <label for="<?php echo $start_key; ?>" class="gfield_label">Min</label>
      <input
          data-slideparent="<?php echo $field_base; ?>"
          data-minarg="<?php echo $start_key; ?>"
          data-maxarg="<?php echo $end_key; ?>"
          data-slidedata="<?php echo $view_start_key; ?>"
          data-slide="<?php echo $field_slider; ?>"
          data-rangetype="min"
          data-min="<?php echo $sliderSet['first']; ?>"
          data-max="<?php echo $sliderSet['last']; ?>"
          data-minlabel="<?php echo $sliderSet['first_label']; ?>"
          data-maxlabel="<?php echo $sliderSet['last_label']; ?>"
          data-mindefault="<?php echo $sliderSet['first_default']; ?>"
          data-maxdefault="<?php echo $sliderSet['last_default']; ?>"
          data-step="<?php echo $sliderSet['step']; ?>"
          data-format="<?php echo $sliderSet['format']; ?>"
          class="range-input range-input-min"
          type="text"
          name="<?php echo $view_start_key; ?>"
          id="<?php echo $view_start_key; ?>"
          value="<?php echo $sliderSet['first']; ?>"
          step="1"
          data-default="<?php echo $sliderSet['first']; ?>">
      <input
        type="hidden"
        class="range-input range-input-min"
        id="<?php echo $start_key; ?>"
        name="<?php echo $start_key; ?>"
        data-label="<?php echo $sliderSet['first_label']; ?>"
        value="<?php echo $sliderSet['first'] . '&' . $sliderSet['first_label']; ?>">
      <span id="text_min_<?php echo $start_key; ?>" class="number-formatted-range-min">
        <?php echo $sliderSet['first_text']; ?>
      </span>
    </div>


    <div class="range-field-wrapper">
      <label for="<?php echo $end_key; ?>" class="gfield_label">Max</label>
      <input
          data-slideparent="<?php echo $field_base; ?>"
          data-minarg="<?php echo $start_key; ?>"
          data-maxarg="<?php echo $end_key; ?>"
          data-slidedata="<?php echo $view_end_key; ?>"
          data-slide="<?php echo $field_slider; ?>"
          data-rangetype="max"
          data-min="<?php echo $sliderSet['first']; ?>"
          data-max="<?php echo $sliderSet['last']; ?>"
          data-minlabel="<?php echo $sliderSet['first_label']; ?>"
          data-maxlabel="<?php echo $sliderSet['last_label']; ?>"
          data-mindefault="<?php echo $sliderSet['first_default']; ?>"
          data-maxdefault="<?php echo $sliderSet['last_default']; ?>"
          data-step="<?php echo $sliderSet['step']; ?>"
          data-format="<?php echo $sliderSet['format']; ?>"
          class="range-input range-input-max"
          type="text"
          name="<?php echo $view_end_key; ?>"
          id="<?php echo $view_end_key; ?>"
          value="<?php echo $sliderSet['last']; ?>"
          step="1"
          data-default="<?php echo $sliderSet['last']; ?>">
      <input
        type="hidden"
        class="range-input range-input-max"
        id="<?php echo $end_key; ?>"
        name="<?php echo $end_key; ?>"
        data-label="<?php echo $sliderSet['last_label']; ?>"
        value="<?php echo $sliderSet['last'] . '&' . $sliderSet['last_label']; ?>">
      <span id="text_max_<?php echo $end_key; ?>" class="number-formatted-range-max">
          <?php echo $sliderSet['last_text']; ?>
      </span>
    </div>

    <div
      data-sliderid="<?php echo $field_base; ?>"
      id="<?php echo $field_slider; ?>"
      class="col-span-2 nouislider range-field-jselement"
      data-slide="<?php echo $field_slider; ?>"
      data-min="<?php echo $sliderSet['first']; ?>"
      data-max="<?php echo $sliderSet['last']; ?>"
      data-minlabel="<?php echo $sliderSet['first_label']; ?>"
      data-maxlabel="<?php echo $sliderSet['last_label']; ?>"
      data-mindefault="<?php echo $sliderSet['first_default']; ?>"
      data-maxdefault="<?php echo $sliderSet['last_default']; ?>"
      data-step="<?php echo $sliderSet['step']; ?>">
    </div>



</div>
