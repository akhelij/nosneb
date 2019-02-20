<?php
try
{

    $bdd = new PDO('mysql:host=localhost;dbname=bensonss_ecom', 'bensonss_moa', 'Letmeinside2018');
    die($bdd);

} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$ORDER_ID = "5bbb826023bc6";
$q_productOrdered = $bdd->prepare('SELECT product_id, quantity, size FROM order_product WHERE order_id in (select id from orders where reference=?)');
$q_productOrdered->execute([$ORDER_ID]);
$productOrdered = $q_productOrdered->fetch(PDO::FETCH_OBJ);
die($productOrdered);
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

function updateStock($productOrdered)
{
    foreach ($productOrdered as $key => $productordered) {

        $q_productType = $bdd->prepare('SELECT `type` FROM products WHERE product_id = ?');
        $q_productType->execute([$productordered['product_id']]);
        $productType = $q_productType->fetch(PDO::FETCH_OBJ);

        $size = null;
        if ($productType == "on") {
            $start = 34;
        } else {
            $start = 39;
        }
        $cmp = 1;
        for ($size = $start; $size < $start + 7; $size += 0.5) {
            if ($productordered['size'] == $size) {
                break;
            }

            $cmp++;
        }

        $q_update = $bdd->prepare('UPDATE sizes SET  `value`=`value`+? WHERE product_id=? and size=?');
        $q_update->execute([$productordered['quantity'], $productordered['product_id'], 'size' . $cmp]);
    }
}
