<?php
/** @var \Glavpro\Component\GlavproCrm\Site\View\Companies\HtmlView $this */

declare(strict_types=1);

use Joomla\CMS\HTML\HTMLHelper;

$items = $this->items;
$search = $this->search ?? '';
?>

<h2>Компании (демо)</h2>

<form method="get" action="index.php">
  <input type="hidden" name="option" value="com_glavpro_crm">
  <input type="hidden" name="view" value="companies">
  <label>
    Поиск по названию:
    <input type="text" name="filter_search" value="<?php echo htmlspecialchars((string) $search); ?>">
  </label>
  <button type="submit">Найти</button>
</form>

<form method="post" action="index.php?option=com_glavpro_crm&task=company.createDemo">
  <label>
    Создать демо-компаний:
    <input type="number" name="count" min="1" max="50" value="1">
  </label>
  <button type="submit">Создать</button>
  <?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php if (empty($items)) : ?>
  <div>Компаний нет</div>
<?php else : ?>
  <table style="width: 100%;">
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
