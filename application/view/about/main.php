<?=$header?>
  <div class="container">
    <section class="left">
      <div class="inside">
        <?=$content?>
      </div>
    </section>
    <section class="right">
      <ul>
      <?php foreach ($archors as $key => $value) { ?>
        <li><a href="#<?=$key?>"><?=$value?></a></li>
      <?php } ?>
      </ul>
    </section>
  </div>

<?=$footer?>