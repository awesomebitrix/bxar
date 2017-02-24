<?php

namespace marvin255\bxar\bitrix\ibelement;

use InvalidArgumentException;
use UnexpectedValueException;
use Bitrix\Main\Loader;

/**
 * Провайдер, который испльзует элементы инфоблоков в качестве хранилища.
 *
 * @see \marvin255\bxar\repo\ProviderInterface
 */
class Provider implements \marvin255\bxar\repo\ProviderInterface
{
    /**
     * @var string
     */
    protected $iblockId = null;

    /**
     * В конструкторе передаем код или идентификатор инфоблока.
     *
     * @param string $iblock
     */
    public function __construct($iblock)
    {
        $this->iblockId = $iblock;
    }

    /**
     * @return array
     */
    public function getFieldsDescription()
    {
        $iblock = $this->getIBlockData();
        $return = $this->getIblockFields();
        //подгружаем пользовательские свойства
        $res = \CIBlockProperty::GetList(
            ['sort' => 'asc', 'id' => 'asc'],
            [
                'IBLOCK_ID' => (int) $iblock['ID'],
                'CHECK_PERMISSIONS' => 'N',
            ]
        );
        while ($ob = $res->fetch()) {
            //бросаем исключение, если код не указан или имена двух полей совпадают,
            //чтобы не приводить к путанице
            if (empty($ob['CODE'])) {
                throw new InvalidArgumentException('Empty field code for field with id '.$ob['ID']);
            } elseif (isset($return[$ob['CODE']])) {
                throw new InvalidArgumentException('Field already exists '.$ob['CODE']);
            }
            $return['property_'.$ob['CODE']] = $ob;
        }

        return $return;
    }

