<?php
if (session()->has('flash_message')) {
    echo session()->get('flash_message');
}
?>


