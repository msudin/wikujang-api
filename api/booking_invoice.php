<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        $dToken = headerAccessToken();
        if (!empty($entityBody) && $dToken != NULL) {
            $dBokingDetail = getBookingDetail($data['id']);
            if ($dBokingDetail->success) {
                $data = $dBokingDetail->data;
                $params = new stdClass();
                $params->externalId = uniqid();
                $params->givenNames = $data->user->fullName;
                $params->surname = $data->user->fullName;
                $params->email = $data->user->email;
                $params->phone = $data->user->phone;
                $params->postalCode = $data->user->address->postalCode;
                $params->subDistrictName = $data->user->address->subDistrictName;
                $params->districtName = $data->user->address->districtName;
                $params->street = $data->user->address->addressDetail;
                $params->itemNames = "Booking Warung ".$data->warung->name." #".$data->id;
                $params->itemPrices = $data->dpAmount;
                $params->itemCategory = "Booking";
                $params->invoiceDuration = 3600;

                $params->amountBilling = ((int) $params->itemPrices) + 10000;
                $params->fees = array(
                    array(
                        "type" => "Admin Booking",
                        "value" => 10000
                    )
                );

                $dInvoices = createInvoiceXendit($params);
                if ($dInvoices != NULL) { 
                    $dataI = new stdClass();
                    $dataI->id = $dInvoices->id;
                    $dataI->amount = 5000;
                    $dataI->fees = 5000;
                    $dataI->booking = $params->itemPrices;
                    $dataI->status = $dInvoices->status;
                    $dataI->invoiceUrl = $dInvoices->invoice_url;
                    $dataI->expiryAt = $dInvoices->expiry_date;

                    $dCreateInvoice = createInvoiceDb($dataI);
                    if ($dCreateInvoice->success) {
                        /// ------- Update booking invoiceId
                        $bodyRequest = array(
                            "id" => $data->id,
                            "invoiceId" => $dInvoices->id
                        );
                        $dUpdateBooking = updateBooking($bodyRequest);
                        if ($dUpdateBooking) {
                            response(200, "Berhasil generate link pembayaran", $dataI);
                        }
                    }
                }
            }
        } else {
            response(400);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>