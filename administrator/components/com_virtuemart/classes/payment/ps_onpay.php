<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

class onpay_language 
{
	var $PHPSHOP_ADMIN_CFG_ONPAY_DEBUG = "Отладочный режим";
	var $PHPSHOP_ADMIN_CFG_ONPAY_DEBUG_EXPLAIN = "Отладочный режим. Когда включен счет не выписывается.";
	var $PHPSHOP_ADMIN_CFG_ONPAY_LOGIN = "Имя пользователя";
	var $PHPSHOP_ADMIN_CFG_ONPAY_LOGIN_EXPLAIN = "*Имя пользователя в системе <a href=http://onpay.ru/><u>OnPay.ru</u></a>";
	var $PHPSHOP_ADMIN_CFG_ONPAY_ONPAY_SECRET_KEY = "Ключ для КЛЮЧ KEY API IN";
	var $PHPSHOP_ADMIN_CFG_ONPAY_ONPAY_SECRET_KEY_EXPLAIN = "*Устанавливается в <a href=http://secure.onpay.ru/><u>личном кабинете пользователя OnPay.ru</u></a> в разделе <b>Настройки магазина&nbsp;&rarr; Настройки API IN</b>";

	var $PHPSHOP_ONPAY_ORDER_STATUS_PENDING_SET = "Пользователь сделал заказ, но ещё не оплатил. Заказу присвоен статус &laquo;в процессе оплаты&raquo;.";
	var $PHPSHOP_ADMIN_CFG_ONPAY_PENDING_STATUS = "Статус готов к оплате";
	var $PHPSHOP_ADMIN_CFG_ONPAY_PENDING_STATUS_EXPLAIN = "Статус, при котором заказ <i>разрешено</i> оплатить.";

	var $PHPSHOP_ONPAY_ORDER_STATUS_SUCCESS_SET = "Пользователь успешно оплатил счёт способом оплаты %s. Заказу присвоен статус &laquo;успешной оплаты&raquo;.";
	var $PHPSHOP_ADMIN_CFG_ONPAY_STATUS_SUCCESS = "Статус успешной оплаты";
	var $PHPSHOP_ADMIN_CFG_ONPAY_STATUS_SUCCESS_EXPLAIN = "Выбранный статус будет выставлен заказу, который был успешно оплачен.";

	var $PHPSHOP_ONPAY_ERROR_GET_PAYMENT_PROPERTIES = "Не удалось получить реквизиты для оплаты по этим способом";
	var $PHPSHOP_ONPAY_ERROR_CHECK_PAYMENT_STATUS = "Не удалось проверить статус оплаты";
	var $PHPSHOP_ONPAY_ERROR_GET_PAYMENT_METHODS = "Не удалось получить список способов оплаты";

	var $PHPSHOP_ONPAY_ERROR_WRONG_ORDER_NUMBER = "Неправильный номер заказа";
	var $PHPSHOP_ONPAY_ERROR_UNDEFINED_CURRENCY = "Неизвестная валюта";
	var $PHPSHOP_ONPAY_ERROR_MIN_AMOUNT = "Минимально допустимая сумма оплаты - 5 USD";
	var $PHPSHOP_ONPAY_ERROR_NO_METHODS = "Не удалось получить доступные методы оплаты";

	var $PHPSHOP_ONPAY_PAYMENT_METHOD = "Метод оплаты";
	var $PHPSHOP_ONPAY_PAYMENT_CURRENCY = "валюта";
	var $PHPSHOP_ONPAY_PAYMENT_TOTAL = "Итого с комиссией";
	var $PHPSHOP_ONPAY_PAYMENT_COMMISSION = "Комиссия";
	var $PHPSHOP_ONPAY_PAY = "Оплатить";
	var $PHPSHOP_ONPAY_PAYMENT_METHOD_CERTIFICATION_REQUIRED = "Для оплаты этим способом требуется аттестация продавца (Выводится только при тестировании)";
	var $PHPSHOP_ONPAY_PAYMENT_METHOD_CODE = "Код метода оплаты (Выводится только при тестировании)";
}
 
class ps_onpay 
{
	var $classname = "ps_onpay";
	var $payment_code = "OPY";

	/**
	* Conctructor, that merge our language varibles to VM_LANG
	*/
	function ps_onpay() 
	{
		global $VM_LANG;
		$status = $VM_LANG->merge('onpay_language');
	}

