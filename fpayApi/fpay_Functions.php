<?php
class fpayPack {
		//function to encrypt data.
	function signData($data, $SECRETKEY) {
		return hash_hmac('sha256', $data, $SECRETKEY, true);
	}

	//function to add logs
	function addLog($url,$txt) {
		$fileName=$url.'\fpay'.date("Y-m-d").'.log';
		if(strpos($url,'/') !== false){
			$fileName=$url.'/fpay'.date("Y-m-d").'.log';
		}
		if (!file_exists($fileName)) 
			file_put_contents($fileName, "");

		file_put_contents($fileName,file_get_contents($fileName)." \r\n ".date("[j/m/y H:i:s]")." - $txt ");
	}
	
	//function to send data
	function sendData($BASE,$URL,$DATA) {
		$this->addLog($BASE,'<<<<==== Start sendData() ====>>>>');
		$response_code=0;
		$FPAY_MESSAGE_VERSION="3";
		$TRANSACTION_TYPE='AUTH';
		$TRANSACTION_MODE='MODE';
		
		$capture =  $DATA['TRANSACTION_CAPTURE'];

		$id_marchant = $DATA['MERCHANT_ID'];
		if (!$id_marchant || empty($id_marchant)) {
				$this->addLog($BASE,'MERCHANT_ID is null');
			return -1;
		} else {
			$MERCHANT_ID=(string)($id_marchant);
		}

		$hmac=$DATA['MERCHANT_KEY'];
		if(!$hmac || empty($hmac)){
			$this->addLog($BASE,'MERCHANT_KEY is null or empty');
			return -1;
		}
		
		$url_fpay=$DATA['FPAY_URL'];
		if(!$url_fpay || empty($url_fpay)){
			$this->addLog($BASE,'FPAY_URL is null or empty');
			return -1;
		}

		$amount_convertion=$DATA['AMOUNT_CONVERSION'];
		if ($amount_convertion=='true') {
			$converted_amount=$DATA['CONVERTED_AMOUNT'];
			if (!$converted_amount || empty($converted_amount)) {
				$this->addLog($BASE,'CONVERTED_AMOUNT is null or empty');
				return -1;
			}

			if (strlen($converted_amount)>12){
				$this->addLog($BASE,'CONVERTED_AMOUNT is too large');
				return -1;
			}
		
			$conversion_currency=$DATA['CONVERSION_CURRENCY'];
			if (!$conversion_currency || empty($conversion_currency)){
				$this->addLog($BASE,'CONVERSION_CURRENCY is null or empty');
				return -1;
			}

			if (strlen($conversion_currency > 3)) {
				$this->addLog($BASE,'CONVERSION_CURRENCY is too large');
				return -1;
			}
		} else {
			$this->addLog($BASE,'AMOUNT_CONVERSION is null. default value will be set to false');
			$amount_convertion='false';
		}
		
		$customer_message=$DATA['CUSTOMER_MESSAGE'];
		if(!empty($customer_message)) {
			if(strlen($customer_message)>512){
				$this->addLog($BASE,'CUSTOMER_MESSAGE is large. will be tranked');
				$customer_message = substr($customer_message, 0, 512); 
			}
		} else {
			$this->addLog($BASE,'CUSTOMER_MESSAGE is not present');
		}
		
		$id_order = $DATA['ORDER_ID'];
		if(!$id_order || empty($id_order)){
			$this->addLog($BASE,'ORDER_ID is null or empty');
			return -1;
		}

		$amount =$DATA['AMOUNT'];
		if(!$amount || empty($amount)){
			$this->addLog($BASE,'AMOUNT is null or empty');
			return -1;
		} else {
			if(!is_int((int)$amount)){
				$this->addLog($BASE,'AMOUT is not int');
				return -2;
			}	
		}
		
		$currency=$DATA['CURRENCY_CODE'];
		if(!$currency || empty($currency)){
			$this->addLog($BASE,'CURRENCY_CODE is null or empty');
			return -1;
		} else {
			if(!is_numeric($currency)){
				$this->addLog($BASE,'CURRENCY_CODE is not numeric');
				return -2;
			}	
		}		

		$url_marchant=$DATA['MERCHANT_URL'];
		$description = $DATA['ORDER_DETAILS'];
		$firstname = $DATA['CUSTOMER_FIRSTNAME'];
		$lastname = $DATA['CUSTOMER_LASTNAME'];
		$address = $DATA['CUSTOMER_ADDRESS'];
		$zipcode = $DATA['CUSTOMER_ZIPCODE'];
		$city = $DATA['CUSTOMER_CITY'];
		$state = $DATA['CUSTOMER_STATE'];
		$country = $DATA['CUSTOMER_COUNTRY'];
		$phone = $DATA['CUSTOMER_PHONE'];
		$lang = $DATA['LANGUAGE'];

		$email = $DATA['CUSTOMER_EMAIL'];
		if(!$email || empty($email)){
			$this->addLog($BASE,'CUSTOMER_EMAIL is null or empty');
			return -1;
		}

		$url_reponse=$DATA['FPAY_URLREPAUTO'];
		if (!$url_reponse || empty($url_reponse)) {
			$this->addLog($BASE,'FPAY_URLREPAUTO is null or empty');
			return -1;
		}
		
		$AMOUNT=(string)($amount);
		$AMOUNT_CONVERSION=(string)$amount_convertion;
		$CONVERTED_AMOUNT=(string)$converted_amount;
		$CONVERSION_CURRENCY=(string)$conversion_currency;
		$CUSTOMER_MESSAGE=(string)$customer_message;
		$CURRENCY_CODE=$currency;
		$TRANSACTION_CAPTURE = $capture;
		$CUSTOM_DATA='DATA';
		
		if (!$id_order || empty($id_order)) {
			$this->addLog($BASE,'Order ID is null or empty');
			return -1;
		} else {
			if(strlen($id_order) > 45) {
				$ORDER_ID=substr((string)($id_order), 0, 45);
			} else {
				$ORDER_ID=(string)($id_order);
			}
		}
		
		if (strlen($description) > 125) {
			$ORDER_DETAILS=substr((string)($description), 0, 125);
		} else {
			$ORDER_DETAILS=(string)($description);
		}
				
		$MERCHANT_URL=substr($url_marchant, 0, 255);
		
		if($lastname){
			if(strlen($lastname)>45)
				$CUSTOMER_LASTNAME=substr((string)($lastname), 0, 45);
			else 
				$CUSTOMER_LASTNAME=(string)($lastname);
		} else {
			$CUSTOMER_LASTNAME=' ';
		}
		
		if($firstname){
			if(strlen($firstname)>45) 
				$CUSTOMER_FIRSTNAME=substr((string)($firstname), 0, 45);
			else
				$CUSTOMER_FIRSTNAME=(string)($firstname);
		}
		else 
			$CUSTOMER_FIRSTNAME=' ';
		
		if($address){
			if(strlen($address)>255) 
				$CUSTOMER_ADDRESS=substr((string)($address), 0, 255);
			else 
				$CUSTOMER_ADDRESS=(string)($address);
		}
		else
			$CUSTOMER_ADDRESS=' ';
		
		if($zipcode){
			if(strlen($zipcode)>12) 
				$CUSTOMER_ZIPCODE=substr((string)($zipcode), 0, 12);
			else 
				$CUSTOMER_ZIPCODE=(string)($zipcode);
		}
		else 
			$CUSTOMER_ZIPCODE=' ';
		
		if($city){
			if(strlen($city)>45) 
				$CUSTOMER_CITY=substr((string)($city), 0, 45);
			else 
				$CUSTOMER_CITY=(string)($city);
		}
		else 
			$CUSTOMER_CITY=' ';
		
		if($state){
			if(strlen($state)>45) 
				$CUSTOMER_STATE=substr((string)($state), 0, 45);
			else 
				$CUSTOMER_STATE=(string)($state);
		}
		else 
			$CUSTOMER_STATE=' ';
		
		if($country){
			if(strlen($country)>45) 
				$CUSTOMER_COUNTRY=substr((string)($country), 0, 45);
			else 
				$CUSTOMER_COUNTRY=(string)($country);
		}
		else 
			$CUSTOMER_COUNTRY=' ';
		
		/* CUSTOMER_PHONE */
		if($phone){
			if(strlen($phone)>15) 
				$CUSTOMER_PHONE=substr((string)($phone), 0, 15);
			else 
				$CUSTOMER_PHONE=(string)($phone);
		} else 
			$CUSTOMER_PHONE=' ';
		
		/* CUSTOMER_EMAIL */
		if($email){
			if(strlen($email)>45) 
				$CUSTOMER_EMAIL=substr((string)($email), 0, 45);
			else 
				$CUSTOMER_EMAIL=(string)($email);
		} else 
			$CUSTOMER_EMAIL=' ';
		
		/* LANGUAGE */
		if($lang){
			if(strlen($lang)>2) 
				$LANGUAGE=substr((string)($lang), 0, 2);
			else 
				$LANGUAGE=(string)($lang);
		} else
			$LANGUAGE=' ';
		
		/* FPAY_URLREPAUTO */
		if($url_reponse){
			if(strlen($url_reponse)>125) 
				$FPAY_URLREPAUTO=substr((string)($url_reponse), 0, 125);
			else 
				$FPAY_URLREPAUTO=(string)($url_reponse);
		} else 
			$FPAY_URLREPAUTO=' ';
		
		$message='';
		
		// converte $TRANSACTION_CAPTURE to String for MESSAGE_SIGNATURE
		$TRANSACTION_CAPTURE = ($TRANSACTION_CAPTURE) ? 'true' : 'false';
		$message=$FPAY_MESSAGE_VERSION.$MERCHANT_ID.$AMOUNT.$CURRENCY_CODE.$TRANSACTION_CAPTURE.$TRANSACTION_TYPE.$TRANSACTION_MODE.$CUSTOM_DATA.$ORDER_ID.$ORDER_DETAILS.$MERCHANT_URL.$CUSTOMER_LASTNAME.$CUSTOMER_FIRSTNAME.$CUSTOMER_ADDRESS.$CUSTOMER_ZIPCODE.$CUSTOMER_CITY.$CUSTOMER_STATE.$CUSTOMER_COUNTRY.$CUSTOMER_PHONE.$CUSTOMER_EMAIL.$LANGUAGE.$FPAY_URLREPAUTO;
		$FPAY_MERCHANT_KEY = $hmac;
		$MESSAGE_SIGNATURE = hash_hmac('sha256',$message,$FPAY_MERCHANT_KEY);
		$hexdigest = bin2hex($MESSAGE_SIGNATURE);
		$MESSAGE_SIGNATURE= strtoupper($MESSAGE_SIGNATURE);
		
		//Construction of JSON Object
		$this->addLog($BASE,"==> FPAY_MESSAGE_VERSION : $FPAY_MESSAGE_VERSION");
		$this->addLog($BASE,"==> MERCHANT_ID : $MERCHANT_ID");
		$this->addLog($BASE,"==> AMOUNT : $AMOUNT");
		$this->addLog($BASE,"==> CURRENCY_CODE : $CURRENCY_CODE");
		$this->addLog($BASE,"==> TRANSACTION_CAPTURE : $TRANSACTION_CAPTURE");
		$this->addLog($BASE,"==> TRANSACTION_TYPE : $TRANSACTION_TYPE");
		$this->addLog($BASE,"==> TRANSACTION_MODE : $TRANSACTION_MODE");
		$this->addLog($BASE,"==> CUSTOM_DATA : $CUSTOM_DATA");
		$this->addLog($BASE,"==> ORDER_ID : $ORDER_ID");
		$this->addLog($BASE,"==> ORDER_DETAILS : $ORDER_DETAILS");
		$this->addLog($BASE,"==> MERCHANT_URL : $MERCHANT_URL");
		$this->addLog($BASE,"==> CUSTOMER_LASTNAME : $CUSTOMER_LASTNAME");
		$this->addLog($BASE,"==> CUSTOMER_FIRSTNAME : $CUSTOMER_FIRSTNAME");
		$this->addLog($BASE,"==> CUSTOMER_ADDRESS : $CUSTOMER_ADDRESS");
		$this->addLog($BASE,"==> CUSTOMER_ZIPCODE : $CUSTOMER_ZIPCODE");
		$this->addLog($BASE,"==> CUSTOMER_CITY : $CUSTOMER_CITY");
		$this->addLog($BASE,"==> CUSTOMER_STATE : $CUSTOMER_STATE");
		$this->addLog($BASE,"==> CUSTOMER_COUNTRY : $CUSTOMER_COUNTRY");
		$this->addLog($BASE,"==> CUSTOMER_PHONE : $CUSTOMER_PHONE");
		$this->addLog($BASE,"==> CUSTOMER_EMAIL : $CUSTOMER_EMAIL");
		$this->addLog($BASE,"==> LANGUAGE : $LANGUAGE");
		$this->addLog($BASE,"==> FPAY_URLREPAUTO : $FPAY_URLREPAUTO");
		$this->addLog($BASE,"==> AMOUNT_CONVERSION : $AMOUNT_CONVERSION");
		$this->addLog($BASE,"==> CONVERTED_AMOUNT : $CONVERTED_AMOUNT");
		$this->addLog($BASE,"==> CONVERSION_CURRENCY : $CONVERSION_CURRENCY");
		$this->addLog($BASE,"==> CUSTOMER_MESSAGE : $CUSTOMER_MESSAGE");
		$this->addLog($BASE,"==> MESSAGE_SIGNATURE : $MESSAGE_SIGNATURE");

		$retour = array(
			'FPAY_MESSAGE_VERSION'=>$FPAY_MESSAGE_VERSION,
			'MERCHANT_ID'=>$MERCHANT_ID,
			'AMOUNT' => $AMOUNT,
			'CURRENCY_CODE'  =>$CURRENCY_CODE,
			'TRANSACTION_CAPTURE'  =>$capture,
			'TRANSACTION_TYPE'=>$TRANSACTION_TYPE,
			'TRANSACTION_MODE'=>$TRANSACTION_MODE,
			'CUSTOM_DATA'=> $CUSTOM_DATA,
			'ORDER_ID'=>$ORDER_ID,
			'ORDER_DETAILS'=>$ORDER_DETAILS,
			'MERCHANT_URL'=>$MERCHANT_URL,
			'AMOUNT_CONVERSION'=>$AMOUNT_CONVERSION,
			'CONVERTED_AMOUNT'=>$CONVERTED_AMOUNT,
			'CONVERSION_CURRENCY'=>$CONVERSION_CURRENCY,
			'CUSTOMER_MESSAGE'=>$CUSTOMER_MESSAGE,
			'CUSTOMER_LASTNAME'=>$CUSTOMER_LASTNAME,
			'CUSTOMER_FIRSTNAME'=>$CUSTOMER_FIRSTNAME,
			'CUSTOMER_ADDRESS'=>$CUSTOMER_ADDRESS,
			'CUSTOMER_ZIPCODE'=>$CUSTOMER_ZIPCODE,
			'CUSTOMER_CITY'=>$CUSTOMER_CITY,
			'CUSTOMER_STATE'=>$CUSTOMER_STATE,
			'CUSTOMER_COUNTRY'=>$CUSTOMER_COUNTRY,
			'CUSTOMER_PHONE'=>$CUSTOMER_PHONE,
			'CUSTOMER_EMAIL'=>$CUSTOMER_EMAIL,
			'LANGUAGE'=>$LANGUAGE,
			'FPAY_URLREPAUTO'=>$FPAY_URLREPAUTO,
			'MESSAGE_SIGNATURE' => $MESSAGE_SIGNATURE
		);

		// script to send JSON Data to FrontEnd.
		$content = json_encode($retour);
		$curl = curl_init($url_fpay);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
		array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		// For Production Server
		//curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		
		// For Test Server
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSLVERSION,1);
		
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 201 && $status != 200) {
			//die("Error: call to URL $url_fpay failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
			$this->addLog($BASE,'== !! erreur retournee par le front curl error : ');
			$this->addLog($BASE,"<== ERREUR : Error: call to URL $url_fpay failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
			$this->addLog($BASE,'==> revoie vers page erreur : error.php ');
			
			return 101;
		}

		$this->addLog($BASE,"== send  JSON Data to Front ... ==>");
		curl_close($curl);
		//Response in JSON Format
		$response = json_decode($json_response, true);
		$RESPONSE_CODE=$response['RESPONSE_CODE'];
		$REASON_CODE=$response['REASON_CODE'];

		$this->addLog($BASE,"<== RESPONSE_CODE : $RESPONSE_CODE ");
		$this->addLog($BASE,"<== REASON_CODE : $REASON_CODE ");
		$REP=(int)$RESPONSE_CODE;
		// If Errors
		
		if ($REP!=0) {
			// Errors .
			$this->addLog($BASE,'== !! erreur retournee par le front : ');
			$this->addLog($BASE,"<== RESPONSE_CODE : $RESPONSE_CODE ");
			$this->addLog($BASE,"<== REASON_CODE : $REASON_CODE ");
		
			return 102;
		} else {
			$ORDER_ID=$response['ORDER_ID'];
			$FPAY_MESSAGE_VERSION=$response['FPAY_MESSAGE_VERSION'];
			$MERCHANT_ID=$response['MERCHANT_ID'];
			$RESPONSE_CODE=$response['RESPONSE_CODE'];
			$REASON_CODE=$response['REASON_CODE'];
			$REFERENCE_ID=$response['REFERENCE_ID'];
			$TRACK_ID=$response['TRACK_ID'];
			$FPAY_URL=$response['FPAY_URL'];
			$MESSAGE_SIGNATURE=$response['MESSAGE_SIGNATURE'];
		
			$this->addLog($BASE,"<== FPAY_MESSAGE_VERSION : $FPAY_MESSAGE_VERSION ");
			$this->addLog($BASE,"<== MERCHANT_ID : $MERCHANT_ID ");
			$this->addLog($BASE,"<== RESPONSE_CODE : $RESPONSE_CODE ");
			$this->addLog($BASE,"<== REASON_CODE : $REASON_CODE ");
			$this->addLog($BASE,"<== ORDER_ID : $ORDER_ID ");
			$this->addLog($BASE,"<== REFERENCE_ID : $REFERENCE_ID ");
			$this->addLog($BASE,"<== TRACK_ID : $TRACK_ID ");
			$this->addLog($BASE,"<== FPAY_URL : $FPAY_URL ");
			$this->addLog($BASE,"<== MESSAGE_SIGNATURE : $MESSAGE_SIGNATURE ");
			
			$dt=$FPAY_MESSAGE_VERSION.$MERCHANT_ID.$RESPONSE_CODE.$REASON_CODE.$ORDER_ID.$REFERENCE_ID.$TRACK_ID.$FPAY_URL;
			
			//send data by POST to fpay
			$home_url = $URL . '/forward_Data.php?FPAY_URL='.$FPAY_URL.'&ORDER_ID='.$ORDER_ID.'&REFERENCE_ID='.$REFERENCE_ID.'&TRACK_ID='.$TRACK_ID;
			$this->addLog($BASE,'==> send Data to URL :==> '.$home_url);
			$this->addLog($BASE,'<<<<==== Fin sendData() ====>>>>');
			
			header('Location: ' . $home_url);
			exit();
		}
	}
	
	function receiveData($package_Folder,$secretKey) {
		header('Content-type: application/json');
		header('Cache-Control: no-cache, must-revalidate');
		$this->addLog($package_Folder," >>====  D�but receiveData() =====<< ");
		$RESPONSE="ACKNOWLEDGE=OK";
		// Verifie if receive data.
		if( $data = file_get_contents("php://input"))
		{
		// Read data
			$buff = file_get_contents("php://input");
			$data = json_decode($buff, true);
			$FPAY_MESSAGE_VERSION=$data['FPAY_MESSAGE_VERSION'];
			$MERCHANT_ID=$data['MERCHANT_ID'];
			$REFERENCE_ID=$data['REFERENCE_ID'];
			$TRACK_ID=$data['TRACK_ID'];
			$RESPONSE_CODE=$data['RESPONSE_CODE'];
			$REASON_CODE=$data['REASON_CODE'];
			$ORDER_ID=$data['ORDER_ID'];
			$TRANSACTION_ID=$data['TRANSACTION_ID'];
			$TRANSACTION_DATE=$data['TRANSACTION_DATE'];
			$AMOUNT=$data['AMOUNT'];
			$CURRENCY_CODE=$data['CURRENCY_CODE'];
			$TRANSACTION_STATE=$data['TRANSACTION_STATE'];
			$AMOUNT_CONVERSION=$data['AMOUNT_CONVERSION'];
			$CONVERTED_AMOUNT=$data['CONVERTED_AMOUNT'];
			$CONVERSION_CURRENCY=$data['CONVERSION_CURRENCY'];
			$CUSTOMER_MESSAGE=$data['CUSTOMER_MESSAGE'];
			
			$MESSAGE_SIGNATURE=$data['MESSAGE_SIGNATURE'];
			
			// Build the string to hache
			$dt=$FPAY_MESSAGE_VERSION.$MERCHANT_ID.$REFERENCE_ID.$TRACK_ID.$RESPONSE_CODE.$REASON_CODE.$ORDER_ID.$TRANSACTION_ID.$TRANSACTION_DATE.$AMOUNT.$CURRENCY_CODE.$TRANSACTION_STATE;
			$digest = $this->signData($dt,$secretKey);
			$hexdigest = bin2hex($digest);
			
			$this->addLog($package_Folder," ====  Received Data =====");
			$this->addLog($package_Folder,"FPAY_MESSAGE_VERSION =".$FPAY_MESSAGE_VERSION);
			$this->addLog($package_Folder,"MERCHANT_ID =".$MERCHANT_ID);
			$this->addLog($package_Folder,"REFERENCE_ID =".$REFERENCE_ID);
			$this->addLog($package_Folder,"RESPONSE_CODE =".$RESPONSE_CODE);
			$this->addLog($package_Folder,"REASON_CODE =".$REASON_CODE);
			$this->addLog($package_Folder,"ORDER_ID =".$ORDER_ID);
			$this->addLog($package_Folder,"TRANSACTION_ID =".$TRANSACTION_ID);
			$this->addLog($package_Folder,"TRANSACTION_DATE =".$TRANSACTION_DATE);
			$this->addLog($package_Folder,"AMOUNT =".$AMOUNT);
			$this->addLog($package_Folder,"CURRENCY_CODE =".$CURRENCY_CODE);
			$this->addLog($package_Folder,"TRANSACTION_STATE =".$TRANSACTION_STATE);
			$this->addLog($package_Folder,"signature brute =".$dt);
			$this->addLog($package_Folder,"Received signature  =".strtoupper($MESSAGE_SIGNATURE));
			$this->addLog($package_Folder,"Calculated signature =".strtoupper($hexdigest));
			$AMOUNT=number_format(($AMOUNT/100), 2, '.', '');
			
			//Verifying Hash
			if(strtoupper($hexdigest)==strtoupper($MESSAGE_SIGNATURE))
			{
				$MERCHANT_GO="true";
				$this->addLog($package_Folder,"MERCHANT_GO = true");
			}
			else{
				$MERCHANT_GO="false";
						$RESPONSE="SIGNATURE INVALIDE";
				$this->addLog($package_Folder,"SIGNATURE INVALIDE  == MERCHANT_GO=false ");
			}
			$retour = array(
					'FPAY_MESSAGE_VERSION'=>($FPAY_MESSAGE_VERSION),
					'MERCHANT_ID'=>strtoupper($MERCHANT_ID),
					'ORDER_ID'    => ($ORDER_ID),
					'REFERENCE_ID'  =>($REFERENCE_ID),
					'TRACK_ID'=>($TRACK_ID),
					'MERCHANT_GO'=>($MERCHANT_GO),
					'MESSAGE_SIGNATURE' => ($MESSAGE_SIGNATURE)
			);
			
			$receive = array(
				'MERCHANT_ID'=>$MERCHANT_ID,
				'REFERENCE_ID' => $REFERENCE_ID,
				'TRACK_ID'  =>$TRACK_ID,
				'RESPONSE_CODE'  =>$RESPONSE_CODE,
				'REASON_CODE'=>$REASON_CODE,
				'ORDER_ID' => $ORDER_ID,
				'TRANSACTION_ID'  =>$TRANSACTION_ID,
				'TRANSACTION_DATE'  =>$TRANSACTION_DATE,
				'AMOUNT'=>$AMOUNT,
				'CURRENCY_CODE'  =>$CURRENCY_CODE,
				'TRANSACTION_STATE'=>$TRANSACTION_STATE,
				'MERCHANT_GO'=>$MERCHANT_GO,
				'FPAY_RETURN'=>json_encode($retour),
				'AMOUNT_CONVERSION'=>$amount_convertion,
				'CONVERTED_AMOUNT'=>$converted_amount,
				'CONVERSION_CURRENCY'=>$convertion_currency,
				'CUSTOMER_MESSAGE'=>$customer_message,
			);
			
			$this->addLog($package_Folder," ==>  data to send : ".json_encode($retour));
			$this->addLog($package_Folder," >>====  End receiveData() =====<<");
			
			return $receive;
		}
		else {
			$this->addLog($package_Folder," >>==== receiveData : input = null =====<<");
			$this->addLog($package_Folder," >>====  End receiveData() =====<<");
			return null;
		}
	}
	}
?>