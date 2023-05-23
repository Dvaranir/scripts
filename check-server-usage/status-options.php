<?php
require('CheckStatus.php');

$json_string = file_get_contents('./options.txt');
$options_array = json_decode($json_string, true);
$options = $options_array["options"];

$TOKEN = trim($options['token']);
$CHAT_ID = trim($options['chat_id']);
$TOPIC_CHAT_ID = trim($options['topic_chat_id']);

$CPU_ALERT = intval(trim($options['cpu_alert']));
$RAM_ALERT = intval(trim($options['ram_alert']));
$DISK_ALERT = intval(trim($options['disk_alert']));

check_status($TOKEN, $CHAT_ID, $TOPIC_CHAT_ID, $CPU_ALERT, $RAM_ALERT, $DISK_ALERT);