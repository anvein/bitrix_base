<?php

namespace Anvein\Base\OptionsPage\Fields;

/**
 * Разделитель.
 */
class Separator extends BaseField
{
    /**
     * Separator constructor.
     *
     * @param string $label - текст на разделителе
     */
    public function __construct(string $label)
    {
        $this->setIsInfoFields(true);
        $this->label = $label;
    }

    /**
     * @inheritdoc
     */
    public function view()
    {
        echo '<tr class="heading">';
        echo '    <td colspan="2">';
        echo '        <b>' . $this->label . '</b>';
        echo '    </td>';
        echo '</tr>';
    }

    /**
     * @inheritdoc
     */
    public function saveToDb()
    {
        return;
    }

    /**
     * @inheritdoc
     */
    public function loadValueFromDb()
    {
        return;
    }
}
