<?php
class TelegramBot
{
    private $uri = 'https://api.telegram.org/bot';
    private $name = '';
    private $admin_list = [];

    public function __construct(string $bot_token, string $bot_name)
    {
        $this->uri = $this->uri . $bot_token;
        if (strpos('@', $bot_name) !== 0) {
            $bot_name = '@'.$bot_name;
        }
        $this->name = $bot_name;
    }

    public function __call($name, $args)
    {
        return $this->call($name, count($args) === 0 ? [] : $args[0]);
    }

    public function call($method, $params = null)
    {
        $handle = curl_init($this->uri.'/'.$method);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        
        $response = curl_exec($handle);

        return ($response) ? json_decode($response, true) : false;
    }

    public function getName()
    {
        return $this->name;
    }

    public function filterText(string $text)
    {
        return str_replace($this->name, "", $text);
    }

    private function request()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    public function serve()
    {

        $update = $this->request();

        if ($update && isset($update["message"])) {
        
            $message = $update['message'];
            
            $message_id = $message['message_id'];
            $from_id = $user_id = $message['from']['id'];
            $chat_id = $message['chat']['id'];

            $text = $this->filterText($message['text']);

            switch($text)
            {
                case '/whoami':
                {
                    $_text = "Your ID: ".$user_id;
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $_text
                    ]);
                    break;
                }
                case '/time':
                {
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => date('d.m.Y, H:i', time())
                    ]);
                    break;                    
                }
                case '/unixtime':
                {
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => time()
                    ]);
                    break;
                }
                case '/message_id':
                {   
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $message_id
                    ]);
                    break;
                }
                case '/user_id':
                {   
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $user_id
                    ]);
                    break;
                }
                case '/from_id':
                {   
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $from_id
                    ]);
                    break;
                }
                case '/chat_id':
                {
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $chat_id
                    ]);
                }
                case '/ping':
                {   
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'pong'
                    ]);
                    break;
                }
                case '/pong':
                {   
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'ping'
                    ]);
                    break;
                }
                default:
                {
                    break;
                }
            }
        }

    }
}
