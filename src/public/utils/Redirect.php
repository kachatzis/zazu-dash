<?php

  function redirect($url){
    ?>
      <script type="text/javascript">
      window.location.href = '<?php echo $url; ?>';
      </script>
    <?php
  }

  function header_redirect($url){
    header('Location: '.$url);
    redirect($url);
    exit();
  }

 ?>
