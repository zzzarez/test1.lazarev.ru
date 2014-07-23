<?php

// & JFactory::getApplication( 'site' ) необходимая для инициализации базы дает warning
// потому отключим warning'и, для корректного XML ответа
error_reporting(0);

$messages = Array();

function debug_msg($msg) {
    global $messages;
}

//Функция выдает ответ для сервиса onpay в формате XML на чек запрос
function answer($type, $code, $pay_for, $order_amount, $order_currency, $text) {
    global $key;
    $md5 = strtoupper(md5("$type;$pay_for;$order_amount;$order_currency;$code;$key"));
    return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<result>\n<code>$code</code>\n<pay_for>$pay_for</pay_for>\n<comment>$text</comment>\n<md5>$md5</md5>\n</result>";
}

//Функция выдает ответ для сервиса onpay в формате XML на pay запрос
function answerpay($type, $code, $pay_for, $order_amount, $order_currency, $text, $onpay_id) {
    global $key;
    $md5 = strtoupper(md5("$type;$pay_for;$onpay_id;$pay_for;$order_amount;$order_currency;$code;$key"));

    return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<result>\n<code>$code</code>\n <comment>$text</comment>\n<onpay_id>$onpay_id</onpay_id>\n <pay_for>$pay_for</pay_for>\n<order_id>$pay_for</order_id>\n<md5>$md5</md5>\n</result>";
}

if (isset($_REQUEST['type'])):

    global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang, $database,
    $mosConfig_mailfrom, $mosConfig_fromname;

    /*     * * access Joomla's configuration file ** */
    $my_path = dirname(__FILE__);

    if (file_exists($my_path . "/../../../configuration.php")) {
        $absolute_path = dirname($my_path . "/../../../configuration.php");
        require_once($my_path . "/../../../configuration.php");
    }
    elseif (file_exists($my_path . "/../../configuration.php")) {
        $absolute_path = dirname($my_path . "/../../configuration.php");
        require_once($my_path . "/../../configuration.php");
    }
    elseif (file_exists($my_path . "/configuration.php")) {
        $absolute_path = dirname($my_path . "/configuration.php");
        require_once( $my_path . "/configuration.php" );
    }
    else {
        die("Joomla Configuration File not found!");
    }

    // PAYPAL STYLE способ инициализации 
    $absolute_path = realpath($absolute_path);
    if (class_exists('jconfig')) {
        define('_JEXEC', 1);
        define('JPATH_BASE', $absolute_path);
        define('DS', DIRECTORY_SEPARATOR);

        // Load the framework
        require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
        require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );

        // create the mainframe object
        $mainframe = & JFactory::getApplication('site');

        // Initialize the framework
        $mainframe->initialise();

        // load system plugin group
        JPluginHelper::importPlugin('system');

        // trigger the onBeforeStart events
        $mainframe->triggerEvent('onBeforeStart');
        $lang = & JFactory::getLanguage();
        $mosConfig_lang = $GLOBALS['mosConfig_lang'] = strtolower($lang->getBackwardLang());
        // Adjust the live site path

        $mosConfig_live_site = str_replace('/administrator/components/com_virtuemart', '', JURI::base());
        $mosConfig_absolute_path = JPATH_BASE;
    }
    else {
        define('_VALID_MOS', '1');
        require_once($mosConfig_absolute_path . '/includes/joomla.php');
        require_once($mosConfig_absolute_path . '/includes/database.php');

        $database = new database($host, $user, $password, $db, $dbprefix);
        $mainframe = new mosMainFrame($database, 'com_virtuemart', $mosConfig_absolute_path);
    }

    $my_path = dirname($_SERVER['SCRIPT_FILENAME']);
    $mambo_path = str_replace("administrator/components/com_virtuemart", "", $my_path);

    $mosConfig_absolute_path = $mambo_path;

    /*     * * Начало части VirtueMart ** */
    require_once($mosConfig_absolute_path . '/administrator/components/com_virtuemart/virtuemart.cfg.php');
    require_once( CLASSPATH . 'ps_main.php');
    require_once( CLASSPATH . 'language.class.php' );
    require_once( $mosConfig_absolute_path . '/includes/phpmailer/class.phpmailer.php');


    $mail = new PHPMailer();
    $mail->PluginDir = $mosConfig_absolute_path . '/includes/phpmailer/';
    $mail->SetLanguage("en", $mosConfig_absolute_path . '/includes/phpmailer/language/');

    /* Загрузка файла класса базы данных VirtueMart */
    require_once( CLASSPATH . 'ps_database.php' );

    /*     * * END части VirtueMart ** */
    /* Загрузка файла конфигурации OnPay */
    require_once( CLASSPATH . 'payment/ps_onpay.cfg.php' );

    $login = ONPAY_LOGIN; //Ваше "Имя пользователя" (логин) в системе OnPay.ru
    $key = ONPAY_SECRET_KEY; //Ваш "Секретный ключ для API IN" в системе OnPay.ru
    $rezult = '';

