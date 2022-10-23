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
            if ($dBokingDetail->success && $dBokingDetail->data->status == "approved") {
                $data = $dBokingDetail->data;
                if (empty($data->invoice->id)) {
                    $params = new stdClass();
                    $paramsInvoice = new stdClass();

                    $paramsInvoice->amount = 5000;
                    $paramsInvoice->fees = 5000;
                    $bookingFee = $paramsInvoice->amount + $paramsInvoice->fees;

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

                    $params->amountBilling = ((int) $params->itemPrices) + $bookingFee;
                    $params->fees = array(
                        array(
                            "type" => "Admin Booking",
                            "value" => $bookingFee
                        )
                    );

                    $dInvoices = createInvoiceXendit($params);
                    if ($dInvoices != NULL) { 
                        $paramsInvoice->id = $dInvoices->id;
                        $paramsInvoice->booking = $params->itemPrices;
                        $paramsInvoice->status = $dInvoices->status;
                        $paramsInvoice->invoiceUrl = $dInvoices->invoice_url;
                        $paramsInvoice->expiryAt = $dInvoices->expiry_date;

                        $dCreateInvoice = createInvoiceDb($paramsInvoice);
                        if ($dCreateInvoice->success) {
                            /// ------- Update booking invoiceId
                            $bodyRequest = array(
                                "id" => $data->id,
                                "invoiceId" => $dInvoices->id
                            );
                            $dUpdateBooking = updateBooking($bodyRequest);
                            if ($dUpdateBooking) {
                                $response = new stdClass();
                                $response->id = $dInvoices->id;
                                $response->amount = $paramsInvoice->amount;
                                $response->fees = $paramsInvoice->fees;
                                $response->booking = $paramsInvoice->booking;
                                $response->status = $dInvoices->status;
                                $response->url = $dInvoices->invoice_url;
                                $response->paymentExpired = $dInvoices->expiry_date;
                                response(200, "Berhasil generate link pembayaran", $response);
                            }
                        }
                    }
                } else {
                    response(200, "Berhasil generate link pembayaran", $data->invoice);
                }
            } else if ($dBokingDetail->success && $dBokingDetail->data->status != "approved") {
                response(400, "Silahkan hubungi admin warung untuk meminta approval booking");   
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