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

// формирование страницы настроек (пример)
$optPage = new Page('anvein.base');

$tab1 = new Tab('Надпись на ярлычке таба', 'Заголовок на табе');
$tab1->addField(new Separator('Надпись на разделителе секций таба'));
$tab1->addField(new InputText('name_of_sitteng', 'Надпись поля', false, 25));
$tab1->addField(new Textarea('textarea_setting', 'Поле типа textarea', true));
$tab1->addField(new HintBlock('Подсказка с текстом'));
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
    'sport',
    'Виды спорта', [
    [
        'value' => '',
        'label' => '',
    ],
    [
        'value' => 'footboal',
        'label' => 'Футбол',
    ],
    [
        'value' => 'basket',
        'label' => 'Баскетбол',
    ],
],
false,
3)
);


$tab1->addField(new Checkbox('is_people', 'Поле типа checkbox'));
$optPage->addTab($tab1);

$tab2 = new Tab('tab2', 'tab2_long');
$tab2->addField(new Separator('ФИО'));
$tab2->addField(new InputText('name', 'Имя'));
$tab2->addField(new InputText('surname', 'Фамилия'));
$optPage->addTab($tab2);

// служебная часть
$optPage->run();
