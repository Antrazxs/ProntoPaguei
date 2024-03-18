<?php
    namespace app;
    use Exception;
    class TelegramApi{
        public static function getChatId(string $token): ?string {
            $chatId = null;
            $endpoint = "https://api.telegram.org/bot{$token}/getUpdates";
            $content = file_get_contents($endpoint);
            $content = json_decode($content, true);
            $all_msg = $content["result"];
            $count_all_msg = count($all_msg);
            if($content == "" || $content ==null){
                return null;
            }
            $chatName = $all_msg[$count_all_msg-1]["message"]["from"]["username"];
            $chatId = $all_msg[$count_all_msg-1]["message"]["chat"]["id"];
            $chatText = $all_msg[$count_all_msg-1]["message"]["text"];
            $return_all = $chatId."|".$chatName."|".$chatText;
            if(!isset($chatText)){
                return null;
            }else{
                return $return_all;
            }
        }
        public static function SendMessage(string $IdChat,string $sendMsg):bool{
            try{
                $bot = new \TelegramBot\Api\BotApi(TOKEN);
                $bot->SendMessage($IdChat,$sendMsg);
                return true;
            }catch(\Exception $e){
                return false;
            }

        }
    }
?>