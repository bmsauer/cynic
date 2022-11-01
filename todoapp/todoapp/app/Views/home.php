<?php if (isset($jwt) && $jwt !== NULL): ?>
    <h2>Actions</h2>
    <ul>
        <li><a href="/add">Add a Todo Item</a></li>
    </ul>
    <h2>Todo List</h2>
    
<?php else: ?>
    <p>Please log in to the Cynic platform.</p>
<?php endif ?>