<?php
/*
 * This is simply a record of markup templates for the various dropdowns
 */
?>

<?php /* Text search w/ selected filters */ ?>
<div class="flex items-center justify-between flex-wrap">

  <div class="flex-1 mr-4">

    <label for="s" class="sr-only">Search</label>
    <input type="text" name="s" class="keyword-input" placeholder="Search by Make, Model, or Keyword">

  </div>

  <button class="show-filters filter-button" data-toggle-target=".current-filters-wrapper, .search-inventory-results" data-toggle-class="is-open" data-toggle-outside data-toggle-escape><span class="filters">Filters (6)</span><span class="hide-filters hidden">Hide</span></button>

  <div class="w-full relative current-filters-wrapper">

    <div class="flex justify-start items-center current-filters">

      <div class="button-area">

        <button>Volvo <svg class="icon icon-close"><use xlink:href="#icon-close"></use></svg></button>

      </div>

      <div class="button-area">

        <button>Freightliner <svg class="icon icon-close"><use xlink:href="#icon-close"></use></svg></button>

      </div>

      <div class="button-area">

        <button>Ford <svg class="icon icon-close"><use xlink:href="#icon-close"></use></svg></button>

      </div>

    </div> <!-- /.flex -->

  </div> <!-- /.w-full -->

</div> <!-- /.flex -->

<?php /* various dropdowns */ ?>
<div class="button-area">

  <button data-toggle-target="#make-dropdown" data-toggle-class="is-open" data-toggle-group="search-form" data-toggle-outside data-toggle-escape aria-haspopup="true" aria-expanded="false" aria-controls="#make-dropdown" aria-controls="#make-dropdown">Make <svg class="icon icon-chevron-down hidden lg:inline-block"><use xlink:href="#icon-chevron-down"></use></svg></button>

  <div class="search-dropdown" id="make-dropdown">

    <span class="arrow"></span>

    <ul class="checkbox-list">
      <li>
        <label for="over-the-road" tabindex="0">Over the Road</label>
        <input type="checkbox" name="make" id="over-the-road">
        <div class="icon-area">
          <span class="icon icon-checkmark-empty"></span>
          <svg class="icon icon-checkmark-filled"><use xlink:href="#icon-checkmark-filled"></use></svg>
        </div>
      </li>
      <li>
        <label for="regional" tabindex="0">Regional</label>
        <input type="checkbox" name="make" id="regional">
        <div class="icon-area">
          <span class="icon icon-checkmark-empty"></span>
          <svg class="icon icon-checkmark-filled"><use xlink:href="#icon-checkmark-filled"></use></svg>
        </div>
      </li>
      <li>
        <label for="local" tabindex="0">Local</label>
        <input type="checkbox" name="make" id="local">
        <div class="icon-area">
          <span class="icon icon-checkmark-empty"></span>
          <svg class="icon icon-checkmark-filled"><use xlink:href="#icon-checkmark-filled"></use></svg>
        </div>
      </li>
      <li>
        <label for="moving-storage" tabindex="0">Moving &amp; Storage</label>
        <input type="checkbox" name="make" id="moving-storage">
        <div class="icon-area">
          <span class="icon icon-checkmark-empty"></span>
          <svg class="icon icon-checkmark-filled"><use xlink:href="#icon-checkmark-filled"></use></svg>
        </div>
      </li>
      <li>
        <label for="construction" tabindex="0">Construction</label>
        <input type="checkbox" name="make" id="construction">
        <div class="icon-area">
          <span class="icon icon-checkmark-empty"></span>
          <svg class="icon icon-checkmark-filled"><use xlink:href="#icon-checkmark-filled"></use></svg>
        </div>
      </li>
      <li>
        <label for="agricultural" tabindex="0">Agricultural</label>
        <input type="checkbox" name="make" id="agricultural">
        <div class="icon-area">
          <span class="icon icon-checkmark-empty"></span>
          <svg class="icon icon-checkmark-filled"><use xlink:href="#icon-checkmark-filled"></use></svg>
        </div>
      </li>
    </ul>

  </div> <!-- /.search-dropdown -->

</div>

