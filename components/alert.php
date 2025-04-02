<?php
  if (isset($success_msg) && is_array($success_msg)) 
  {
    echo '<script>swal("' . addslashes(implode("\n", $success_msg)) . '", "", "success");</script>';
  }

  if (isset($warning_msg) && is_array($warning_msg)) 
  {
    echo '<script>swal("' . addslashes(implode("\n", $warning_msg)) . '", "", "warning");</script>';
  }

  if (isset($info_msg) && is_array($info_msg)) 
  {
    echo '<script>swal("' . addslashes(implode("\n", $info_msg)) . '", "", "info");</script>';
  }

  if (isset($error_msg) && is_array($error_msg)) 
  {
    echo '<script>swal("' . addslashes(implode("\n", $error_msg)) . '", "", "error");</script>';
  }

?>