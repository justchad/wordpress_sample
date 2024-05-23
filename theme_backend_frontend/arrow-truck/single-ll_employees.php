<?php

  global $wp;
  $sales_rep = new ArrowSalesRep( $thisUser, true );

?>

<pre>
    <?php
        var_dump($sales_rep);
    ?>
</pre>

  <section class="arrowsalesrepview container grid md:grid-cols-6 lg:grid-cols-12 gap-13 mt-10" data-component="set-code" data-code="<?php echo $sales_rep->SLSREPNO ?>">


  </section>
