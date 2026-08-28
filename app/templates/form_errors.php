<?php if(isset($errors)):?>
  <?php foreach($errors as $error):?>
    <div class="container">
      <div class="row">
        <div class="col col-lg-6 col-sm-12 offset-lg-3 ">
          <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach;?>
<?php endif;?>