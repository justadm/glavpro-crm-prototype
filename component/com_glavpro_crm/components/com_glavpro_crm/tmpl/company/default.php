<?php
/** @var \Glavpro\Component\GlavproCrm\Site\View\Company\HtmlView $this */

declare(strict_types=1);

$company = $this->company;
$events = $this->events;
?>

<h2>Карточка компании (демо)</h2>

<?php if ((int) ($company->id ?? 0) <= 0) : ?>
  <div>Компания не найдена</div>
  <div><a href="index.php?option=com_glavpro_crm&view=companies">К списку компаний</a></div>
  <?php return; ?>
<?php endif; ?>

<div><strong>Компания:</strong> <?php echo htmlspecialchars((string) $company->name); ?></div>
<div><strong>Текущая стадия:</strong> <?php echo htmlspecialchars((string) $company->stage_code); ?></div>
<div><a href="index.php?option=com_glavpro_crm&view=companies">К списку компаний</a></div>

<h3>История событий</h3>
<?php if (empty($events)) : ?>
  <div>Событий нет</div>
<?php else : ?>
  <table>
    <thead>
      <tr>
        <th>Тип</th>
        <th>Дата</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($events as $event) : ?>
        <tr>
          <td><?php echo htmlspecialchars((string) ($event['type'] ?? '')); ?></td>
          <td><?php echo htmlspecialchars((string) ($event['created_at'] ?? '')); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

