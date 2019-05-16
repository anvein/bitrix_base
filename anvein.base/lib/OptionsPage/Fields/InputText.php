<?php

namespace Anvein\Base\OptionsPage\Fields;

use Anvein\Base\OptionsPage\Page;
use Bitrix\Main\Config\Option;

/**
 * Поле настройки типа input text.
 */
class InputText extends BaseField
{
    /**
     * InputText constructor.
     *
     * @param string $name
     * @param string $label
     * @param bool   $required
     * @param int    $size
     */
    public function __construct(
        string $name,
        string $label,
        bool $required = false,
        int $size = 25
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->size = $size;
    }

    /**
     * @inheritdoc
     */
    public function view()
    {
        echo '<tr>';
        echo '    <td class="adm-detail-content-cell-l" width="50%">';
        echo '        <label for="' . $this->name . '">' . $this->printLabelHtml() . '</label>';
        echo '    </td>';
        echo '    <td class="adm-detail-content-cell-r">';
        echo '         <input type="text" name="' . $this->name . '" id="' . $this->name . '" size="' . $this->size
            . '"  value="' . $this->value . '">';
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
}
