<p>Add a new todo item.</p>

<form action="/add" method="post">
    <?= csrf_field() ?>
    
    <label for="title">Title:</label></br>
    <input type="input" name="title" /><br />
    
    <label for="details">Details:</label></br>
    <textarea name="details" rows="4" cols="50"></textarea><br/>
    <input type="submit" name="submit" value="Add Todo" />
</form>
    