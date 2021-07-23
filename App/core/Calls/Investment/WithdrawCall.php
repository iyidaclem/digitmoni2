<?php
namespace core\Call;

class WithdrawCall{


  public function withdraw($wallet, $amount, $lifetime=null, $pow){
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => PRO_GW . PAY_BILL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "wallet=$wallet&amount=$amount&lifetime=$lifetime&pow=$pow",
    CURLOPT_HTTPHEADER => array(
      "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJCMkJDcnlwdG9QYXkiLCJzdWIiOiIwZmJhODBiZjQwZmQ5MzEiLCJpYXQiOjE1MTE5MDY3MjIsImV4cCI6MTUyMDU0NjcyMn0.-AaTOAnhne-u8ioWMJrTozph_25mQhSTQGS2cx3tx6w",
      "content-type: application/x-www-form-urlencoded"
    ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
  }
  
}