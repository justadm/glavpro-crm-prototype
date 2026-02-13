<?php
/** @var \Glavpro\Component\GlavproCrm\Administrator\View\Company\HtmlView $this */

declare(strict_types=1);

use Joomla\CMS\HTML\HTMLHelper;

$company = $this->company;
$actions = $this->actions;
$events = $this->events;

$formatEventDate = static function ($value): string {
    if ($value instanceof \DateTimeInterface) {
        return $value->format('Y-m-d H:i:s');
    }

    if (is_string($value)) {
        return $value;
    }

    return '';
};

$actionConfig = [
    'call' => [
        'label' => 'Попытка контакта',
        'event_type' => 'attempt_contact',
        'fields' => [],
    ],
    'comment_after_call' => [
        'label' => 'Разговор с ЛПР + комментарий',
        'event_type' => 'lpr_call_done',
        'fields' => [
            ['name' => 'comment', 'label' => 'Комментарий', 'type' => 'textarea'],
        ],
    ],
    'fill_discovery' => [
        'label' => 'Заполнить дискавери',
        'event_type' => 'discovery_filled',
        'fields' => [
            ['name' => 'discovery', 'label' => 'Дискавери', 'type' => 'textarea'],
        ],
    ],
    'schedule_demo' => [
        'label' => 'Планирование демо',
        'event_type' => 'demo_scheduled',
        'fields' => [
            ['name' => 'demo_datetime', 'label' => 'Дата и время демо', 'type' => 'text'],
        ],
    ],
    'do_demo_via_link' => [
        'label' => 'Демо проведено (по ссылке)',
        'event_type' => 'demo_done',
        'fields' => [
            ['name' => 'demo_link', 'label' => 'Ссылка на демо', 'type' => 'text'],
        ],
    ],
    'create_application' => [
        'label' => 'Завести заявку',
        'event_type' => 'application_created',
        'fields' => [
            ['name' => 'application_note', 'label' => 'Комментарий по заявке', 'type' => 'text'],
        ],
    ],
    'send_commercial_offer' => [
        'label' => 'Отправить КП',
        'event_type' => 'commercial_offer_sent',
        'fields' => [
            ['name' => 'offer_note', 'label' => 'Комментарий по КП', 'type' => 'text'],
        ],
    ],
    'issue_invoice' => [
        'label' => 'Выставить счет',
        'event_type' => 'invoice_issued',
        'fields' => [
            ['name' => 'invoice_number', 'label' => 'Номер счета', 'type' => 'text'],
        ],
    ],
    'mark_payment_received' => [
        'label' => 'Оплата получена',
        'event_type' => 'payment_received',
        'fields' => [
            ['name' => 'payment_amount', 'label' => 'Сумма оплаты', 'type' => 'text'],
        ],
    ],
    'issue_first_certificate' => [
        'label' => 'Выдать первое удостоверение',
        'event_type' => 'first_certificate_issued',
        'fields' => [
            ['name' => 'certificate_number', 'label' => 'Номер удостоверения', 'type' => 'text'],
        ],
    ],
];
?>

<h2>Карточка компании</h2>

<div><strong>Компания:</strong> <?php echo htmlspecialchars((string) $company->name); ?></div>
<div><strong>Текущая стадия:</strong> <?php echo htmlspecialchars((string) $company->stage_code); ?></div>
<div><a href="index.php?option=com_glavpro_crm&view=companies">К списку компаний</a></div>

<?php if ((int) $company->id === 0) : ?>
    <form method="post" action="index.php?option=com_glavpro_crm&task=company.createDemo">
        <button type="submit">Создать демо-компанию</button>
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
<?php endif; ?>

<h3>Доступные действия</h3>
<?php if (empty($actions)) : ?>
    <div>Нет доступных действий</div>
<?php else : ?>
    <?php foreach ($actions as $action) : ?>
        <?php $config = $actionConfig[$action] ?? null; ?>
        <?php if ($config === null) : ?>
            <div>Неизвестное действие: <?php echo htmlspecialchars((string) $action); ?></div>
            <?php continue; ?>
        <?php endif; ?>

        <form method="post" action="index.php?option=com_glavpro_crm&task=company.addEvent">
            <fieldset>
                <legend><?php echo htmlspecialchars($config['label']); ?></legend>
                <input type="hidden" name="company_id" value="<?php echo (int) $company->id; ?>">
                <input type="hidden" name="event_type" value="<?php echo htmlspecialchars($config['event_type']); ?>">

                <?php foreach ($config['fields'] as $field) : ?>
                    <div>
                        <label>
                            <?php echo htmlspecialchars($field['label']); ?>
                            <?php if ($field['type'] === 'textarea') : ?>
                                <textarea name="<?php echo htmlspecialchars($field['name']); ?>" rows="3"></textarea>
                            <?php else : ?>
                                <input type="text" name="<?php echo htmlspecialchars($field['name']); ?>">
                            <?php endif; ?>
                        </label>
                    </div>
                <?php endforeach; ?>

                <button type="submit">Сохранить событие</button>
                <?php echo HTMLHelper::_('form.token'); ?>
            </fieldset>
        </form>
    <?php endforeach; ?>
<?php endif; ?>

<h3>Инструкция/скрипт</h3>
<div><?php echo htmlspecialchars((string) $this->script); ?></div>

<h3>История событий</h3>
<?php if (empty($events)) : ?>
    <div>Событий нет</div>
<?php else : ?>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>Тип</th>
                <th>Дата</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event) : ?>
                <tr>
                    <td><?php echo htmlspecialchars((string) $event['type']); ?></td>
                    <td><?php echo htmlspecialchars($formatEventDate($event['created_at'] ?? null)); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
