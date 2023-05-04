<?php

function check_status($TOKEN, $CHAT_ID, $TOPIC_CHAT_ID, $CPU_ALERT, $RAM_ALERT, $DISK_ALERT){
    $cpu = intval(shell_exec("top -b -n1 | grep 'Cpu(s)' | awk '{print $2}'"));
    $ram = intval(shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'"));
    $disk = shell_exec("df -h / | awk '{print $5}' | sed 's/%//;s/\\n/ /'");
    $disk = intval(str_replace(array("\r", "\n", "Use"), '', $disk));

    if ($cpu > $CPU_ALERT || $ram > $RAM_ALERT || $disk > $DISK_ALERT) {
        $message = "ALERT: Resource usage is high!\n"
                . "CPU: $cpu%,\n"
                . "RAM: $ram%,\n"
                . "Disk: $disk%";
        if(!sendHtopAlert($TOKEN, $CHAT_ID, $TOPIC_CHAT_ID, $message)){
            sendMessage($TOKEN, $CHAT_ID, $TOPIC_CHAT_ID, $message);
        }
    }
}

function sendMessage($TOKEN, $CHAT_ID, $TOPIC_CHAT_ID, $message){
    $data = array(
        'chat_id' => $CHAT_ID,
        'text' => $message,
        'disable_notification' => true,
        'reply_to_message_id' => $TOPIC_CHAT_ID
    );

    $url = "https://api.telegram.org/bot$TOKEN/sendMessage";
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
}

function sendHtopAlert($TOKEN, $CHAT_ID, $TOPIC_CHAT_ID, $message) {
    $wkhtmltoimage = shell_exec('which wkhtmltoimage');
    if (strpos($wkhtmltoimage, 'wkhtmltoimage') !== false) {
        $htop_output = shell_exec("export TERM=xterm-color;export COLUMNS=120 LINES=14;echo q | htop | aha --black --line-fix > htop.html");
        $htop_output = file_get_contents('./htop.html');
        exec("wkhtmltoimage ./htop.html ./htop.png");

        $URL = "https://api.telegram.org/bot$TOKEN/sendPhoto";
        $POST = array(
            'chat_id' => $CHAT_ID,
            'photo' => new CURLFile(realpath("./htop.png")),
            'caption' => $message,
            'disable_notification' => true,
            'reply_to_message_id' => $TOPIC_CHAT_ID
        );
        $CH = curl_init();
        curl_setopt($CH, CURLOPT_URL, $URL);
        curl_setopt($CH, CURLOPT_POST, 1);
        curl_setopt($CH, CURLOPT_POSTFIELDS, $POST);
        curl_setopt($CH, CURLOPT_RETURNTRANSFER, 1);
        $RESULT = curl_exec($CH);
        curl_close($CH);
        return true;
    } else {
        echo "wkhtmltoimage is not installed";
        return false;
    }
    return false;
}
