<?php

namespace Anvein\Base\OptionsPage\Fields;

use Anvein\Base\OptionsPage\Page;
use Bitrix\Main\Config\Option;

/**
 * Поле настройки типа multiply select.
 */
class SelectMultiply extends Select
{
    /**
     * св-во переопределено, чтобы PhpStorm не ругался на тип (тут это array, а не string)
     *
     * @var array
     */
    protected $value = [];


    /**
     * Select constructor.
     *
     * @param string      $name
     * @param string      $label
     * @param array       $data         - массив значений для selecta (каждый элемент должен содержать: value и label)
     * @param int         $rows
     */
    public function __construct(
        string $name,
        string $label,
        array $data,
        int $rows = 1
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->setData($data);
//        $this->required = $required; // не реализовано
        $this->size = $rows;
    }

    /**
     * @inheritdoc
     */
    public function view()
    {
        echo '<tr>';
        echo '    <td class="adm-detail-content-cell-l" width="50%">' . $this->printLabelHtml() . '</td>';
        echo '    <td class="adm-detail-content-cell-r">';
        echo '        <select multiple name="' . $this->name . '[]" size="' . $this->size . '">';

        foreach ($this->getData() as $element) {
            $selected = !empty($this->value) && in_array($element['value'], $this->value)
                ? 'selected'
                : '';
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
        $json = json_encode($this->value);
        Option::set(Page::$moduleId, $this->name, $json);

        return;
    }

    /**
     * @inheritdoc
     */
    public function loadValueFromDb()
    {
        return json_decode(Option::get(Page::$moduleId, $this->name), true);
    }
}