<div class="button-area">

  <button data-toggle-target="#model-dropdown" data-toggle-class="is-open" data-toggle-group="search-form" data-toggle-outside data-toggle-escape aria-haspopup="true" aria-expanded="false" aria-controls="#model-dropdown">Model <svg class="icon icon-chevron-down hidden lg:inline-block"><use xlink:href="#icon-chevron-down"></use></svg></button>

  <div class="search-dropdown" id="model-dropdown">

    <span class="arrow"></span>

    <fieldset>
      <legend>Engine Make</legend>
      <div class="inputs">
        <div>
          <input type="checkbox" name="format" id="CAT" value="CAT">
          <label for="CAT" tabindex="0">CAT</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="CUMM" value="CUMM">
          <label for="CUMM" tabindex="0">CUMM</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="DET" value="DET">
          <label for="DET" tabindex="0">DET</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="HINO" value="HINO">
          <label for="HINO" tabindex="0">HINO</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="INTL" value="INTL">
          <label for="INTL" tabindex="0">INTL</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="PACCAR" value="PACCAR">
          <label for="PACCAR" tabindex="0">PACCAR</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="VOLVO" value="VOLVO">
          <label for="VOLVO" tabindex="0">VOLVO</label>
        </div>
      </div>
    </fieldset>

  </div> <!-- /.search-dropdown -->

</div>

<div class="button-area">

  <button data-toggle-target="#year-dropdown" data-toggle-class="is-open" data-toggle-group="search-form" data-toggle-outside data-toggle-escape aria-haspopup="true" aria-expanded="false" aria-controls="#year-dropdown">Year <svg class="icon icon-chevron-down hidden lg:inline-block"><use xlink:href="#icon-chevron-down"></use></svg></button>

  <div class="search-dropdown" id="year-dropdown">

    <span class="arrow"></span>

    <fieldset>
      <legend>Year</legend>
      <div class="inputs">
        <div>
          <input type="checkbox" name="format" id="1990" value="1990">
          <label for="1990" tabindex="0">1990</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1991" value="1991">
          <label for="1991" tabindex="0">1991</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1992" value="1992">
          <label for="1992" tabindex="0">1992</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1993" value="1993">
          <label for="1993" tabindex="0">1993</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1994" value="1994">
          <label for="1994" tabindex="0">1994</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1995" value="1995">
          <label for="1995" tabindex="0">1995</label>
        </div>
      </div>
    </fieldset>

  </div> <!-- /.search-dropdown -->

</div>

<div class="button-area hidden lg:block">

  <button data-toggle-target="#price-dropdown" data-toggle-class="is-open" data-toggle-group="search-form" data-toggle-outside data-toggle-escape aria-haspopup="true" aria-expanded="false" aria-controls="#price-dropdown">Price <svg class="icon icon-chevron-down hidden lg:inline-block"><use xlink:href="#icon-chevron-down"></use></svg></button>

  <div class="search-dropdown" id="price-dropdown">

    <span class="arrow"></span>

    <fieldset>
      <legend>Price</legend>
      <div class="inputs">
        <div>
          <input type="checkbox" name="format" id="1990" value="1990">
          <label for="1990" tabindex="0">1990</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1991" value="1991">
          <label for="1991" tabindex="0">1991</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1992" value="1992">
          <label for="1992" tabindex="0">1992</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1993" value="1993">
          <label for="1993" tabindex="0">1993</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1994" value="1994">
          <label for="1994" tabindex="0">1994</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1995" value="1995">
          <label for="1995" tabindex="0">1995</label>
        </div>
      </div>
    </fieldset>

  </div> <!-- /.search-dropdown -->

</div>

<div class="button-area hidden lg:block">

  <button data-toggle-target="#type-dropdown" data-toggle-class="is-open" data-toggle-group="search-form" data-toggle-outside data-toggle-escape aria-haspopup="true" aria-expanded="false" aria-controls="#type-dropdown">Type <svg class="icon icon-chevron-down hidden lg:inline-block"><use xlink:href="#icon-chevron-down"></use></svg></button>

  <div class="search-dropdown" id="type-dropdown">

    <span class="arrow"></span>

    <fieldset>
      <legend>Type</legend>
      <div class="inputs">
        <div>
          <input type="checkbox" name="format" id="1990" value="1990">
          <label for="1990" tabindex="0">1990</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1991" value="1991">
          <label for="1991" tabindex="0">1991</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1992" value="1992">
          <label for="1992" tabindex="0">1992</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1993" value="1993">
          <label for="1993" tabindex="0">1993</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1994" value="1994">
          <label for="1994" tabindex="0">1994</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1995" value="1995">
          <label for="1995" tabindex="0">1995</label>
        </div>
      </div>
    </fieldset>

  </div> <!-- /.search-dropdown -->