    /**
     * Возвращает стандартные поля элемента инфоблока.
     *
     * @return array
     */
    protected function getIblockFields()
    {
        $iblock = $this->getIBlockData();
        //получаем описание базовых полей таблицы инфоблоков
        $fields = \CIBlock::getFields($iblock['ID']);
        $return = [
            'id' => [
                'CODE' => 'ID',
                'PROPERTY_TYPE' => 'N',
            ],
            'iblock_id' => [
                'CODE' => 'IBLOCK_ID',
                'PROPERTY_TYPE' => 'N',
            ],
            'iblock_section_id' => [
                'CODE' => 'IBLOCK_SECTION_ID',
                'NAME' => $fields['IBLOCK_SECTION']['NAME'],
                'IS_REQUIRED' => $fields['IBLOCK_SECTION']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'N',
            ],
            'active' => [
                'CODE' => 'ACTIVE',
                'NAME' => $fields['ACTIVE']['NAME'],
                'IS_REQUIRED' => $fields['ACTIVE']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
            'active_from' => [
                'CODE' => 'ACTIVE_FROM',
                'NAME' => $fields['ACTIVE_FROM']['NAME'],
                'IS_REQUIRED' => $fields['ACTIVE_FROM']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'DateTime',
            ],
            'active_to' => [
                'CODE' => 'ACTIVE_TO',
                'NAME' => $fields['ACTIVE_TO']['NAME'],
                'IS_REQUIRED' => $fields['ACTIVE_TO']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'DateTime',
            ],
            'sort' => [
                'CODE' => 'SORT',
                'NAME' => $fields['SORT']['NAME'],
                'IS_REQUIRED' => $fields['SORT']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'N',
            ],
            'name' => [
                'CODE' => 'NAME',
                'NAME' => $fields['NAME']['NAME'],
                'IS_REQUIRED' => $fields['NAME']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
            'preview_picture' => [
                'CODE' => 'PREVIEW_PICTURE',
                'NAME' => $fields['PREVIEW_PICTURE']['NAME'],
                'IS_REQUIRED' => $fields['PREVIEW_PICTURE']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'F',
            ],
            'preview_text_type' => [
                'CODE' => 'PREVIEW_TEXT_TYPE',
                'NAME' => $fields['PREVIEW_TEXT_TYPE']['NAME'],
                'IS_REQUIRED' => $fields['PREVIEW_TEXT_TYPE']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
            'preview_text' => [
                'CODE' => 'PREVIEW_TEXT',
                'NAME' => $fields['PREVIEW_TEXT']['NAME'],
                'IS_REQUIRED' => $fields['PREVIEW_TEXT']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
            'detail_picture' => [
                'CODE' => 'DETAIL_PICTURE',
                'NAME' => $fields['DETAIL_PICTURE']['NAME'],
                'IS_REQUIRED' => $fields['DETAIL_PICTURE']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'F',
            ],
            'detail_text_type' => [
                'CODE' => 'DETAIL_TEXT_TYPE',
                'NAME' => $fields['DETAIL_TEXT_TYPE']['NAME'],
                'IS_REQUIRED' => $fields['DETAIL_TEXT_TYPE']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
            'detail_text' => [
                'CODE' => 'DETAIL_TEXT',
                'NAME' => $fields['DETAIL_TEXT']['NAME'],
                'IS_REQUIRED' => $fields['DETAIL_TEXT']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
            'xml_id' => [
                'CODE' => 'XML_ID',
                'NAME' => $fields['XML_ID']['NAME'],
                'IS_REQUIRED' => $fields['XML_ID']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
            'code' => [
                'CODE' => 'CODE',
                'NAME' => $fields['CODE']['NAME'],
                'IS_REQUIRED' => $fields['CODE']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
            'tags' => [
                'CODE' => 'TAGS',
                'NAME' => $fields['TAGS']['NAME'],
                'IS_REQUIRED' => $fields['TAGS']['IS_REQUIRED'],
                'PROPERTY_TYPE' => 'S',
            ],
        ];

        return $return;
    }

    /**
     * @param string                             $name
     * @param array                              $fieldData
     * @param \marvin255\bxar\repo\RepoInterface $repo
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function createFieldHandler($name, \marvin255\bxar\repo\RepoInterface $repo, array $fields)
    {
        $class = '\marvin255\bxar\model\Field';
        $field = new $class($name, $repo);

        return $field;
    }

    /**
     * @param \marvin255\bxar\query\QueryInterface $query
     * @param array                                $query
     *
     * @return array
     */
    public function search(\marvin255\bxar\query\QueryInterface $query, array $fields)
    {
        $iblock = $this->getIBlockData();
        //формируем сортировку для элементов
        $order = [
            'sort' => 'asc',
            'id' => 'desc',
        ];
        //собираем фильтр для запроса
        if ($query->getFilter()) {
            $filter = $query->getFilter();
        } else {
            $filter = [];
        }
        $filter['IBLOCK_ID'] = $iblock['ID'];
        $filter['CHECK_PERMISSIONS'] = 'N';
        //собираем пагинатор для запроса
        $nav = [];
        if ($query->getLimit()) {
            if ($query->getOffset()) {
                $nav['nPageSize'] = $query->getLimit();
                $nav['iNumPage'] = ceil($query->getOffset() / $query->getLimit()) + 1;
                $nav['bDescPageNumbering'] = false;
                $nav['bShowAll'] = false;
            } else {
                $nav['nTopCount'] = $query->getLimit();
            }
        }
        //собираем те колонки, которые нужно будет получить
        if ($query->getSelect()) {
            $select = $query->getSelect();
        } else {
            $select = [];
            foreach ($fields as $field) {
                $select[] = empty($field['ID']) ? $field['CODE'] : "PROPERTY_{$field['ID']}";
            }
        }
        //запрашиваем элементы
        $res = \CIBlockElement::GetList(
            $order,
            $filter,
            false,
            !empty($nav) ? $nav : false,
            $select
        );
        $return = [];
        while ($ob = $res->GetNext()) {
            $model = [];
            //нужно вернуть только те поля, которые есть в хранилище
            foreach ($fields as $fieldName => $fieldData) {
                $obKey = isset($fieldData['ID'])
                    ? "~PROPERTY_{$fieldData['ID']}_VALUE"
                    : "~{$fieldData['CODE']}";
                $model[$fieldName] = isset($ob[$obKey]) ? $ob[$obKey] : null;
            }
            $return[] = $model;
        }

        return $return;
    }

    /**
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return int
     */
    public function count(\marvin255\bxar\query\QueryInterface $query, array $fields)
    {
        //собираем фильтр для запроса
        if ($query->getFilter()) {
            $filter = $query->getFilter();
        } else {
            $filter = [];
        }
        $filter['IBLOCK_ID'] = $iblock['ID'];
        $filter['CHECK_PERMISSIONS'] = 'N';

        return (int) \CIBlockElement::GetList([], $filter, [], false, ['ID']);
    }

    /**
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return bool
     */
    public function save(\marvin255\bxar\model\ModelInterface $model, array $fields)
    {
        $iblock = $this->getIBlockData();
        //собираем значения модели для записи в базу
        $arLoad = ['PROPERTY_VALUES' => []];
        foreach ($fields as $key => $field) {
            $value = $model->getAttribute($key)->getValue();
            if (!empty($field['ID'])) {
                //пользовательские поля инфоблока
                if (is_array($value)) {
                    //множественные
                    foreach ($value as $valueItem) {
                        $arLoad['PROPERTY_VALUES'][$field['ID']][] = [
                            'VALUE' => $valueItem,
                        ];
                    }
                } else {
                    //обычные
                    $arLoad['PROPERTY_VALUES'][$field['ID']] = [
                        'VALUE' => $value,
                    ];
                }
            } else {
                //стандартные поля инфоблока
                $arLoad[$field['CODE']] = $value;
            }
        }
        $arLoad['IBLOCK_ID'] = $iblock['ID'];
        //получаем идентификатор текущей модели
        $modelId = $model->getAttribute('id')->getValue();
        //получаем вложенность текущих сохранений в цепочке событий
        $lockDepth = $this->getLockDepth();
        if ($lockDepth === 0) {
            //нужно создавать только одну тразакцию на всю цепочку событий
            global $DB;
            $DB->StartTransaction();
        } elseif ($lockDepth >= 5) {
            //выбрасываем исключение, если сохраняется более трех вложенных элементов
            //в событиях
            throw new \Exception('More than 5 elements recoursivly saving. Break on element with id = '.$modelId);
        }
        try {
            if ($modelId) {
                //если указан ID, то пробуем обновить элемент
                $this->update($modelId, $arLoad);
            } else {
                //если не указан ID, то создаем новый элемент
                $model->getAttribute('id')->setValue($this->insert($arLoad));
            }
            if ($lockDepth === 0) {
                //применяем транзакцию, если она запущена и сохранение успешно
                $DB->Commit();
            }
        } catch (\Exception $e) {
            if ($lockDepth === 0) {
                //откатываем транзакцию, если она запущена, но произошла ошибка
                $DB->Rollback();
            }
            //пробрасываем исключение дальше
            throw new \Exception($e->getMessage());
        }

        return true;
    }

    /**
     * Создает новый элемент в базе данных.
     *
     * @param array $load
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function insert(array $load)
    {
        $el = new \CIBlockElement();
        //блокируем элемент на время сохранения с временным ID
        $tmpId = time().'_'.mt_rand();
        $this->lockElement($tmpId, $load['IBLOCK_ID']);
        //пробуем создать новый элемент
        $id = $el->Add($arLoad);
        if (!$id) {
            throw new \Exception($el->LAST_ERROR);
        }
        //разблокируем элемент после сохранения
        $this->unlockElement($tmpId, $load['IBLOCK_ID']);

        return $id;
    }

    /**
     * Обновляет элемент в базе данных.
     *
     * @param int   $id
     * @param array $load
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function update($id, array $load)
    {
        $el = new \CIBlockElement();
        if ($this->checkLockForElement($id, $load['IBLOCK_ID'])) {
            //проверяем, чтобы не было рекурсивного сохранения элемента
            throw new \Exception('Element with ID = '.$id.' already updating. Check recoursive updates in your events.');
        } else {
            //блокируем элемент на время сохранения
            $this->lockElement($id, $load['IBLOCK_ID']);
        }
        if (!$el->Update($id, $load)) {
            //если сохранить не удалось, то бросаем исключение с последней ошибкой
            throw new \Exception($el->LAST_ERROR);
        }
        //разблокируем элемент после сохранения
        $this->unlockElement($id, $load['IBLOCK_ID']);

        return $id;
    }

    /**
     * @var array
     */
    protected static $lockList = [];

    /**
     * Вносит элемент в список блокировок для контроля за событиями.
     *
     * @param int $elementId
     * @param int $iblockId
     */
    protected function lockElement($elementId, $iblockId)
    {
        self::$lockList[$iblockId][] = $elementId;
    }

    /**
     * Убирает элемент из списка блокировок для контроля за событиями.
     *
     * @param int $elementId
     * @param int $iblockId
     */
    protected function unlockElement($elementId, $iblockId)
    {
        if (isset(self::$lockList[$iblockId])) {
            foreach (self::$lockList[$iblockId] as $key => $value) {
                if ($value !== $elementId) {
                    continue;
                }
                unset(self::$lockList[$iblockId][$key]);
                break;
            }
        }
    }

    /**
     * Возвращает правду, если элемент уже сохраняется где-то в цепочке событий.
     *
     * @param int $elementId
     * @param int $iblockId
     *
     * @return bool
     */
    protected function checkLockForElement($elementId, $iblockId)
    {
        $return = isset(self::$lockList[$iblockId]) && in_array($elementId, self::$lockList[$iblockId]);

        return $return;
    }

    /**
     * Возвращает количество элементов, которые затронуты в цепочке сохранений.
     *
     * @return int
     */
    protected function getLockDepth()
    {
        $total = 0;
        foreach (self::$lockList as $value) {
            $total += count($value);
        }

        return $total;
    }

    /**
     * Проверяет поля модели перед записью в хранилище.
     *
     * @param \marvin255\bxar\model\ModelInterface $model
     * @param array                                $fields
     *
     * @return bool
     */
    public function validate(\marvin255\bxar\model\ModelInterface $model, array $fields)
    {
        $return = true;
        foreach ($fields as $key => $field) {
            if (!isset($field['IS_REQUIRED']) || $field['IS_REQUIRED'] !== 'Y') {
                continue;
            }
            $value = $model->getAttribute($key)->getValue();
            if ($value === null || $value === '' || $value === []) {
                $model->getAttribute($key)->addError('Поле должно быть заполнено');
                $return = false;
            }
        }

        return $return;
    }

    /**
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return bool
     */
    public function delete(\marvin255\bxar\model\ModelInterface $model, array $fields)
    {
        global $DB;
        if (!$model->id->value) {
            throw new InvalidArgumentException('Model\'s id property is empty');
        }
        $DB->StartTransaction();
        if (!\CIBlockElement::Delete($model->id->value)) {
            $DB->Rollback();
            throw new UnexpectedValueException('Error while deleting element with id '.$model->id->value);
        } else {
            $DB->Commit();
        }
    }

    /**
     * @var array
     */
    protected $iblockData = null;

    /**
     * Возвращаед данные инфоблока.
     * Запрашиваем данные инфоблока только по первому требованию.
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getIBlockData()
    {
        if ($this->iblockData === null) {
            //подключаем модуль инфоблоков
            Loader::includeModule('iblock');
            //если инфоблок еще не найден, то ищем его
            //будет всего один поиск на все обращения к методу
            $filter = [
                //не проверяем права на доступ к инфоблоку, чтобы не ломать логику модели
                'CHECK_PERMISSIONS' => 'N',
            ];
            if (empty($this->iblockId)) {
                //если идентификатор пустой, то бросаем исключение
                throw new InvalidArgumentException('Empty iblock identity');
            } elseif (!is_numeric($this->iblockId)) {
                //если получили строку, в которой не только числа, то ищем по коду
                $filter['CODE'] = $this->iblockId;
            } else {
                //в общем случае ищем по идентификатору
                $filter['ID'] = $this->iblockId;
            }
            $res = \CIBlock::GetList([], $filter, false);
            if ($ob = $res->fetch()) {
                //записываем данные в параметр, чтобы не искать еще раз
                $this->iblockData = $ob;
            } else {
                //если ничего не нашли, то обязаны выбросить исключение
                throw new InvalidArgumentException('Can not find iblock for identity: '.$this->iblockId);
            }
            if ($ob = $res->fetch()) {
                //если для данного идентификатора нашли несколько инфоблоков,
                //например, блоки с одинаковым символьным кодом, то нужно выбросить исключение,
                //чтобы не приводить к путанице
                throw new InvalidArgumentException('Multiple iblocks found for identity: '.$this->iblockId);
            }
            //получаем дополнительное описание инфоблока из админки
            $this->iblockData['FIELDS'] = \CIBlock::getFields($this->iblockData['ID']);
        }

        return $this->iblockData;
    }
}
