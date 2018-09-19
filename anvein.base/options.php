<?php

use Bitrix\Main\Loader;
use Anvein\Base\OptionsPage\Page;
use Anvein\Base\OptionsPage\Tab;
use Anvein\Base\OptionsPage\Fields\InputText;
use Anvein\Base\OptionsPage\Fields\Separator;
use Anvein\Base\OptionsPage\Fields\Checkbox;
use Anvein\Base\OptionsPage\Fields\Select;
use Anvein\Base\OptionsPage\Fields\SelectMultiply;
use Anvein\Base\OptionsPage\Fields\Textarea;
use Anvein\Base\OptionsPage\Fields\HintBlock;

Loader::includeModule('anvein.base');

// формирование страницы настроек
$optPage = new Page('anvein.base');

$tab1 = new Tab('short title', 'main title');
$tab1->addField(new Separator('ФИО ru'));
$tab1->addField(new InputText('name', 'Имя', false, 25));
$tab1->addField(new Textarea('text', 'Какой-то текст', true));
$tab1->addField(new HintBlock('Ключ для использования сервиса Яндекс.Перевода можно получить, посредством '));
$tab1->addField(new Select(
    'sex',
    'Пол', [
        [
            'value' => '',
            'label' => '',
        ],
        [
            'value' => 'man',
            'label' => 'М',
        ],
        [
            'value' => 'woman',
            'label' => 'Ж',
        ],
    ],
    true)
);

$tab1->addField(new SelectMultiply(
    'sex2',
    'Пол', [
    [
        'value' => '',
        'label' => '',
    ],
    [
        'value' => 'man',
        'label' => 'М',
    ],
    [
        'value' => 'woman',
        'label' => 'Ж',
    ],
],
    3));


$tab1->addField(new Checkbox('is_people', 'Ты человек?'));
$tab1->addField(new Checkbox('is_people2', 'Ты человек2?'));
$tab1->addField(new Separator('ФИО2'));
$tab1->addField(new InputText('name2', 'Имя'));
$optPage->addTab($tab1);

$tab2 = new Tab('tab2', 'tab2_long');
$tab2->addField(new Separator('Фамилия'));
$tab2->addField(new InputText('name3', 'Фамилия'));
$optPage->addTab($tab2);

$tab3 = new Tab('tab3', 'tab3_long');
$tab3->addField(new Separator('Фамилия'));
$tab3->addField(new InputText('name4', 'Фамилия'));
$optPage->addTab($tab3);

// служебная часть
$optPage->run();
