<?php

namespace Anvein\Base\OptionsPage\Fields;

abstract class BaseField
{
    /**
     * Флаг, который надо задавать true, для "информационных" полей (которые не хрянят значение в бд и т.п.)
     *
     * @var bool
     */
    protected $isInfo = false;

    protected $name = '';

    protected $size = 25;

    protected $label = '';

    protected $required = false;

    /**
     * Значение поля (как в POST)
     *
     * @var string
     */
    protected $value = '';

    /**
     * Метод генерирующий html поля.
     */
    abstract public function view();

    /**
     * Метод сохраняющий значение поля.
     * Тут должно быть реализовано преобразование значения из POST в db. ('on' -> 'Y')
     */
    abstract public function saveToDb();

    /**
     * Метод вытаскивающий значение поля из бд.
     * Тут должно быть реализовано преобразование из db в POST. ('Y' -> 'on'; 'N' -> '')
     *
     * @return mixed
     */
    abstract public function loadValueFromDb();

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $value
     *
     * @return BaseField
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Является ли поле "информационным" (не хранящим ничего в бд).
     *
     * @return bool
     */
    public function isInfoFields(): bool
    {
        return $this->isInfo;
    }

    /**
     * Задает флаг является ли поле "информационным" (не хранящим ничего в бд).
     *
     * @param bool $value
     *
     * @return BaseField
     */
    public function setIsInfoFields(bool $value): self
    {
        $this->isInfo = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Оборачивает label в тег <b>, если поле обязательно для заполенния
     *
     * @return string
     */
    protected function printLabelHtml()
    {
        $labelText = $this->label;

        if ($this->isRequired()) {
            $labelText = "<b>{$labelText}</b>";
        }

        return $labelText;
    }
}
