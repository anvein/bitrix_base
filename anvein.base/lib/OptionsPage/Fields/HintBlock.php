<?php

namespace Anvein\Base\OptionsPage\Fields;

/**
 * Блок "подсказка".
 */
class HintBlock extends BaseField
{
    /**
     * Separator constructor.
     *
     * @param string $text - текст на разделителе
     */
    public function __construct(string $text)
    {
        $this->setIsInfoFields(true);
        $this->label = $text;
    }

    /**
     * @inheritdoc
     */
    public function view()
    {
        echo '<tr>';
        echo '    <td colspan="2" align="center">';
        echo '        <div class="adm-info-message-wrap" align="center">';
        echo '            <div class="adm-info-message">';
        echo '                <p>' . $this->label . '</p>';
        echo '            </div>';
        echo '        </div>';
        echo '   </td>';
        echo '</tr>';

        return;
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
