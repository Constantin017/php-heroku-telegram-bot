<?php
class TelegramBot
{
    private $uri = 'https://api.telegram.org/bot';
    private $name = '';
    private $admin_list = [];

    public function __construct($bot_token, $bot_name)
    {
        $this->uri = $this->uri . $bot_token;
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
        return str_replace("@".$this->name, "", $text);
    }

    private function request()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    public function setAdmins(string $admin_list)
    {
        $this->admin_list = explode(';', $admin_list);
    }

    public function isAdmin($user_id)
    {
        if ( !empty($this->admin_list) && is_array($this->admin_list) )
        {
            return ( in_array($user_id, $this->admin_list) ) ? true : false;
        }
        return true;
    }

    public function serve()
    {

        $update = $this->request();

        if ($update && isset($update["message"])) {
        
            $message = $update['message'];
            
            $message_id = $message['message_id'];
            
            $from = $message['from'];
            $from_id = $from['id'];
            $user_id = $from['id'];

            $chat = $message['chat'];
            $chat_id = $chat['id'];

            // $text = $this->filterText($message['text']);
            $text = str_replace("@php_heroku_telegram_bot", "", $message['text']);

            switch($text)
            {
                case "/name":
                {
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $this->getName()
                    ]);
                    break;
                }
                case '/whoami':
                {
                    $_text = "Your ID: ".$user_id;

                    if ($this->isAdmin($from_id)){
                        $_text = "You are admin and your ID: ".$user_id;
                    }
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $_text
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
                    if ( $this->isAdmin($user_id) ) {
                        $this->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat_id
                        ]);
                    }
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
                    $this->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $text
                    ]);
                    break;
                }
            }
        }

    }
}
