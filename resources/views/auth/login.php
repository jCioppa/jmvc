<h1>Login</h1>

<?php
if (session()->has('flash_message')) {
    echo session()->get('flash_message');
}
?>

    <form method = "post" action = "<?php echo SERVER; ?>/login">
    <input type = "text" name = "email" placeholder = "enter your email"><br>
    <input type = "text" name = "password" placeholder = "enter your password"><br>
    <input type = "submit">
</form>
