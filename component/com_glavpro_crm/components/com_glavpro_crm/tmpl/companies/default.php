<?php
/** @var \Glavpro\Component\GlavproCrm\Site\View\Companies\HtmlView $this */

declare(strict_types=1);

$items = $this->items;
?>

<h2>Компании (демо)</h2>

<?php if (empty($items)) : ?>
  <div>Компаний нет</div>
<?php else : ?>
  <ul>
    <?php foreach ($items as $item) : ?>
      <li>
        <a href="index.php?option=com_glavpro_crm&view=company&id=<?php echo (int) $item->id; ?>">
          <?php echo htmlspecialchars((string) $item->name); ?>
        </a>
        (<?php echo htmlspecialchars((string) $item->stage_code); ?>)
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
