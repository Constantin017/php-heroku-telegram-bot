<?php
class TelegramBot
{
    private $uri = 'https://api.telegram.org/bot';
    private $debug = true;

    public function __construct($bot_token)
    {
        $this->uri = $this->uri . $bot_token;
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


    public function request()
    {
        $postdata = file_get_contents("php://input");
        $update = json_decode($postdata, true);

        if ($update && isset($update["message"])) {
        
            $message = $update['message'];
            $message_id = $message['message_id'];
            $from = $message['from'];
            $from_id = $from['id'];
            $text = $message['text'];
            $chat = $message['chat'];
            $chat_id = $chat['id'];

            if (false && $from_id !== $chat_id){
                $this->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Working only on private chat with user'
                ]);
                exit();
            }

            switch($text)
            {
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
                case '/chat_id':
                {   
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $chat_id
                    ]);
                    break;
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
                    if ($this->$debug){
                        $this->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat_id
                        ]);
                    }
                    break;
                }
            }
        }

    }
}
