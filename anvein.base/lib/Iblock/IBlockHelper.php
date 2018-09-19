<?php

namespace Anvein\Base\Iblock;

use Bitrix\Iblock\ElementTable;
use CIBlock;
use Exception;

class IBlockHelper
{
    /**
     * Возвращает элемент, который указан в "свойсве типа привязка к элементу" (без свойств, только поля).
     *
     * @param array $prop - массив свойства типа привязка к элементу (E)
     *
     * @return array - если элемент найден, то возвращается массив с этим элементом, иначе пустой массив
     */
    public static function getEPropertyValue(array $prop = []): array
    {
        if (empty($prop['LINK_IBLOCK_ID']) || empty($prop['VALUE'])) {
            return [];
        }

        $element = ElementTable::getList([
            'filter' => [
                'IBLOCK_ID' => $prop['LINK_IBLOCK_ID'],
                'ID' => $prop['VALUE'],
            ],
            'select' => [
                'ID',
                'ACTIVE',
                'ACTIVE_FROM',
                'ACTIVE_TO',
                'IBLOCK_ID',
                'CODE',
                'PREVIEW_TEXT',
                'DETAIL_TEXT',
            ],
        ])->fetch();

        if (!empty($element)) {
            return $element;
        } else {
            return [];
        }
    }

    /**
     * Возвращает элементы, которые выбраны в "множественном свойсве типа привязка к элементу" (без свойств, только поля).
     *
     * @param array $prop - массив свойства типа привязка к элементу (E)
     *
     * @return array - если элементы найдены, то возвращается массив с этими элементами, иначе пустой массив
     */
    public static function getMultiplyEPropertyValue(array $prop = [])
    {
        if (empty($prop['LINK_IBLOCK_ID']) || empty($prop['VALUE'])) {
            return [];
        }

        $result = ElementTable::getList([
            'filter' => [
                'IBLOCK_ID' => $prop['LINK_IBLOCK_ID'],
                'ID' => $prop['VALUE'],
            ],
            'select' => [
                'ID',
                'ACTIVE',
                'ACTIVE_FROM',
                'ACTIVE_TO',
                'IBLOCK_ID',
                'CODE',
                'PREVIEW_TEXT',
                'DETAIL_TEXT',
            ],
        ])->fetchAll();

        if (!empty($result)) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * Возвращает PREVIEW_TEXT из свойства типа привязка к элементу.
     *
     * @param array $prop - массив свойства
     *
     * @return string - возвр значение PREVIEW_TEXT, если он задан, иначе null
     */
    public static function getPrevTextFromEProp(array $prop = []): string
    {
        $result = self::getEPropertyValue($prop);

        return $result['PREVIEW_TEXT'];
    }

    /**
     * Возвращает DETAIL_TEXT из свойства типа привязка к элементу.
     *
     * @param array $prop - массив свойства
     *
     * @return string - возвр значение DETAIL_TEXT, если он задан, иначе null
     */
    public static function getDetailTextFromEProp(array $prop = []): string
    {
        $result = self::getEPropertyValue($prop);

        return $result['DETAIL_TEXT'];
    }

    /**
     * Возвращает массив со значениями PREVIEW_TEXT из множественного свойства типа привязка к элементу.
     *
     * @param array $prop - массив свойства
     *
     * @return array - возвр массив значений PREVIEW_TEXT, если он задан, иначе пустой массив
     */
    public static function getPrevTextFromMultiplyEProp(array $prop = []): array
    {
        $result = [];
        $resultProp = self::getMultiplyEPropertyValue($prop);

        foreach ($resultProp as $value) {
            if (!empty($value['PREVIEW_TEXT'])) {
                $result[] = $value['PREVIEW_TEXT'];
            }
        }

        return $result;
    }

    ///**
    // * Получает список элементов ИБ (как обычный ElementTable::getList())<br>
    // * но со свойствами
    // *
    // * @param array $params - параметры обычного ElementTable::getList()
    // *
    // * @return array - массив элементов или пустой массив
    // * @throws Exception
    // */
    //public static function getList(array $params)
    //{
    //    if (empty($params['filter']['IBLOCK_ID'])) {
    //        throw new Exception('Не задан IBLOCK_ID');
    //    }
    //    $params['filter']['IBLOCK_ID'] = (int) $params['filter']['IBLOCK_ID'];
    //
    //    $params['select'] = (!empty($params['select']) && is_array($params['select']))
    //        ? $params['select']
    //        : ['ID', 'IBLOCK_ID'];
    //
    //    $params['order'] = (!empty($params['order']) && is_array($params['order']))
    //        ? $params['order']
    //        : [];
    //
    //    $params['limit'] = (!empty($params['limit']) && is_int($params['limit']))
    //        ? $params['limit']
    //        : null;
    //
    //    $params['offset'] = (!empty($params['offset']) && is_int($params['offset']))
    //        ? $params['offset']
    //        : null;
    //
    //    $params['sortByFilterIds'] = empty($params['sortByFilterIds']) ? false : true;
    //
    //    $entity = OrmElementTable::createEntity(
    //        $params['filter']['IBLOCK_ID'], [
    //        'namespace' => 'app\model'
    //    ]);
    //
    //    /** @var \Bitrix\Main\Entity\Base $entity */
    //    $entityClass = $entity->getDataClass();
    //
    //    $res = $entityClass::getList([
    //        'select' => $params['select'], // имена полей, которые необходимо получить в результате
    //        'filter' => $params['filter'], // описание фильтра для WHERE и HAVING
    //        // 'group'   => $params['group'], // явное указание полей, по которым нужно группировать результат
    //        'order' => $params['order'], // параметры сортировки
    //        'limit' => $params['limit'], // количество записей
    //        'offset' => $params['offset'], // смещение для limit
    //        // 'runtime' => $params['runtime'], // динамически определенные поля
    //    ]);
    //
    //    $arElements = $entityClass::fetchAllWithProperties($res);
    //
    //    if ($params['sortByFilterIds']) {
    //        $arElements = self::sortByFilterIds($arElements, $params['filter']['ID']);
    //    }
    //
    //    return $arElements;
    //}

    /**
     * Сортирует элементы по ID в порядке $arFilterIds.
     *
     * @param array $arElements  - исходный массив элементов
     * @param array $arFilterIds - массив ID по которому нужно отсортировать
     *
     * @return array - отсортированный массив элементов
     */
    private static function sortByFilterIds(array $arElements = [], array $arFilterIds = [])
    {
        if (empty($arElements) || empty($arFilterIds)) {
            return $arElements;
        }

        $arItemsBuf = [];
        foreach ($arFilterIds as $itemId) {
            if (isset($arElements[$itemId])) {
                $arItemsBuf[] = $arElements[$itemId];
            }
        }

        return $arItemsBuf;
    }

    /**
     * Возвращает ID инфоблока по его коду.
     *
     * @param string      $code   - код ИБ
     * @param string|null $siteId - SITE_ID
     *
     * @return int|null
     */
    public static function getIblockIdByCode(string $code, string $siteId = null): int
    {
        $filter = [
            'CODE' => $code,
        ];

        if (!is_null($siteId)) {
            $filter['SITE_ID'] = $siteId;
        }

        $dbIblock = CIBlock::GetList([], $filter, false);

        $arIblock = [];
        while ($iblock = $dbIblock->Fetch()) {
            $arIblock[] = $iblock;
        }

        $iblock = reset($arIblock);

        if (!empty($iblock['ID'])) {
            return $iblock['ID'];
        }

        return null;
    }
}
