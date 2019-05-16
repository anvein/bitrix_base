<?php

namespace Anvein\Base\OptionsPage;

use Anvein\Base\OptionsPage\Fields\BaseField;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

/**
 * Класс таба для страницы настроек модуля.
 */
class Tab
{
    /**
     * @var string
     */
    protected $shortTitle = '';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var BaseField[]
     */
    protected $fields = [];

    /**
     * Tab constructor.
     *
     * @param string $shortTitle - надпись на ярлычке вкладки
     * @param string $title      - заголовок на табе
     */
    public function __construct(string $shortTitle, string $title = '')
    {
        $this->shortTitle = $shortTitle;
        $this->title = $title;
    }

    /**
     * Добавляет поле в таб.
     *
     * @param BaseField $field
     *
     * @return $this
     */
    public function addField(BaseField $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Генерит html-код, с полями закладки.
     */
    public function viewFields()
    {
        foreach ($this->fields as $field) {
            $field->view();
        }
    }

    /**
     * @return string
     */
    public function getShortTitle(): string
    {
        return $this->shortTitle;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Сохраняет значения полей таба в БД.
     */
    public function saveFieldValuesToDb()
    {
        $post = Application::getInstance()->getContext()->getRequest()->getPostList()->toArray();
        if (empty($post)) {
            return;
        }

        foreach ($this->fields as $field) {
            $field->saveToDb();
        }
    }

    /**
     * Заполняет value полей таба значениями из POST.
     */
    public function fillFieldValuesFromPost()
    {
        $post = Application::getInstance()->getContext()->getRequest()->getPostList()->toArray();

        foreach ($this->fields as $field) {
            $name = $field->getName();

            if (!empty($post[$name])) {
                $field->setValue($post[$name]);
            }
        }
    }

    /**
     * Заполняет value объектов полей таба значениями из базы.
     */
    public function fillFieldValuesFromDb()
    {
        foreach ($this->fields as $field) {
            if (!$field->isInfoFields()) {
                $field->setValue($field->loadValueFromDb());
            }
        }
    }

    /**
     * Валидирует поля.
     *
     * @return array - массив с ошибками
     */
    public function validateFields(): array
    {
        $post = Application::getInstance()->getContext()->getRequest()->getPostList()->toArray();
        $errors = [];

        foreach ($this->fields as $field) {
            $name = $field->getName();

            if ($field->isRequired() && empty($post[$name])) {
                $errors[] = Loc::getMessage('anvein_base_options_field') . "\"{$field->getName()}\""
                    . Loc::getMessage('anvein_base_options_required');
            }
        }

        return $errors;
    }

    /**
     * Возвращает name всех полей таба.
     *
     * @return array
     */
    public function getNamesOfFields(): array
    {
        $names = [];

        foreach ($this->fields as $field) {
            $names[] = $field->getName();
        }

        return $names;
    }
}