	/**
	* Отображение параметров конфигурации этого модуля оплаты
	* @returns boolean False when the Payment method has no configration
	*/
	function show_configuration() 
	{
		global $db, $VM_LANG;
		if(htmlspecialchars( $db->sf("payment_extrainfo")) == '' ) 
		{
			$db->record[$db->row]->payment_extrainfo = '<?

			$user_email="";
			require_once(CLASSPATH."ps_database.php");
			$q2  = "SELECT * FROM #__{vm}_user_info WHERE user_info_id=\'".$db->f("user_info_id")."\'"; 
			$dbst = new ps_DB;
			$dbst->setQuery($q2);
			$dbst->query();
			$dbst->next_record();
			$user_email=$dbst->f("user_email");

			$order_id=$db->f("order_id");
			$sum=$db->f("order_total");

			$db_pm = new ps_DB;			
			$q  = "SELECT * FROM `#__{vm}_payment_method` p, `#__{vm}_order_payment` op ";
			$q .= "WHERE op.order_id=\'".$order_id."\' ";
			$q .= "AND p.payment_method_id=op.payment_method_id ";
			$db_pm->query($q);
			$db_pm->next_record();
			
			require_once(CLASSPATH."payment/ps_onpay.php");
			$onpay = new ps_onpay();
			$url=$onpay->get_onpay_url($order_id,$sum,$user_email);

			echo \'<div class="pay_form" style="text-align:left;">\', $db_pm->f("payment_method_name");
			echo \'<form style="text-align:left;" action="\'.$url.\'" method="post" target="_blank">\';
			echo \'<table><tr><td><img src="http://onpay.ru/images/onpay_logo.gif" /></td><td>&nbsp;&nbsp;</td><td><input class="enter_button" type="submit" name="submit"  value="qqqq\'.$VM_LANG->PHPSHOP_ONPAY_PAY.\'" /></td></tr></table>\';
			echo \'</form></div>\';

			?>';
		}

		/** Загрузка файла конфигурации ***/
		if($this->has_configuration())
			include_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
		else
			return false;
?>
	<table style="text-align:left">

		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_ONPAY_LOGIN; ?>:</strong></td>
			<td>
				<input type="text" name="ONPAY_LOGIN" class="inputbox" value="<? if(ONPAY_LOGIN != '') echo ONPAY_LOGIN; ?>" />
			</td>
		
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_ONPAY_LOGIN_EXPLAIN; ?></td>
			</tr>
			<tr>
				<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_ONPAY_ONPAY_SECRET_KEY; ?>:</strong></td>
			<td>
				<input type="text" name="ONPAY_SECRET_KEY" class="inputbox" value="<? if(ONPAY_SECRET_KEY != '') echo ONPAY_SECRET_KEY; ?>" />
			</td>
		
		<td>
			<?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_ONPAY_ONPAY_SECRET_KEY_EXPLAIN; ?></td>
		</tr>

		<?php
		$q = "SELECT order_status_name, order_status_code FROM #__{vm}_order_status ORDER BY list_order";
		$dbs = new ps_DB;
		$dbs->query($q);
		$order_status_code = Array();
		$order_status_name = Array();

		while ($dbs->next_record()) 
		{
			$order_status_code[] = $dbs->f("order_status_code");
			$order_status_name[] =  $dbs->f("order_status_name");
		}
		?>


		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_ONPAY_PENDING_STATUS; ?></strong></td>
			<td>
			<select name="ONPAY_PENDING_STATUS" class="inputbox" >
			<?php
				for ($i=0; $i < sizeof($order_status_code); $i++) 
				{
					if (ONPAY_PENDING_STATUS == $order_status_code[$i]) $selected = 'selected="selected"';
				else $selected = '';

				echo '<option '.$selected.' value="'.$order_status_code[$i].'">'.$order_status_name[$i].'</option>';
				}
			?>
			</select>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_ONPAY_PENDING_STATUS_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_ONPAY_STATUS_SUCCESS; ?></strong></td>
			<td>
			<select name="ONPAY_VERIFIED_STATUS" class="inputbox" >
			<?php
				for ($i=0; $i < sizeof($order_status_code); $i++) 
				{
					if (ONPAY_VERIFIED_STATUS == $order_status_code[$i]) 
						$selected = 'selected="selected"';
					else $selected = '';
					
					echo '<option '.$selected.' value="'.$order_status_code[$i].'">'.$order_status_name[$i].'</option>';
				}
			?>
			</select>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_ONPAY_STATUS_SUCCESS_EXPLAIN; ?>
			</td>
		</tr>

	</table>
	<?php
	}

