<h1>Liste des Films</h1>
<table>
    <?php foreach ($output as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td><?= htmlspecialchars($p['description']) ?> €</td>
        </tr>
    <?php endforeach; ?>
</table>
