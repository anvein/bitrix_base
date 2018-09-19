<?php

namespace Anvein\Base\OptionsPage\Fields;

use Anvein\Base\OptionsPage\Page;
use Bitrix\Main\Config\Option;

/**
 * Поле настройки типа input text.
 */
class Checkbox extends BaseField
{
    /**
     * Checkbox constructor.
     *
     * @param string $optName
     * @param string $label
     */
    public function __construct(string $optName, string $label)
    {
        $this->name = $optName;
        $this->label = $label;
    }

    /**
     * @inheritdoc
     */
    public function view()
    {
        $checked = !empty($this->value) && $this->value === 'on' ? 'checked' : '';

        echo '<tr>';
        echo '    <td class="adm-detail-content-cell-l" width="50%">';
        echo '        <label for="' . $this->name . '">' . $this->printLabelHtml() . '</label>';
        echo '    </td>';
        echo '    <td class="adm-detail-content-cell-r">';
        echo '         <input type="checkbox" name="' . $this->name . '" id="' . $this->name . '" ' . $checked . '>';
        echo '    </td>';
        echo '</tr>';
    }

    /**
     * @inheritdoc
     */
    public function saveToDb()
    {
        $val = $this->value;
        $prepVal = !empty($val) && $val === 'on' ? 'Y' : 'N';
        Option::set(Page::$moduleId, $this->name, $prepVal);

        return;
    }

    /**
     * @inheritdoc
     */
    public function loadValueFromDb()
    {
        $val = Option::get(Page::$moduleId, $this->name);

        return !empty($val) && $val === 'Y' ? 'on' : '';
    }
}
