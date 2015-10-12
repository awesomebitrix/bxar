# Аналог Active Record для 1С-Битрикс.



## Зачем?

**Основная цель - заменить код, который приходится писать в каждом проекте раз за разом:**

```php
if (CModule::IncludeModule('iblock')) {
	$result = array();
	$res = CIBlockElement::GetList(
		array('SORT' => 'ASC'),
		array('IBLOCK_ID' => 1, 'ACTIVE' => 'Y'),
		false,
		false,
		array('ID', 'NAME')
	);
	while ($ob = $res->GetNext()) {
		$result[] = $ob;
	}
}
```

**на простой и понятный:**

```php
$model = \bx\ar\element\Finder::find(['IBLOCK_ID' => 1, 'ACTIVE' => 'Y'])->all();
```

**Заменить запутанное:**

```php
$el = new CIBlockElement;

$PROP = array();
$PROP[12] = "Белый";

$arLoadProductArray = Array(
	"MODIFIED_BY"    => $USER->GetID(),
	"IBLOCK_SECTION" => false,
	"PROPERTY_VALUES"=> $PROP,
	"NAME"           => "Элемент",
	"ACTIVE"         => "Y",
	"PREVIEW_TEXT"   => "текст для списка элементов",
	"DETAIL_TEXT"    => "текст для детального просмотра",
	"DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/image.gif")
);

$PRODUCT_ID = 2;  // изменяем элемент с кодом (ID) 2
$res = $el->Update($PRODUCT_ID, $arLoadProductArray);
```

**на очевидное:**

```php
$el = \bx\ar\element\Finder::find(['ID' => 2])->one();
$el->setValues([
	'modified_by'    => $USER->GetID(), // не нужно писать все заглавными буквами
	'IBLOCK_SECTION' => false,
	'NAME'           => 'Элемент',
	'ACTIVE'         => true, // всегда хотел так сделать
	"PREVIEW_TEXT"   => "текст для списка элементов",
	"DETAIL_TEXT"    => "текст для детального просмотра",
	"DETAIL_PICTURE" => "http://site.com/image.gif", // теперь можно и по ссылке
	"property_color" => "Белый", // не нужно помнить идентификаторы свойств
]);
if ($el->save()) {
	echo "Успешно сохранено";
} else {
	print_r($el->getErrors());
	// все правильно - это полный список ошибок, разбитый по названиям полей
}
```

**И другой сахар:**

* все работает только на основе стандартного API битрикса для инфоблоков,

* можно использовать любой регистр для полей и элементов,

* запросы нескольких элементов стали быстрее,

* дополнительные функции для разных типов полей:

	* всегда можно легко получить xml_id, id и название для опции свойства типа список,

	* всегда можно легко получить список всех опций для свойства типа список,

	* дату можно как задать в любом формате, так и получить в любом формате,

	* для загрузки файлов можно указать как путь на локальном диске, так и ссылку в интернете,

	* удобная функция для изменения размеров изображения,

	* список сразу всех разделов для элемента без необходимости постоянно вспоминать про CIBlockElement::GetElementGroups,

	* для элементов множественного поля достпуен весь дополнительный функционал,

* это полноценные модели, достаточно унаследовать новый класс от базового, и весь его функционал будет доступен по всему сайту, больше не нужно писать специфический код и в news.list, и в news.detail,

* полноценная настраиваемая валидация полей,

* теперь можно указать псевдоним для функции поиска, и больше не придется заменять все коды поля по всему проекту, в случае если PROPERTY_REGION изменится на PROPERTY_PRODUCT_REGION.



## Установка

**Обычная:**

1. загрузите и распакуйте библиотеку в ваш проект, в папку /bitrix/php_interface/bxar,

2. добавьте в /bitrix/php_interface/init.php (если файла нет, то создайте его самостоятельно) строку

```php
require_once __DIR__ . '/bxar/lib/Autoloader.php';
```

