<table class="table table-bordered">
    <tr>
        <th>文章名</th>
        <th>文章内容</th>
    </tr>
    <?php foreach ($article as $articles): ?>
    <tr>

        <td><?=$articles->article->name?></td>
        <td><?=$articles->content?></td>
    </tr>
    <?php endforeach; ?>
</table>