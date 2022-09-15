<?php 
include_once('../helper/import.php');

function createInvoiceXendit($params) {
    /// PARAMS DATA 
    // 1. External Id Trans [externalId]
    // 2. Customer Given Names [givenNames]
    // 3. Customer Surname [surname]
    // 4. Customer Email [email]
    // 5. Customer Mobile Phone [phone]
    // 6. Customer Postal Code [postalCode]
    // 7. Customer Adress Subdistrict [subDirstrictName]
    // 8. Customer Address District [districtName]
    // 9. Customer Adress Street [street]
    // 10. Items Names [itemNames]
    // 11. Items Prices [itemPrices]
    // 12. Items Category [itemCategory]

    $amount = ((int) $params->itemPrices) + 5000;
    $body = array(
        "external_id" => "payment-link-".$params->externalId,
        "amount" => $amount,
        "description" => "Invoice #".$params->externalId,
        "invoice_duration" => 86400,
        "customer" => array(
            "given_names" => $params->givenNames,
            "surname" => $params->surname,
            "email" => $params->email,
            "mobile_number" => $params->phone,
            "addresses" => array(
                array(
                    "city" => "Lumajang",
                    "country" => "Indonesia",
                    "postal_code" => "$params->postalCode",
                    "state" => "Jawa Timur",
                    "street_line1" => "$params->street, $params->districtName",
                    "street_line2" => "Kecamatan ".$params->subDistrictName
                )
            )    
        ),
        "customer_notification_preference" => array(
            "invoice_created" => array("email"),
            "invoice_reminder" => array("email"),
            "invoice_paid" => array("email"),
            "invoice_expired" => array("email")
        ),
        "locale" => "id",
        "payment_methods" => array(
            "BCA",
            "MANDIRI",
            "BNI",
            "BRI",
            "PERMATA",
            "BSI", 
            "ALFAMART",
            "INDOMARET",
            "OVO",
            "DANA",
            "SHOPEEPAY",
            "LINKAJA",
            "QRIS"      
        ),
        "currency" => "IDR",
        "items" => array(
            array(
                "name" => $params->itemNames,
                "quantity" => 1,
                "price" => $params->itemPrices,
                "category" => $params->itemCategory,
                "url" => "https://wikujang.site/images/79c914ad211a850f81306d54f071a7ee.jpeg"
            )
        ),
        "fees" => array(
            array(
                "type" => "ADMIN",
                "value" => 5000
            )
        )
    );

    $baseUrl = "https://api.xendit.co/v2/invoices";
    $response = callAPI('POST', $baseUrl, json_encode($body));
    if ($response != NULL) {
        return $response;
    } else {
        return NULL;
    }
}
?>