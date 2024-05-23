<div>
  <table class="wp-pattern-table striped" style="border-collapse: collapse;">
    <tbody>
    <?php if($data != null) : ?>
      <?php foreach( $data as $key => $value ) : ?>
        <tr>
          <td><span><?php echo $key ?></span></td>
          <td><span><?php echo $value; ?></span></td>
        </tr>
      <?php endforeach; ?>

    <?php else : ?>
      <tr>
        <td><span>Unable to retrieve data.</span></td>
      </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
