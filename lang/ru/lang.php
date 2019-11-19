<?php return [
    'plugin' => [
        'name' => 'DTP App',
        'description' => '',
    ],
    'menu' => [
        'calls' => 'Вызовы',
        'tarifs' => 'Тарифы',
        'services' => 'Услуги',
        'statuses' => 'Статусы',
        'slides' => 'Слайдеры',
    ],
    'permissions' => [
        'manage_calls' => 'Управление вызовами',
        'manage_tarifs' => 'Управление тарифами',
        'manage_services' => 'Управление услугами',
        'manage_statuses' => 'Управление статусами вызова',
        'manage_slides' => 'Управление слайдерами',
    ],
    'calls' => [
        'coor_lat' => 'Широта',
        'coor_long' => 'Долгота',
        'address' => 'Адрес',
        'status' => 'Статус вызова',
        'employe_group_code' => 'Тип сотрудника',
        'params' => 'Основные',
        'created_at' => 'Дата и время создания',
        'addantial_comment' => 'Комментарий',
        'services' => 'Услуги',
        'map' => 'Выберите позицию на карте',
        'updated_at' => 'Время последнего изменения',
        'images' => 'Прикрепленные фото',
        'client' => 'Клиент',
        'clients' => [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'avatar' => 'Аватар',
            'phone' => 'Телефон',
        ],
        'employe' => 'Сотрудник',
    ],
    'statuses' => [
        'name' => 'Название',
        'code' => 'Код',
        'is_active' => 'Активность',
    ],
    'tarifs' => [
        'name' => 'Название',
        'description' => 'Описание',
        'employe_group_type' => 'Тип сотрудников для тарифа',
        'amount' => 'Сумма',
        'created_at' => 'Дата создания',
        'updated_at' => 'Дата обновления',
    ],
    'services' => [
        'name' => 'Название',
        'employe_group_code' => 'Тип сотрудников для услуги',
        'icon' => 'Иконка',
        'tab' => 'Таб(в Мобильном приложении)',
        'for_specialists' => 'Вызов специалиста',
        'for_masters' => 'Вызов мастера',
    ],
    'slides' => [
        'content' => 'Контент',
        'image' => 'Картинка',
        'is_active' => 'Активность',
    ],
    'messages' => [
        'locations' => [
            'not_found' => 'Координаты не найдены или не были установлены.',
            'coor_lat_required' => 'Широта не получена',
            'coor_long_required' => 'Долгота не получена',
        ],
        'name' => [
            'required' => 'Поле "имя" обязательна для заполнения',
            'min3' => 'Минимальное количество символов для поля "имя", 3',
        ],
        'surname' => [
            'required' => 'Поле "Фамилия" обязательна для заполнения',
            'min3' => 'Минимальное количество символов для поля "Фамилия", 3',
        ],
        'phone' => [
            'required' => 'Поле "Телефон" обязательна для заполнения',
            'unique' => 'Этот номер телефона уже используется',
        ],
        'password' => [
            'confirmed' => 'Подтверждение пароля объязательна',
            'min6' => 'Минимальное количество символов для поля "Фамилия", 6',
            'required' => 'Поле "Пароль" обязательна для заполнения',
        ],
        'email' => [
            'required' => 'Поле "E-mail" обязательна для заполнения',
            'min3' => 'Минимальное количество символов для поля "E-mail", 3',
            'email' => 'E-mail не актуален',
        ],
        'no_access' => 'У вас нет доступа к приложению.',
    ],
];