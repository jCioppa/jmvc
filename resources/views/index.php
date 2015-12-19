<?php
    if (app()->user()) {
        echo "<div style = 'margin: 0; width: 100%; height; 100px; border: 1px solid red; padding: 20px;'>" . app()->user()->name . '</div>';
    }
?>

<h1><?php foreach ($users as $user) echo($user->name); ?></h1>
<h1><?php echo ($article->title); ?></h1>

<?php

if (session()->has('flash_message')) {
    echo session()->get('flash_message');
}

?>

<form action = "<?php echo SERVER; ?>/testPost" method = "POST">
    <input type = "text" name = "name">
    <input type = "submit">
</form>
