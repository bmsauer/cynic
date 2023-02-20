<?php if (isset($jwt) && $jwt !== NULL): ?>
    <h2>Actions</h2>
    <ul>
        <li><a href="/add">Add a Todo Item</a></li>
    </ul>
    <h2>Todo List</h2>
    <?php if (empty($items) || !is_array($items)): ?>
    <p>No items!</p>
    <?php else: ?>
    <p>Total Items: <?= esc($total_items) ?> </p>
    <?php foreach($items as $id => $item): ?>
        <details>
        <summary><?= esc($item['title']) ?></summary>
        <p><?= esc($item['details']) ?></p>
        <ul>
            <li>Completed: <?= esc($item['completed']) ?></li>
            <li>Date Added: <?= esc($item['date_added']) ?> </li>
            <form method="post" action="/complete">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= esc($id) ?>" />
                <input type="submit" value="Complete" />
            </form>
                
        </ul>
        </details>
    <?php endforeach ?>
    <?php endif ?>
<?php else: ?>
    <p>Please log in to the Cynic platform.</p>
<?php endif ?>