  /**
  * Returns the "has_configuration" status of the module
  * @param void
  * @returns boolean True when the configuration is, false when not
  */
	function has_configuration() 
	{
		if(file_exists( CLASSPATH."payment/".$this->classname.".cfg.php" )) 
		{
			include_once( CLASSPATH."payment/".$this->classname.".cfg.php" );
		}
		else 
		{
			if(!$this->write_configuration($d)) {
				return false;
			}
		}
		return true;
	}

	/**
	* Returns the "is_writeable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is writeable, false when not
	*/
	function configfile_writeable() 
	{
		return is_writeable( CLASSPATH."payment/".$this->classname.".cfg.php" );
	}

	/**
	* Returns the "is_readable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is readable, false when not
	*/
	function configfile_readable() 
	{
		return is_readable( CLASSPATH."payment/".$this->classname.".cfg.php" );
	}

	/**
	* Writes the configuration file for this payment method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
	function write_configuration( &$d ) 
	{
		global $database, $mosConfig_live_site, $mosConfig_absolute_path, $mosConfig_lang, $mosConfig_locale;


		/** Check for empty values **/
		if (is_array($d)) 
		{
			if(!$d['ONPAY_LOGIN']) 
			{
				$my_config_array['ONPAY_LOGIN'] = '';
			}
			else $my_config_array['ONPAY_LOGIN'] = $d['ONPAY_LOGIN'];

			if(!$d['ONPAY_SECRET_KEY']) 
			{
				$my_config_array['ONPAY_SECRET_KEY'] = '';
			}
			else $my_config_array['ONPAY_SECRET_KEY'] = $d['ONPAY_SECRET_KEY'];

			if ($d['ONPAY_PENDING_STATUS']) 
				$my_config_array ['ONPAY_PENDING_STATUS'] = $d['ONPAY_PENDING_STATUS'];
			else $my_config_array ['ONPAY_PENDING_STATUS'] = 'P';

			if ($d['ONPAY_VERIFIED_STATUS']) 
				$my_config_array ['ONPAY_VERIFIED_STATUS'] = $d['ONPAY_VERIFIED_STATUS'];
			else $my_config_array ['ONPAY_VERIFIED_STATUS'] = 'R';
		}

		$config = "<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die('Direct Access to this location is not allowed.');\n\n";

		while (list($key, $value) = each($my_config_array)) 
		{
			if(substr($key, 0, 5) == 'text_') 
			{
				$config .= $value."\n";
			}
			else 
			{
				$key = strtoupper($key);
				$config .= "define ('".$key."', '".$value."');\n";
			}
		}
		$config .= "?>";

		if ($fp = fopen( CLASSPATH."payment/".$this->classname.".cfg.php", "w")) 
		{
			fputs($fp, stripslashes($config));
			fclose($fp);
			
			return true;
		}
		else return false;
	}


	//Форма для оплаты заказа через систему OnPay.ru
	//Выводится после оформления заказа. Через payment_extrainfo
	function get_onpay_url($order_id,$sum,$user_email) 
	{
		global $db;
		
		if(file_exists( CLASSPATH."payment/".$this->classname.".cfg.php" )) 
		{
			include_once( CLASSPATH."payment/".$this->classname.".cfg.php" );
		}

		$login=ONPAY_LOGIN;
		$key=ONPAY_SECRET_KEY;
		//$order_id=$db->f("order_id");
		//$sum=intval($db->f("order_total"));//$db->f("order_total");
		$path='http://'.$_SERVER['HTTP_HOST'].'/';

		$sum=floatval($sum);
		$sum_for_md5=$this->to_float($sum);
		$md5check=md5("fix;$sum_for_md5;RUR;$order_id;yes;$key"); //Создаем проверочную строку, которая защищает платежную ссылку от изменений
		$url="http://secure.onpay.ru/pay/$login?f=10&pay_mode=fix&pay_for=$order_id&price=$sum&currency=RUR&convert=yes&md5=$md5check&user_email=$user_email&url_success=$path"; //Формируем платежную ссылку

		return $url;
	}


	//Функция округления для md5
	function to_float($sum) 
	{
		if (strpos($sum, ".")) 
		{
			$sum=round($sum,2);
		}
		else {$sum=$sum.".0";}
		return $sum;
	}

	/**************************************************************************
	** name: process_payment()
	** returns:
	***************************************************************************/
	function process_payment($order_number, $order_total, &$d) 
	{
		return true;
	}
}
?>