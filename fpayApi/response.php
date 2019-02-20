<?php
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=bensonss_ecom', 'bensonss_moa', 'Letmeinside2018');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

//require __DIR__.'/../vendor/autoload.php';

include 'fpay_Functions.php';
$fctFpay = new fpayPack();
$secretKey = '1234567890123456';

if (!empty($_GET['ORDER_ID']) && !empty($_GET['REFERENCE_ID']) && !empty($_GET['TRACK_ID'])) {
    echo 'transation effectuée avec succès';
} else {
    $ApiFPayFolder = '/home/bensonss/public_html/storage/logs/pluginfpay-test/fpayApi';
    $data = $fctFpay->receiveData($ApiFPayFolder, $secretKey);
    if ($data != null) {
        $MERCHANT_ID = $data['MERCHANT_ID'];
        $REFERENCE_ID = $data['REFERENCE_ID'];
        $TRACK_ID = $data['TRACK_ID'];
        $RESPONSE_CODE = $data['RESPONSE_CODE'];
        $REASON_CODE = $data['REASON_CODE'];
        $ORDER_ID = $data['ORDER_ID'];
        $TRANSACTION_ID = $data['TRANSACTION_ID'];
        $TRANSACTION_DATE = $data['TRANSACTION_DATE'];
        $AMOUNT = $data['AMOUNT'];
        $CURRENCY_CODE = $data['CURRENCY_CODE'];
        $TRANSACTION_STATE = $data['TRANSACTION_STATE'];
        $MERCHANT_GO = $data['MERCHANT_GO'];
        $FPAY_RETURN = $data['FPAY_RETURN'];

        $q_productOrdered = $bdd->prepare('SELECT product_id, quantity, size FROM order_product WHERE order_id in (select id from orders where reference=?)');
        $q_productOrdered->execute([$ORDER_ID]);

        $productOrdered = $q_productOrdered->fetchAll(PDO::FETCH_OBJ);

        //Get the previous order status to destock if it's AUTHORIZED
        $q_order_status_id = $bdd->prepare('SELECT order_status_id FROM orders WHERE reference=?');
        $q_order_status_id->execute([$ORDER_ID]);

        $order_status_id = $q_order_status_id->fetch(PDO::FETCH_OBJ);
        $wasAuthorized = false;
        if ($order_status_id == 1 || $order_status_id == 3 || $order_status_id == 6) {
            $wasAuthorized = true;
        }

        if ($MERCHANT_GO == "true") {
            if ($RESPONSE_CODE == 0) {
                if ($TRANSACTION_STATE == "AUTHORIZED") {
                    $q_order = $bdd->prepare('SELECT * FROM orders WHERE reference=?');
                    $q_order->execute([$ORDER_ID]);
                    $order = $q_order->fetch(PDO::FETCH_OBJ);
                    if (!is_null($order)) {
                        $q_update = $bdd->prepare('UPDATE orders SET transaction_id=?, transaction_status=?, order_status_id=1 WHERE reference=?');
                        $q_update->execute([$TRANSACTION_ID, $TRANSACTION_STATE, $ORDER_ID]);

                        updateStock($productOrdered, $bdd, true);

                    }
                }
                if ($TRANSACTION_STATE == "REVERSED") {
                    $q_update = $bdd->prepare('UPDATE orders SET transaction_id=?, transaction_status=?, order_status_id=2 WHERE reference=?');
                    $q_update->execute([$TRANSACTION_ID, $TRANSACTION_STATE, $ORDER_ID]);
                    if ($wasAuthorized) {
                        updateStock($productOrdered, $bdd, false);
                    }

                }
                if ($TRANSACTION_STATE == "CAPTURED") {
                    $q_update = $bdd->prepare('UPDATE orders SET transaction_id=?, transaction_status=?, order_status_id=3 WHERE reference=?');
                    $q_update->execute([$TRANSACTION_ID, $TRANSACTION_STATE, $ORDER_ID]);
                    if (!$wasAuthorized) {
                        updateStock($productOrdered, $bdd, true);
                    }

                }
            } else {
                if ($TRANSACTION_STATE == "EXPIRED") {
                    $q_update = $bdd->prepare('UPDATE orders SET transaction_id=?, transaction_status=?, order_status_id=4 WHERE reference=?');
                    $q_update->execute([$TRANSACTION_ID, $TRANSACTION_STATE, $ORDER_ID]);
                    if ($wasAuthorized) {
                        updateStock($productOrdered, $bdd, false);
                    }

                } else {
                    $q_update = $bdd->prepare('UPDATE orders SET transaction_id=?, transaction_status=?, order_status_id=4 WHERE reference=?');
                    $q_update->execute([$TRANSACTION_ID, $TRANSACTION_STATE, $ORDER_ID]);
                    if ($wasAuthorized) {
                        updateStock($productOrdered, $bdd, false);
                    }

                }
            }
        }
        echo $FPAY_RETURN;
    } else {
        // si data = null
        echo '{"FPAY_MESSAGE_VERSION":null,"MERCHANT_ID":"","ORDER_ID":null,"REFERENCE_ID":null,"TRACK_ID":null,"MERCHANT_GO":"false","MESSAGE_SIGNATURE":null}';
    }
}

