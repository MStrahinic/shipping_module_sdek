<?php
/* -----------------------------------------------------------------------------------------
   $Id: sdek.php 899 2010/05/29 13:24:46 oleg_vamsoft $   

   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2007 VaM Shop
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(sdek.php,v 1.39 2003/02/05); www.oscommerce.com 
   (c) 2003	 nextcommerce (sdek.php,v 1.7 2003/08/24); www.nextcommerce.org
   (c) 2004	 xt:Commerce (sdek.php,v 1.7 2003/08/24); xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

defined('root') ? null : define('root', '/home/srv42343/htdocs/' );
include_once(root."includes/modules/CalculatePriceDeliveryCdek.php"); 
include_once(root."minidatabase.php");

  class sdek {
    var $code, $title, $description, $icon, $enabled;


    function sdek() {
      global $order;	
	
      $this->code = 'sdek';
      $this->title = MODULE_SHIPPING_SDEK_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_SDEK_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_SDEK_SORT_ORDER;
      $this->icon = DIR_WS_ICONS . 'shipping_sdek.png';
      $this->tax_class = MODULE_SHIPPING_SDEK_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_SDEK_STATUS == 'True') ? true : false);

       if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_SDEK_ZONE > 0) ) {
        $check_flag = false;
        $check_query = vam_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_TABLE_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = vam_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

   if ($check_flag == false) {
          $this->enabled = false;
        }
    }
    	
		$check_query = vam_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='STORE_ZONE'");
        $check = vam_db_fetch_array($check_query);
		$own_zone_id = $check['configuration_value'];	
	
	//переключатель Доставка по своему городу
	if (($this->enabled == true) && (MODULE_SHIPPING_SDEK_OWN_CITY_DELIVERY == 'False')){		         		        
		if (strtolower(MODULE_SHIPPING_SDEK_FROM_CITY) == strtolower($order->delivery['city'])){				
			$this->enabled = false;
		}
	}
			
	//Переключатель Доставка по своему региону
	if (($this->enabled == true) && (MODULE_SHIPPING_SDEK_OWN_REGION_DELIVERY == 'False'))	{		         		
		if ($own_zone_id == $order->delivery['zone_id']){				
			$this->enabled = false;
		}
	}
	
	//отключение доставки для отдельных городов
	if (($this->enabled == true) && (MODULE_SHIPPING_SDEK_DISABLE_CITIES !== '')){		         		        
		$disabled_cities = explode(',',MODULE_SHIPPING_SDEK_DISABLE_CITIES);
		foreach ($disabled_cities as $cityvalue){			
			if (strtolower($cityvalue) == strtolower($order->delivery['city'])){				
				$this->enabled = false;
			}
		}
	}
	
  }
// class methods
     function quote($method = '') {
		global $order, $cart, $shipping_weight, $own_city_id; 		
		$calc = new CalculatePriceDeliveryCdek();
		
		try {
			
	  	if ($this->tax_class > 0) {
        	$this->quotes['tax'] = vam_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      	}
	  
			
		//устанавливаем город-отправитель
		$check_query = vam_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_SHIPPING_SDEK_FROM_CITY'");
		$check = vam_db_fetch_array($check_query);
		$own_city_name = $check['configuration_value'];
		//echo 'Grad:'.$own_city_name.'<br>';
		
		$city_check_query = vam_db_query("select city_id from city where city_name='".$own_city_name."'");
		$city_check = vam_db_fetch_array($city_check_query);	
		$own_city_id = $city_check['city_id'];
		$calc->setSenderCityId($own_city_id);
		//echo 'Grad:'.$own_city_id.'<br>';
		
		//устанавливаем город-получатель
		$city_shipping_to_name = $order->delivery['city'];
		$city_check_query = vam_db_query("select city_id from city where city_name='".$city_shipping_to_name."'");
		$city_check = vam_db_fetch_array($city_check_query);	
		$city_shipping_id = $city_check['city_id'];
		$calc->setReceiverCityId($city_shipping_id);
		//echo 'Grad primaoca:'.$city_shipping_id.'<br>';
		
		//устанавливаем дату планируемой отправки
		$shipping_date = date("Y-m-d");
		$calc->setDateExecute($shipping_date);
		//echo 'Datum:'.$shipping_date.'<br>';
		
		//устанавливаем тариф по-умолчанию
		$calc->setTariffId('1');
		
		//устанавливаем режим доставки 
		$calc->setModeDeliveryId('1');
		
		//добавляем места в отправление
		if ($shipping_weight == 0){
				$shipping_weight = MODULE_SHIPPING_SDEK_DEFAULT_SHIPPING_WEIGHT;
				//echo 'Tezina:'.$shipping_weight.'<br>';
		}
		
		$calc->addGoodsItemBySize($shipping_weight, '40', '50', '60');
		//$calc->addGoodsItemByVolume('0.1', '0.1');
		
		if ($calc->calculate() === true) {
		$res = $calc->getResult();
		}
		/*
		echo 'Цена доставки: ' . $res['result']['price'] . 'руб.<br />';
		echo 'Срок доставки: ' . $res['result']['deliveryPeriodMin'] . '-' . 
								 $res['result']['deliveryPeriodMax'] . ' дн.<br />';
		echo 'Планируемая дата доставки: c ' . $res['result']['deliveryDateMin'] . ' по ' . $res['result']['deliveryDateMax'] . '.<br />';
		echo 'id тарифа, по которому произведён расчёт: ' . $res['result']['tariffId'] . '.<br />';
        if(array_key_exists('cashOnDelivery', $res['result'])) {
            echo 'Ограничение оплаты наличными, от (руб): ' . $res['result']['cashOnDelivery'] . '.<br />';
        }
	} else {
		$err = $calc->getError();
		if( isset($err['error']) && !empty($err) ) {
			var_dump($err);
			foreach($err['error'] as $e) {
				echo 'Код ошибки: ' . $e['code'] . '.<br />';
				echo 'Текст ошибки: ' . $e['text'] . '.<br />';
			}
		}
	}	*/
	
	} catch (Exception $e) {
    echo 'Ошибка: ' . $e->getMessage() . "<br />";
	}
	if ($method != '') $title = strip_tags($title);	
       $this->quotes = array(
            'id' => $this->code,
            'module' => MODULE_SHIPPING_SDEK_TEXT_TITLE
        );

        $this->quotes['methods'] = array(array(
            'id'    => $this->code,
            'title' => MODULE_SHIPPING_SDEK_TEXT_NOTE,
            'cost'  => $res['result']['price']
        ));

      if (vam_not_null($this->icon)) $this->quotes['icon'] = vam_image($this->icon, $this->title);
	  
      return $this->quotes;
	}

   function check() {
      if (!isset($this->_check)) {
        $check_query = vam_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_SDEK_STATUS'");
        $this->_check = vam_db_num_rows($check_query);
      }
      return $this->_check;
    }

      function install() {
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_SDEK_STATUS', 'True', '6', '0', 'vam_cfg_select_option(array(\'True\', \'False\'), ', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_SDEK_ALLOWED', '', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_SDEK_FROM_CITY', 'Москва', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_SDEK_DISABLE_CITIES', '', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_SDEK_OWN_CITY_DELIVERY', 'True', '6', '0', 'vam_cfg_select_option(array(\'True\', \'False\'), ', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_SDEK_OWN_REGION_DELIVERY', 'True', '6', '0', 'vam_cfg_select_option(array(\'True\', \'False\'), ', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_SDEK_DEFAULT_SHIPPING_WEIGHT', '0.5', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_SDEK_NATURE', '3', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_SDEK_DEBUG', 'False', '6', '0', 'vam_cfg_select_option(array(\'True\', \'False\'), ', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_SHIPPING_SDEK_TAX_CLASS', '0', '6', '0', 'vam_get_tax_class_title', 'vam_cfg_pull_down_tax_classes(', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_SHIPPING_SDEK_ZONE', '0', '6', '0', 'vam_get_zone_class_title', 'vam_cfg_pull_down_zone_classes(', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_SDEK_SORT_ORDER', '0', '6', '0', now())");
    }

    function remove() {
      vam_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

   function keys() {
      return array('MODULE_SHIPPING_SDEK_STATUS', 'MODULE_SHIPPING_SDEK_FROM_CITY', 'MODULE_SHIPPING_SDEK_DISABLE_CITIES', 'MODULE_SHIPPING_SDEK_OWN_CITY_DELIVERY', 'MODULE_SHIPPING_SDEK_OWN_REGION_DELIVERY', 'MODULE_SHIPPING_SDEK_DEFAULT_SHIPPING_WEIGHT', 'MODULE_SHIPPING_SDEK_NATURE', 'MODULE_SHIPPING_SDEK_DEBUG', 'MODULE_SHIPPING_SDEK_ALLOWED', 'MODULE_SHIPPING_SDEK_TAX_CLASS', 'MODULE_SHIPPING_SDEK_ZONE', 'MODULE_SHIPPING_SDEK_SORT_ORDER');
    }
  }
?>
