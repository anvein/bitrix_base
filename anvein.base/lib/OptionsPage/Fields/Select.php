<?php

namespace Anvein\Base\OptionsPage\Fields;

use Anvein\Base\OptionsPage\Page;
use Bitrix\Main\Config\Option;
use Exception;

/**
 * Поле настройки типа select.
 */
class Select extends BaseField
{
    /**
     * Массив для хранения значений для selecta.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Select constructor.
     *
     * @param string      $name
     * @param string      $label
     * @param array       $data         - массив значений для selecta (каждый элемент должен содержать: value и label)
     * @param bool        $required
     */
    public function __construct(
        string $name,
        string $label,
        array $data,
        bool $required = false
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->setData($data);
        $this->required = $required;
    }

    /**
     * @inheritdoc
     */
    public function view()
    {
        echo '<tr>';
        echo '    <td class="adm-detail-content-cell-l" width="50%">' . $this->printLabelHtml() . '</td>';
        echo '    <td class="adm-detail-content-cell-r">';
        echo '        <select name="' . $this->name . '">';

        foreach ($this->getData() as $element) {
            $selected = !empty($this->value) && $element['value'] === $this->value ? 'selected' : '';
            echo '        <option ' . $selected . ' value="' . $element['value'] . '">' . $element['label'] . '</option>';
        }

        echo '        </select>';
        echo '    </td>';
        echo '</tr>';

        return;
    }

    /**
     * @inheritdoc
     */
    public function saveToDb()
    {
        Option::set(Page::$moduleId, $this->name, $this->value);

        return;
    }

    /**
     * @inheritdoc
     */
    public function loadValueFromDb()
    {
        return Option::get(Page::$moduleId, $this->name);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return Select
     * @throws Exception
     */
    public function setData(array $data): self
    {
        if (empty($data)) {
            throw new Exception('Массив data для Select не может быть пустым');
        }

        foreach ($data as $element) {
            if (!is_array($element) || !isset($element['value']) || !isset($element['value'])) {
                throw new Exception('Каждый элемент массива data должен быть массивом с элементами: value и label');
            }
        }

        $this->data = $data;

        return $this;
    }

}
