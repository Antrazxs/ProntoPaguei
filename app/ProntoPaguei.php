<?php
    function element_separator($tag,$find_start_tag,$find_end_tag='"'){
        $find_start_element = explode($find_start_tag, $tag);
        $find_end_element = explode($find_end_tag, $find_start_element[1]);
        return $find_end_element[0];
    }
    function ProntoPagueiCSRF(){
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => 'https://prontopaguei.com/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_COOKIEJAR => "cookies.txt",
            CURLOPT_MAXREDIRS => -1,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);
        $result = curl_exec($curl);
        $csrfToken = element_separator($result,'<meta name="csrf-token" content="','">');
        curl_close($curl);
        return $csrfToken;
    }
    function ProntoPaguei($boleto){
        $token = ProntoPagueiCSRF();
        $header = [
            'Host: prontopaguei.com',
            'Sec-Ch-Ua: "Not(A:Brand";v="24", "Chromium";v="122"',
            'X-Csrf-Token: '.$token.'',
            'Sec-Ch-Ua-Mobile: ?0',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.6261.112 Safari/537.36',
            'Content-Type: application/json;charset=UTF-8',
            'Accept: application/json, text/plain, */*',
            'X-Requested-With: XMLHttpRequest',
            'Sec-Ch-Ua-Platform: "Windows"',
            'Origin: https://prontopaguei.com',
            'Sec-Fetch-Site: same-origin',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Dest: empty',
            'Referer: https://prontopaguei.com/parcelar-boletos',
            'Priority: u=1, i'
        ];
        $post = '{"linha":"'.$boleto.'","erro_add":true}';
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => 'https://prontopaguei.com/adicionar-boleto-parcelado',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_MAXREDIRS => -1,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_COOKIEJAR => "cookies.txt",
            CURLOPT_COOKIEFILE => "cookies.txt",
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);
        $result = curl_exec($curl);
        $result = json_decode($result,true);
        curl_close($curl);
        if(isset($result["message"]) ==false || isset($result["errors"])==false ){
            echo "ERROR: ";
            print_r($result);
            return null;
        }else{
            return $result;
        }
    }
    $response_api = ProntoPaguei("23792.7900994073213213.00000510004132501 2 97010000164122");
    print_r($response_api);
?>