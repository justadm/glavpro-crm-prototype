# Демо-стенд (Joomla + компонент)

Цель: быстро поднять Joomla и установить компонент, чтобы дать гендиректору доступ посмотреть UI и логику стадий.

## Требования
- Установлен Docker.

## Запуск
1. В каталоге репозитория:

   docker compose -f deploy/docker-compose.yml up -d

Прод-режим (на сервере, привязка только к localhost):

   docker compose -p glavpro -f deploy/docker-compose.yml -f deploy/docker-compose.prod.yml up -d

2. Открыть сайт:

   http://localhost:8080

3. Пройти стандартную установку Joomla в браузере.

Параметры БД в установщике Joomla:
- Host: db
- Database: joomla
- User: joomla
- Password: joomla

## Установка компонента
1. Собрать ZIP компонента:

   bash scripts/build_component_zip.sh

2. В админке Joomla установить архив из `dist/com_glavpro_crm.zip`.

## Демо-данные
- SQL для демо: `component/com_glavpro_crm/administrator/components/com_glavpro_crm/sql/demo_seed.sql`
- Альтернатива: в админке компонента есть кнопка создания демо-компаний.

## Доступ для гендира
Вариант A (быстрее): дать временный доступ в админку Joomla.
- Создать пользователя (например, `ceo_viewer`) и выдать минимально достаточные права на компонент.
- После просмотра удалить пользователя.

Вариант B (без админки): если хочешь, я добавлю публичную read-only страницу компонента с токеном в URL.
