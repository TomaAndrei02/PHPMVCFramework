<h1>Home</h1>

<?php
use app\core\Application;
?>

<?php if(Application::isGuest()): ?>

    <h3>Welcome</h3>

<?php else: ?>

    <h3>Welcome <?php echo Application::$app->user->getDisplayName() ?></h3>
    
<?php endif; ?>
