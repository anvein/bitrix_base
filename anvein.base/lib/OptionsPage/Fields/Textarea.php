<?php

namespace Anvein\Base\OptionsPage\Fields;

use Anvein\Base\OptionsPage\Page;
use Bitrix\Main\Config\Option;

/**
 * Поле настройки типа textarea.
 */
class Textarea extends BaseField
{
    protected $height = 5;

    /**
     * Textarea constructor.
     *
     * @param string $name
     * @param string $label
     * @param bool   $required
     * @param int    $width
     * @param int    $height
     */
    public function __construct(string $name, string $label, bool $required = false, int $width = 25, int $height = 5)
    {
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->size = $width;
        $this->height = $height;
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
        echo '        <textarea name="' . $this->name . '" id="' . $this->name . '" cols="' . $this->size
            . '" rows="' . $this->height . '" >' . $this->value . '</textarea>';
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
