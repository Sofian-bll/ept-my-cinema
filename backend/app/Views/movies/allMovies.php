<h1>Liste des Films</h1>
<div style="display: flex; width: 100%; flex-wrap: wrap; gap: 10px; justify-content: space-between">
    <?php foreach ($data as $movie): ?>
        <article style='width: 300px; display: flex ; flex-direction: column; height: fit-content'>
            <header><?= $movie->getTitle() ?></header>
            <div style='display: flex; justify-content: space-between; height: fit-content'>
                <p>Duration</p>
                <p><?= $movie->getDuration() ?></p>
            </div>
            <div style='display: flex; justify-content: space-between; height: fit-content'>
                <p>Release Year</p>
                <p><?= $movie->getReleaseYear() ?></p>
            </div>
            <br>
            <details>
                <summary>Description</summary>
                <?= $movie->getDescription() ?>
            </details>
            <details>
                <summary>Details</summary>
                <div style='display: flex; justify-content: space-between; height: fit-content'>
                    <p>Director</p>
                    <p><?= $movie->getDirector() ?></p>
                </div>
                <div style='display: flex; justify-content: space-between; height: fit-content'>
                    <p>Genre</p>
                    <p><?= $movie->getGenre() ?></p>
                </div>
                <div style='display: flex; justify-content: space-between; height: fit-content'>
                    <p>Created At</p>
                    <p><?= $movie->getCreatedAt() ?></p>
                </div>
                <div style='display: flex; justify-content: space-between; height: fit-content'>
                    <p>Updated At</p>
                    <p><?= $movie->getUpdatedAt() ?></p>
                </div>
            </details>

            <footer>
                <form action="?path=/movies/delete/<?= $movie->getId() ?>" method="post">
                    <button type="submit">Delete</button>
                </form>

                <details>
                    <summary>
                        Edit
                    </summary>
                    <form action="?path=/movies/edit/<?= $movie->getId() ?>" method='post'>
                        <input type='text' name='title' value="<?= $movie->getTitle() ?>">
                        <input type='text' name='description' value="<?= $movie->getDescription() ?>">
                        <input type='text' name='director' value="<?= $movie->getDirector() ?>">
                        <input type='number' name='duration' value="<?= $movie->getDuration() ?>">
                        <input type='number' name='release_year' value="<?= $movie->getReleaseYear() ?>">
                        <input type='text' name='genre' value="<?= $movie->getGenre() ?>">
                        <button type='submit'>Submit</button>
                    </form>
                </details>
            </footer>
        </article>
    <?php endforeach; ?>

</div>

<table>
    <tr>
        <td>Title</td>
        <td>Description</td>
        <td>Duration</td>
        <td>Release Year</td>
        <td>Genre</td>
        <td>Director</td>
        <td>Created at</td>
        <td>Updated at</td>
        <td>Delete BTN</td>
    </tr>
    <?php foreach ($data as $movie): ?>
        <tr>
            <td><?= $movie->getTitle() ?></td>
            <td style="font-size: small"><?= $movie->getDescription() ?></td>
            <td><?= $movie->getDuration() ?></td>
            <td><?= $movie->getReleaseYear() ?></td>
            <td><?= $movie->getGenre() ?></td>
            <td><?= $movie->getDirector() ?></td>
            <td><?= $movie->getCreatedAt() ?></td>
            <td><?= $movie->getUpdatedAt() ?></td>
            <td>
                <button>Delete</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

