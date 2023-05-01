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
}