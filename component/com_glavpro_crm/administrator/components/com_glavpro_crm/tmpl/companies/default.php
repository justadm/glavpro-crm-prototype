<?php
/** @var \Glavpro\Administrator\Component\GlavproCrm\View\Companies\HtmlView $this */

declare(strict_types=1);

$items = $this->items;
?>

<h2>Список компаний</h2>

<?php if (empty($items)) : ?>
    <div>Компаний нет</div>
<?php else : ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Стадия</th>
                <th>Обновлено</th>
                <th>Карточка</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <td><?php echo (int) $item->id; ?></td>
                    <td><?php echo htmlspecialchars((string) $item->name); ?></td>
                    <td><?php echo htmlspecialchars((string) $item->stage_code); ?></td>
                    <td><?php echo htmlspecialchars((string) $item->updated_at); ?></td>
                    <td>
                        <a href="index.php?option=com_glavpro_crm&view=company&id=<?php echo (int) $item->id; ?>">Открыть</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
