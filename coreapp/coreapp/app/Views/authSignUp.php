<h1>Cynic Core Application - Auth Module</h1>

<?= session()->getFlashdata('error') ?>
<?= service('validation')->listErrors() ?>

<form action="/auth/signup" method="post">
    <?= csrf_field() ?>

    <label for="username">Username:</label><br/>
    <input type="input" name="username" /><br />

     <label for="password">Password:</label><br/>
    <input type="password" name="password" /><br />

    <label for="confirm_password">Confirm Password:</label><br/>
    <input type="password" name="confirm_password" /><br />
    <br/>
    <input type="submit" name="submit" value="Sign Up" />
</form>