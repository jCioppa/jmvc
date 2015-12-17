<h1><?php foreach ($users as $user) echo($user->name); ?></h1>
<h1><?php echo ($article->title); ?></h1>

<form action = "<?php echo SERVER; ?>/testPost" method = "POST">
    <input type = "text" name = "name">
    <input type = "submit">
</form>
