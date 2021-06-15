<?php

1. Создаем контстанту шаблона
const TEMPLATE = 'template/default';
2. Пути к административной панели
const ADMIN_TEMPLATE = 'core/admin/views/';
3. Куки
const COOKIE_VERSION = '1.0.0';
4. Ключ шифрования для куки
const CRYPT_KET = '';
5. Время бездействия (для администратора - время бездействия)
const COOKIE_TIME = 0;
6. Время блокировки для тех, кто подбирает пароль
const BLOCK_TIME = 3;
7. Для постраничной навигации
const QTY = 8; - количество товаров
const QTY_LINKS = 3; - количество ссылок левее и правее активных
8. Константа для хранения пути css и js для работы админпанели
const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => []
];
9. Константа для хранения пути css и js для работы юзерской части
const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => []
];