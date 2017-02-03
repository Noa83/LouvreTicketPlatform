<?php
namespace Louvre\TicketPlatformBundle;

use Doctrine\ORM\Mapping as ORM;

class Payment
{
    public function payment(){
        $token = $_POST['stripeToken'];
        $name = $_POST['name'];


        if(filter_var(!empty($name) && !empty($token))){

           $ch = curl_init();
            $data = [
                'source' => $token,
                'description' => $name
            ];

           curl_setopt_array($ch, [
               CURLOPT_URL => 'https://api.stripe.com/v1/customers',
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_USERPWD => 'sk_test_Lv5I6Urhq0UOhJwDurhD1cna',
               CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
           ]);

           $response = json_decode(curl_exec($ch));
           curl_close($ch);
           dump($response);
        }
    }
}