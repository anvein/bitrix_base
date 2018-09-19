# Bitrix Base

Модуль для Битрикса с часто используемым функционалом: класс-обертка логгер, \"красивый\" построитель страниц настроек модуля и компонент формы (в будущем).

Что дает:
+ Класс-обертка для более удобного логирования
+ Удобный "построитель" страниц настроек модуля


####Построитель
Возможность добавлять поля поля настроек следующих типов ():

Пример использования:
в файле ``options.php`` вашего модуля
```
use Bitrix\Main\Loader;
use Anvein\Base\OptionsPage\Page;
use Anvein\Base\OptionsPage\Tab;

// возможные типы полей
use Anvein\Base\OptionsPage\Fields\InputText;
use Anvein\Base\OptionsPage\Fields\Separator;
use Anvein\Base\OptionsPage\Fields\Checkbox;
use Anvein\Base\OptionsPage\Fields\Select;
use Anvein\Base\OptionsPage\Fields\SelectMultiply;
use Anvein\Base\OptionsPage\Fields\Textarea;
use Anvein\Base\OptionsPage\Fields\HintBlock;

Loader::includeModule('anvein.base'); // подгрузка модуля

// формирование страницы настроек
$optPage = new Page('anvein.base'); // создание объекта страницы настроек

$tab1 = new Tab('short title', 'main title'); // создание таба

// добавить необходимые поля (они находятся в пространстве имен Anvein\Base\OptionsPage\Fields)
$tab1->addField(new Separator('ФИО ru'));
$tab1->addField(new InputText('name', 'Имя', false, 25));
$tab1->addField(new Textarea('text', 'Какой-то текст', true));
$tab1->addField(new HintBlock('Ключ для использования сервиса Яндекс.Перевода можно получить, посредством '));

$optPage->addTab($tab1); // добавить таба на страницу

$optPage->run(); // запустить построение страницы 
```