//Ответ на запрос check от OnPay (проверка наличия кода в базе данных)
    if ($_REQUEST['type'] == 'check') {
        $error = 0;
        $order_amount = $_REQUEST['order_amount'];
        $order_currency = $_REQUEST['order_currency'];
        $order_id = $pay_for = $_REQUEST['pay_for'];
        $md5 = $_REQUEST['md5'];
        $sum = floatval($order_amount);

        $order_id = intval($order_id); //Код должен быть целым числом
        //Проверка кода и переводимой за него суммы
        $qv = "	SELECT `order_id`,
					`order_total`,
					`order_status` 
			FROM #__{vm}_orders 
			WHERE 
					`order_id`='" . $order_id . "' 
					AND `order_total`>='" . $sum . "' 
					AND `order_status`='" . ONPAY_PENDING_STATUS . "'";

        $dbbt = new ps_DB;
        $dbbt->query($qv);
        $dbbt->next_record();

        //Если в базе данных найдена строка с введенным кодом для данной директории и он не просрочен, то отвечаем ОК
        if ($dbbt->num_rows() == 1) {

            //Отвечаем серверу OnPay, что все хорошо, можно принимать деньги
            $rezult = answer($_REQUEST['type'], 0, $pay_for, $order_amount, $order_currency, 'OK');
        }
        else
            $error = 1;

        //Сообщаем ошибку
        if ($error == 1)
            $rezult = answer($_REQUEST['type'], 2, $pay_for, $order_amount, $order_currency, 'Error order_id:' . $order_id . ' in order_id!=order_id, order_sum>sum or order_status!=P');
    }