function str_slug($title, $separator = '-')
{
    // Convert all dashes/underscores into separator
    $flip = $separator == '-' ? '_' : '-';
    $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);
    // Remove all characters that are not the separator, letters, numbers, or whitespace.
    $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));
    // Replace all separator characters and whitespace by a single separator
    $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);
    return trim($title, $separator);
}

function updateStock($productOrdered, $bdd, $destockage)
{

    foreach ($productOrdered as $key => $productordered) {

        $q_productType = $bdd->prepare('SELECT `type` FROM products WHERE id = ?');
        $q_productType->execute([$productordered->product_id]);
        $productType = $q_productType->fetch(PDO::FETCH_OBJ);

        $q_productCategory = $bdd->prepare('SELECT `slug` FROM categories WHERE id in (select `catgeory_id` from products where id = ? )');
        $q_productCategory->execute([$productordered->product_id]);
        $productCategory = $q_productCategory->fetch(PDO::FETCH_OBJ);

        $size = null;
        if ($productType == "on") {
            $start = 34;
        } else {
            $start = 39;
        }
        $cmp = 1;

        for ($size = $start; $size < $start + 7; $size += 0.5) {

            if ($productordered->size == $size) {

                break;
            } else {
                $cmp++;
            }

        }
        $q_update = null;
        if ($productCategory == "accessoires" && $productordered->slug != "embauchoirs") {
            if ($destockage) {
                $q_update = $bdd->prepare('UPDATE products SET  `quantity`=`quantity`-? WHERE id=?');

            } else {
               $q_update = $bdd->prepare('UPDATE products SET  `quantity`=`quantity`+? WHERE id=?');

            }

            $q_update->execute([$result->quantity, $productordered->product_id]);
        } else {
            if ($destockage) {
                $q_update = $bdd->prepare('UPDATE sizes SET  `value`=`value`-? WHERE product_id=? and size=?');
            } else {
                $q_update = $bdd->prepare('UPDATE sizes SET  `value`=`value`+? WHERE product_id=? and size=?');
            }

            $q_update->execute([$productordered->quantity, $productordered->product_id, 'size' . $cmp]);

            $q_update = $bdd->prepare('SELECT SUM(`value`) as quantity FROM `sizes` WHERE product_id = ?');
            $q_update->execute([$productordered->product_id]);
            $result = $q_update->fetch(PDO::FETCH_OBJ);

            $q_update = $bdd->prepare('UPDATE products SET  `quantity`=? WHERE id=?');

            $q_update->execute([$result->quantity, $productordered->product_id]);

        }

    }

}
