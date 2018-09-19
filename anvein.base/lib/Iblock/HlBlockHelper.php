<?php

namespace Anvein\Base\Iblock;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Exception;

/**
 * Класс-hepler содержащий методы для работы с hl-блоками.
 */
class HlBlockHelper
{
    /**
     * Возвращает сущность hl-блока.
     *
     * @param string $hlBlockName - код hl-блолка
     *
     * @return string - класс сущности hl-блока
     *
     * @throws Exception
     */
    public static function getHlBlockEntity(string $hlBlockName): string
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new Exception('Модуль highloadblock не установлен');
        }

        $hlblock = HighloadBlockTable::getRow([
            'filter' => ['=NAME' => $hlBlockName],
        ]);

        if (empty($hlblock)) {
            throw new Exception("Не найден hl-блок {$hlBlockName}");
        }

        $hlblock = HighloadBlockTable::getById($hlblock['ID'])->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        return $entityClass;
    }
}