</div>

<div class="button-area hidden lg:block">

  <button data-toggle-target="#mileage-dropdown" data-toggle-class="is-open" data-toggle-group="search-form" data-toggle-outside data-toggle-escape aria-haspopup="true" aria-expanded="false" aria-controls="#mileage-dropdown">Mileage <svg class="icon icon-chevron-down hidden lg:inline-block"><use xlink:href="#icon-chevron-down"></use></svg></button>

  <div class="search-dropdown" id="mileage-dropdown">

    <span class="arrow"></span>

    <fieldset>
      <legend>Mileage</legend>
      <div class="inputs">
        <div>
          <input type="checkbox" name="format" id="1990" value="1990">
          <label for="1990" tabindex="0">1990</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1991" value="1991">
          <label for="1991" tabindex="0">1991</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1992" value="1992">
          <label for="1992" tabindex="0">1992</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1993" value="1993">
          <label for="1993" tabindex="0">1993</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1994" value="1994">
          <label for="1994" tabindex="0">1994</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1995" value="1995">
          <label for="1995" tabindex="0">1995</label>
        </div>
      </div>
    </fieldset>

  </div> <!-- /.search-dropdown -->

</div>

<div class="button-area hidden lg:block">

  <button data-toggle-target="#engine-dropdown" data-toggle-class="is-open" data-toggle-group="search-form" data-toggle-outside data-toggle-escape aria-haspopup="true" aria-expanded="false" aria-controls="#engine-dropdown">Engine <svg class="icon icon-chevron-down hidden lg:inline-block"><use xlink:href="#icon-chevron-down"></use></svg></button>

  <div class="search-dropdown" id="engine-dropdown">

    <span class="arrow"></span>

    <fieldset>
      <legend>Engine</legend>
      <div class="inputs">
        <div>
          <input type="checkbox" name="format" id="1990" value="1990">
          <label for="1990" tabindex="0">1990</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1991" value="1991">
          <label for="1991" tabindex="0">1991</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1992" value="1992">
          <label for="1992" tabindex="0">1992</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1993" value="1993">
          <label for="1993" tabindex="0">1993</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1994" value="1994">
          <label for="1994" tabindex="0">1994</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="1995" value="1995">
          <label for="1995" tabindex="0">1995</label>
        </div>
      </div>
    </fieldset>

  </div> <!-- /.search-dropdown -->

</div>

<div class="button-area">

  <button data-toggle-target="#more-filters-dropdown" data-toggle-class="is-open" data-toggle-group="search-form" data-toggle-outside data-toggle-escape aria-haspopup="true" aria-expanded="false" aria-controls="#more-filters-dropdown">
    More
    <svg class="icon icon-chevron-down hidden lg:inline-block"><use xlink:href="#icon-chevron-down"></use></svg>
  </button>

  <div class="search-dropdown is-wide" id="more-filters-dropdown">

    <span class="arrow"></span>

    <div class="input-area">
      <label for="stock-num">Stock #</label>
      <input type="text" name="stock-num" id="stock-num" placeholder="Enter Stock Number">
    </div>

    <fieldset>
      <legend>Engine Make</legend>
      <div class="inputs">
        <div>
          <input type="checkbox" name="format" id="0-299" value="0-299">
          <label for="0-299" tabindex="0">0-299</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="300-399" value="300-399">
          <label for="300-399" tabindex="0">300-399</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="400-499" value="400-499">
          <label for="400-499" tabindex="0">400-499</label>
        </div>
        <div>
          <input type="checkbox" name="format" id="500+" value="500+">
          <label for="500+" tabindex="0">500+</label>
        </div>
      </div>
    </fieldset>

  </div> <!-- /.search-dropdown -->

</div>
