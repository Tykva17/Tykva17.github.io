<?php
        
$leadData = json_decode(file_get_contents("php://input"), true);

if (!$leadData) die('{"status":"error empty"}');
if ($leadData['_token'] != '873429:recorca') die('{"status":"error token"}');
unset($leadData['_token']);

send_telegram($leadData);

send_google_table($leadData);

echo '{"status":true}';

http_response_code(200);


// ---FUNCTIONS START
function send_telegram($leadData) {

        $token = '6313543795:AAEW_LpayPqsBuPzC3nVlMsCDZ9bQVOtYD0'; // токен API бота
        $chat_id = '-978622632'; // id чата с пулом заявок 

        $msg ="🔥<strong>Лид с сайта: {$_SERVER['HTTP_HOST']}</strong>" . '%0A';
        foreach($leadData as $key => $value) {
                $msg .= "<b>".$key."</b>".' >>> '.$value."%0A";
        };
        
        // CURL отправка сообщения
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/sendMessage');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'chat_id=' . $chat_id . '&parse_mode=html&text=' . $msg);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $result = curl_exec($ch);
        curl_close($ch);
}


function send_google_table($leadData){

        $url = 'https://docs.google.com/forms/u/0/d/e/1FAIpQLSc5K-lUP74u2Oav5CWaA8NH_RiE5LvYJgb2iRKwieWcvdHHNQ/formResponse';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'entry.582043782'   => $leadData['name'],
                'entry.1281340312'  => $leadData['telegram'],
                'entry.706421399'   => $leadData['comment'],
                'entry.657415439'   => $leadData['partner'],
                'entry.1821608881'  => $leadData['lang'],
        ]);
        $output = curl_exec($ch);
        curl_close($ch);

}
// ---FUNCTIONS END






