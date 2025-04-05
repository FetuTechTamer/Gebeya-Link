<?php
if (!empty($success_msg)) {
    foreach ($success_msg as $message) {
        echo '<script>swal("' . $message . '", "", "success");</script>';
    }
}

if (!empty($warning_msg)) {
    foreach ($warning_msg as $message) {
        echo '<script>swal("' . $message . '", "", "warning");</script>';
    }
}

if (!empty($info_msg)) {
    foreach ($info_msg as $message) {
        echo '<script>swal("' . $message . '", "", "info");</script>';
    }
}

if (!empty($error_msg)) {
    foreach ($error_msg as $message) {
        echo '<script>swal("' . $message . '", "", "error");</script>';
    }
}
?>