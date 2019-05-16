<?php

namespace Anvein\Base\OptionsPage;

use CAdminMessage;
use CAdminTabControl;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Exception;

/**
 * Класс страницы настроек модуля.
 */
class Page
{
    /**
     * @var string
     */
    public static $moduleId = '';

    /**
     * @var Tab[]
     */
    protected $tabs = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Page constructor.
     *
     * @param string $moduleId
     */
    public function __construct(string $moduleId)
    {
        global $USER;
        global $APPLICATION;

        if (!$USER->isAdmin()) {
            $APPLICATION->authForm('Nope');
        }

        self::$moduleId = $moduleId;
    }

    /**
     * Метод запускающий генерацию страницы и выполняющий все служебные функции.
     * "Мегабыдлокодный экшен".
     */
    final public function run(): void
    {
        try {
            $this->checkUniqueNamesOptions();

            $request = Application::getInstance()->getContext()->getRequest();
            $post = $request->getPostList()->toArray();

            if ((!empty($post['save']) || !empty($post['apply']) || !empty($post['restore'])) && $request->isPost() && check_bitrix_sessid()) {
                $this->fillFieldsValuesFromPost();

                if (!$this->isValidFieldsValues()) {
                    throw new Exception($this->getErrorsHtmlForNotice());
                } else {
                    $this->save();
                    $this->fillFieldsValuesFromDb();

                    $this->showSuccessSaveMessage();
                }
            } else {
                $this->fillFieldsValuesFromDb();
            }
        } catch (Exception $e) {
            $this->showErrorMessage($e->getMessage());
        }

        $this->view();
    }

    /**
     * "Выводит" сообщение об успешном сохранении настроек страницы модуля.
     */
    protected function showSuccessSaveMessage(): void
    {
        $oCAdminMessage = new CAdminMessage('');

        $oCAdminMessage->ShowMessage([
            'MESSAGE' => Loc::getMessage('anvein_base_options_save_success'),
            'TYPE' => 'OK',
        ]);
    }

    /**
     * Выводит сообщение об ошибке.
     *
     * @param string $message
     */
    protected function showErrorMessage(string $message): void
    {
        $oCAdminMessage = new CAdminMessage('');

        $oCAdminMessage->ShowMessage([
            'MESSAGE' => $message,
            'TYPE' => 'ERROR',
        ]);
    }

    /**
     * Добавление таба на страницу.
     *
     * @param Tab $tab
     *
     * @return Page
     */
    public function addTab(Tab $tab): self
    {
        $this->tabs[] = $tab;

        return $this;
    }

    /**
     * Заполняет объекты полей данными из базы.
     */
    protected function fillFieldsValuesFromDb()
    {
        foreach ($this->tabs as $tab) {
            $tab->fillFieldValuesFromDb();
        }
    }

    /**
     * Заполняет value полей табов значениями из POST.
     */
    protected function fillFieldsValuesFromPost()
    {
        foreach ($this->tabs as $tab) {
            $tab->fillFieldValuesFromPost();
        }
    }

    /**
     * Сохраняет значения полей в БД (в битриксовую таблицу с настройками модулей).
     */
    protected function save()
    {
        foreach ($this->tabs as $tab) {
            $tab->saveFieldValuesToDb();
        }

        return;
    }

    /**
     * Генерирует html-страницы настроек.
     */
    protected function view(): void
    {
        $tabControl = new CAdminTabControl(
            'optionsTabControl',
            $this->getArrayForCAdminTabControl()
        );

        $request = Application::getInstance()->getContext()->getRequest();
        $action = sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode(self::$moduleId), LANGUAGE_ID);

        // генерация html-кода страницы настроек
        echo "<form method=\"POST\" action=\"{$action}\">";
        echo bitrix_sessid_post();

        $tabControl->begin();

        foreach ($this->tabs as $tab) {
            $tabControl->beginNextTab();
            $tab->viewFields();
            $tabControl->endTab();
        }

        $tabControl->buttons([]);
        $tabControl->end();
        echo '</form>';
    }

    /**
     * Валидирует поля.
     *
     * @return bool - true, если всё ок, иначе false
     */
    protected function isValidFieldsValues(): bool
    {
        foreach ($this->tabs as $tab) {
            $this->errors += $tab->validateFields();
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * Возвращает массив с табами для функции CAdminTabControl.
     *
     * @return array
     */
    protected function getArrayForCAdminTabControl(): array
    {
        $arTabs = [];
        foreach ($this->tabs as $key => $tab) {
            $arTabs[] = [
                'DIV' => "edit{$key}",
                'TAB' => $tab->getShortTitle(),
                'TITLE' => $tab->getTitle(),
            ];
        }

        return $arTabs;
    }

    /**
     * Генерирует html для сообщения об ошибке.
     *
     * @return string
     */
    protected function getErrorsHtmlForNotice(): string
    {
        if (!empty($this->errors)) {
            return implode('<br>', $this->errors);
        }

        return '';
    }

    /**
     * Проверяет name полей на уникальность.
     *
     * @throws Exception
     */
    protected function checkUniqueNamesOptions(): void
    {
        $names = [];
        foreach ($this->tabs as $tab) {
            $names += $tab->getNamesOfFields();
        }
        $names = array_diff($names, ['']);

        $namesCnt = array_count_values($names);
        foreach ($namesCnt as $key => $nameCnt) {
            if ($nameCnt > 1) {
                throw new Exception("name \"{$key}\" поля используется несколько ({$nameCnt}) раз");
            }
        }
    }
}
