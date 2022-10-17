<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $dToken = headerAccessToken();
            if ($dToken != NULL) {
                if ($dToken->userId == 0 || !empty($dToken->warungId)) {
                    $bodyRequest = new stdClass();
                    $bodyRequest->warungId = $dToken->warungId;
                    $bodyRequest->name = $data['name'] ?? "";
                    $bodyRequest->description = $data['description'] ?? "";
                    $bodyRequest->imageId = $data['imageId'] ?? "";
                    $bodyRequest->status = $data['status'] ?? "inactive";
                    $bodyRequest->startDate = $data['startDate'] ?? '';
                    $bodyRequest->endDate = $data['endDate'] ?? '';
                    $bodyRequest->id = uniqid();

                    $dWarung = getWarungByUserId($dToken->userId);
                    if ($dWarung->success) {
                        $data = $dWarung->data;
                        $params = new stdClass();
                        $params->externalId = $bodyRequest->id;
                        $params->givenNames = $data->name;
                        $params->surname = $data->name;
                        $params->email = $data->email;
                        $params->phone = $data->phone;
                        $params->postalCode = $data->address->postalCode;
                        $params->subDistrictName = $data->address->subDistrictName;
                        $params->districtName = $data->address->districtName;
                        $params->street = $data->address->addressDetail;
                        $params->itemNames = $bodyRequest->name;
                        $params->itemPrices = 50000;
                        $params->itemCategory = "Iklan";

                        $dInvoices = createInvoiceXendit($params);
                        if ($dInvoices != NULL) {
                            $dataI = new stdClass();
                            $dataI->id = $dInvoices->id;
                            $dataI->amount = $params->itemPrices;
                            $dataI->fees = 5000;
                            $dataI->booking = 0;
                            $dataI->status = $dInvoices->status;
                            $dataI->invoiceUrl = $dInvoices->invoice_url;
                            $dataI->expiryAt = $dInvoices->expiry_date;

                            $dCreateInvoice = createInvoiceDb($dataI);
                            if ($dCreateInvoice->success) {
                                /// ------- Insert ads to DB
                                $bodyRequest->invoiceId = $dInvoices->id;
                                $dAds = createAds($bodyRequest);
                                if ($dAds->success) {
                                    response(200, "Berhasil pengajuan iklan", $dataI);
                                }
                            }
                        } else {
                            response(400, "Gagal pengajuan iklan");
                        }
                    }
                } else {
                    response(400, "Silahkan buka warung terlebih dahulu");
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