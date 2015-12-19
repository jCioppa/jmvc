<h1>Register new user</h1>
<?php
if (session()->has('flash_message')) {
    echo session()->get('flash_message');
}
?>


<form method = "post" action = "<?php echo SERVER; ?>/register">
    <input type = "text" name = "name" placeholder = "enter your name"><br>
    <input type = "text" name = "email" placeholder = "enter your email"><br>
    <input type = "text" name = "password" placeholder = "enter your password"><br>
    <input type = "text" name = "password_confirmation" placeholder = "re-enter your password"><br>
    <input type = "submit">
</form>
