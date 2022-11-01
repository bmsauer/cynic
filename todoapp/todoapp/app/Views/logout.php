<p>This form will sign you out of the Cynic platform.</p>

<form action="/logout" method="post">
    <?= csrf_field() ?>
    <input type="submit" value="Logout" />
</form>