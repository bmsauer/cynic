
<p>This form will sign you into the Cynic platform.</p>

<form action="/login" method="post">
    <?= csrf_field() ?>
    
    <label for="username">Username:</label><br/>
    <input type="input" name="username" /><br />

    <label for="password">Password:</label><br/>
    <input type="password" name="password" /><br />
    <input type="submit" name="submit" value="Login" />
</form>