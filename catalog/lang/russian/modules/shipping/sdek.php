<?php
/* -----------------------------------------------------------------------------------------
   $Id: SDEK.php 899 2007/02/07 13:24:46 VaM $

   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2007 VaM Shop
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(SDEK.php,v 1.02 2003/02/18 03:37:00); www.oscommerce.com
   (c) 2003	 nextcommerce (SDEK.php,v 1.4 2003/08/13); www.nextcommerce.org 
   (c) 2004	 xt:Commerce (SDEK.php,v 1.4 2003/08/13); xt-commerce.com

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   SDEK_austria_1.02       	Autor:	Copyright (C) 2002 - 2003 TheMedia, Dipl.-Ing Thomas Plдnkers | http://www.themedia.at & http://www.oscommerce.at

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

define('MODULE_SHIPPING_SDEK_TEXT_TITLE', 'Доставка СДЭК');
define('MODULE_SHIPPING_SDEK_TEXT_DESCRIPTION', 'СДЭК');

define('MODULE_SHIPPING_SDEK_TEXT_NOTE','Доставка курьерской службой СДЭК');

define('MODULE_SHIPPING_SDEK_STATUS_TITLE' , 'Разрешить модуль СДЭК Экспресс');
define('MODULE_SHIPPING_SDEK_STATUS_DESC' , 'Вы хотите разрешить модуль СДЭК?');
define('MODULE_SHIPPING_SDEK_ALLOWED_TITLE' , 'Разрешённые страны');
define('MODULE_SHIPPING_SDEK_ALLOWED_DESC' , 'Укажите коды стран, для которых будет доступен данный модуль (например RU,DE (оставьте поле пустым, если хотите что б модуль был доступен покупателям из любых стран))');
define('MODULE_SHIPPING_SDEK_FROM_CITY_TITLE' , 'Город отправителя');
define('MODULE_SHIPPING_SDEK_FROM_CITY_DESC' , 'Название города, откуда осуществляется отправка.');
define('MODULE_SHIPPING_SDEK_DISABLE_CITIES_TITLE' , 'Отключить для городов');
define('MODULE_SHIPPING_SDEK_DISABLE_CITIES_DESC' , 'Города, для которых этот способ доставки не показывать, через запятую.');
define('MODULE_SHIPPING_SDEK_OWN_CITY_DELIVERY_TITLE' , 'Включить доставку по своему городу?');
define('MODULE_SHIPPING_SDEK_OWN_CITY_DELIVERY_DESC' , '');
define('MODULE_SHIPPING_SDEK_OWN_REGION_DELIVERY_TITLE' , 'Включить доставку по своему региону?');
define('MODULE_SHIPPING_SDEK_OWN_REGION_DELIVERY_DESC' , '');
define('MODULE_SHIPPING_SDEK_DEFAULT_SHIPPING_WEIGHT_TITLE' , 'Вес товара по умолчанию');
define('MODULE_SHIPPING_SDEK_DEFAULT_SHIPPING_WEIGHT_DESC' , 'Если вес товара не установлен, то используем вес по умолчанию.');
define('MODULE_SHIPPING_SDEK_NATURE_TITLE' , 'Режимы доставки');
define('MODULE_SHIPPING_SDEK_NATURE_DESC' , 'Режимы доставки (число):<br />1 — дверь-дверь (Д-Д)<br />2 — дверь-склад (Д-С)<br />3 — склад-дверь (С-Д)<br />4 — склад-склад (С-С)<br />');
define('MODULE_SHIPPING_SDEK_DEBUG_TITLE' , 'Включить режим отладки');
define('MODULE_SHIPPING_SDEK_DEBUG_DESC' , 'Будет выводиться отладочная информация.');
define('MODULE_SHIPPING_SDEK_TAX_CLASS_TITLE' , 'Налог');
define('MODULE_SHIPPING_SDEK_TAX_CLASS_DESC' , 'Использовать налог.');
define('MODULE_SHIPPING_SDEK_ZONE_TITLE' , 'Зона');
define('MODULE_SHIPPING_SDEK_ZONE_DESC' , 'Если выбрана зона, то данный модуль доставки будет виден только покупателям из выбранной зоны.');
define('MODULE_SHIPPING_SDEK_SORT_ORDER_TITLE' , 'Порядок сортировки');
define('MODULE_SHIPPING_SDEK_SORT_ORDER_DESC' , 'Порядок сортировки модуля.');
?>