//Ответ на запрос pay от OnPay (прием оплаты за код)
    if ($_REQUEST['type'] == "pay") {
        $onpay_id = $_REQUEST['onpay_id'];
        $order_id = $code = $pay_for = $_REQUEST['pay_for'];

        $order_amount = $_REQUEST['order_amount'];
        $order_currency = $_REQUEST['order_currency'];
        $balance_amount = $_REQUEST['balance_amount'];
        $balance_currency = $_REQUEST['balance_currency'];
        $exchange_rate = $_REQUEST['exchange_rate'];
        $paymentDateTime = $_REQUEST['paymentDateTime'];

        $md5 = $_REQUEST['md5'];
        $error = '';

        //Проверка входных данных
        if (empty($onpay_id)) {
            $error .="Не указан id<br>";
        }
        else {
            if (!is_numeric(intval($onpay_id))) {
                $error .="Параметр не является числом<br>";
            }
        }
        if (empty($order_amount)) {
            $error .="Не указана сумма<br>";
        }
        else {
            if (!is_numeric($order_amount)) {
                $error .="Параметр не является числом<br>";
            }
        }
        if (empty($balance_amount)) {
            $error .="Не указана сумма<br>";
        }
        else {
            if (!is_numeric(intval($balance_amount))) {
                $error .="Параметр не является числом<br>";
            }
        }
        if (empty($balance_currency)) {
            $error .="Не указана валюта<br>";
        }
        else {
            if (strlen($balance_currency) > 4) {
                $error .="Параметр слишком длинный<br>";
            }
        }
        if (empty($order_currency)) {
            $error .="Не указана валюта<br>";
        }
        else {
            if (strlen($order_currency) > 4) {
                $error .="Параметр слишком длинный<br>";
            }
        }
        if (empty($exchange_rate)) {
            $error .="Не указана сумма<br>";
        }
        else {
            if (!is_numeric($exchange_rate)) {
                $error .="Параметр не является числом<br>";
            }
        }

        //Если нет ошибок
        if (!$error) {
            //Если pay_for - число
            if (is_numeric($order_id)) {
                $order_id = intval($order_id); //Код должен быть целым числом
                $sum = floatval($order_amount);

                //Проверяем, что код есть в базе данных, и оплачиваема сумма не меньше допустимой
                $sum = $sum + 1;
                $qv = "SELECT 	`order_id`,
							`order_total`,
							`order_status` 
						FROM #__{vm}_orders 
							WHERE `order_id`='" . $order_id . "'
							AND `order_total`<='" . $sum . "'
							AND `order_status`='" . ONPAY_PENDING_STATUS . "'";

                $dbbt = new ps_DB;
                $dbbt->query($qv);
                $dbbt->next_record();

                //TODO: Не примнимать оплату при: отсуствии order_id в базе, сумме меньше заказа, при статусе заказа НЕ Ожидание (P)
                if ($dbbt->num_rows() == 1) {
                    //Создаем строку хэша с присланных данных
                    $md5fb = strtoupper(md5($_REQUEST['type'] . ";" . $pay_for . ";" . $onpay_id . ";" . $order_amount . ";" . $order_currency . ";" . $key . ""));

                    //Сверяем строчки хеша (присланную и созданную нами)
                    if ($md5fb != $md5) {
                        $rezult = answerpay($_REQUEST['type'], 7, $pay_for, $order_amount, $order_currency, 'Md5 signature is wrong', $onpay_id);
                    }
                    else {

                        $time = time();

                        //Изменяем статус заказа на ОПЛАЧЕН
                        $d['order_id'] = $order_id;    //Идентификатор записи заказа
                        $d['current_order_status'] = ONPAY_PENDING_STATUS; //Текущийщй статус заказа должен быть "Ожидание" P
                        $d['order_status'] = ONPAY_VERIFIED_STATUS;   // Новый статус заказа - ОПЛАЧЕН R
                        $d['notify_customer'] = 'N'; //Уведомлять заказчика о смене статуса
                        // Обновление состояния заказа
                        require_once ( CLASSPATH . 'ps_order_onpay.php' );

                        //Если занесение информации в базу данных прошло без ошибок,
                        $rezult = answerpay($_REQUEST['type'], 0, $pay_for, $order_amount, $order_currency, 'OK', $onpay_id);

                        $ps_order->order_status_update($d);

                        echo $rezult;
                        die;

                        /*
                          if (true)
                          {
                          $rezult=answerpay($_REQUEST['type'],0,$pay_for,$order_amount,$order_currency,'OK',$onpay_id);
                          }
                          else
                          {
                          $rezult=answerpay($_REQUEST['type'],3,$pay_for,$order_amount,
                          $order_currency,
                          'Error in mechant database queries: in order_id!=order_id, order_sum>sum or order_status!='.ONPAY_PENDING_STATUS,$onpay_id);
                          } */
                    }
                }
                else {
                    $rezult = answerpay($_REQUEST['type'], 3, $pay_for, $order_amount, $order_currency, 'Cannot find any pay rows acording to this parameters: wrong payment', $onpay_id);
                }
            }
            else { //Если pay_for - не правильный формат
                $rezult = answerpay($_REQUEST['type'], 3, $pay_for, $order_amount, $order_currency, 'Error in parameters data', $onpay_id);
            }
        }
        else //Если есть ошибки
            $rezult = answerpay($_REQUEST['type'], 3, $pay_for, $order_amount, $order_currency, 'Error in parameters data', $onpay_id);
    }

    echo $rezult;

endif; //if(isset($_REQUEST['type'])):
?>