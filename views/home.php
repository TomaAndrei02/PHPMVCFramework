<?php
$this->title = 'Home';
?>
<h1>Home</h1>

<?php
use app\core\Application;
?>

<?php if(Application::isGuest()): ?>

    <h3>Welcome</h3>
    <a href="http://localhost:3000/">Back to list of users</a>
<?php else: ?>

    <h3>Welcome <?php echo Application::$app->user->getDisplayName() ?></h3>
    <a href="http://localhost:3000/">Back to list of users</a>
<?php endif; ?>