**С помощью [Composer](https://getcomposer.org/doc/00-intro.md):**

1. Добавьте в ваш composer.json

```javascript
"require": {
    "marvin255/bxar": "*"
},
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/marvin255/bxar"
    }
]
```



## Использование для элементов инфоблока

Для фильтра и сортировки заботают те же правила, что и для [CIBlockElement::GetList](https://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getlist.php).


**Получить один элемент:**

```php
$model = \bx\ar\element\Finder::find(['ID' => 2])->one();
```


**Получить список из нескольких элементов:**

```php
$models = \bx\ar\element\Finder::find([
	'IBLOCK_ID' => 1,
	'ACTIVE' => 'Y'
])->setOrder(['SORT' => 'ASC'])->all();
```


**Добавить несколько условий:**

```php
$finder = \bx\ar\element\Finder::find([
	'IBLOCK_ID' => 1,
	'ACTIVE' => 'Y'
]);
if (!empty($_GET['section_id'])) {
	$finder->mergeFilterWith(['SECTION_ID' => $_GET['section_id']]);
}
$models = $finder->setOrder(['SORT' => 'ASC'])->all();
```


**Ограничить выборку по количеству элементов:**

```php
$models = \bx\ar\element\Finder::find(['IBLOCK_ID' => 1])->setLimit(10)->all();
```


**Узнать количество элементов, которые подходят под условия:**

```php
$count = \bx\ar\element\Finder::find(['IBLOCK_ID' => 1])->count();
```


**Получить массив, в котором ключами будут символьные коды элементов:**

```php
$models = \bx\ar\element\Finder::find(['IBLOCK_ID' => 1])->setIndex('CODE')->all();
if (isset($models['my_code'])) {
	// получить модель для элемента с кодом "my_code"
}
```


**Если вам не нравятся модели, то всегда можно получить массив, аналогичный news.list:**

```php
$arrays = \bx\ar\element\Finder::find(['IBLOCK_ID' => 1])->setAsArray()->all();
```


**Получить любой атрибут модели:**

Подный список атрибутов можно найти в [таблице полей элементов информационных блоков](https://dev.1c-bitrix.ru/api_help/iblock/fields.php#felement).

```php
$model = \bx\ar\element\Finder::find(['ID' => 2])->one();
$id = $model->id->value; // ID элемента инфоблока
$iblock_id = $model->iblock_id->value // ID инфоблока
$name = $model->name->value // Название элемента инфоблока
```


**Получить любое пользовательское свойство модели:**

```php
$model = \bx\ar\element\Finder::find(['ID' => 2])->one();
$city = $model->property_city->value; // Значение свойства с кодом city
$year = $model->property_year->value; // Значение свойства с кодом year
```


**Обновить элемент:**

```php
$model = \bx\ar\element\Finder::find(['ID' => 2])->one();
$model->name->value = 'test value';
$model->property_city->value = 15;
$model->save();
```

или массовое задание значений

```php
$model = \bx\ar\element\Finder::find(['ID' => 2])->one();
$model->setValues([
	'NAME'           => 'test value',
	'ACTIVE'         => false,
	"PREVIEW_TEXT"   => "текст для списка элементов",
	"DETAIL_TEXT"    => "текст для детального просмотра",
	"DETAIL_PICTURE" => "http://site.com/image.gif",
	"property_color" => "Белый",
]);
$model->save();
```


**Создать новый элемент:**

```php
//информационный блок должен быть указан обязательно!
$model = new \bx\ar\element\Element(['IBLOCK_ID' => 1]);
$model->setValues([
	'NAME'           => 'test value',
	'ACTIVE'         => false,
	"PREVIEW_TEXT"   => "текст для списка элементов",
	"DETAIL_TEXT"    => "текст для детального просмотра",
	"DETAIL_PICTURE" => "http://site.com/image.gif",
	"property_color" => "Белый",
]);
$model->save();
```


**Сахар:**

```php
$model = \bx\ar\element\Finder::find(['ID' => 2])->one();

// ссылка на картинку
$url = $model->preview_picture->path;
// ссылка на картинку 100 на 100
$url = $model->preview_picture->getResized(100, 100);

// дата в нужном формате
$date = $model->date_active_from->getValueFormatted('Y-m-d H:i:s');

// xml_id опции
$option = $model->list->xmlId;
// название опции
$option = $model->list->readable;
// список всех опций
$optionsList = $model->list->listItems;
```

[Подробнее о модели для элементов инфоблока](https://github.com/marvin255/bxar).