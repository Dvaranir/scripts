<?php
require('./CheckStatus.php');

$TOKEN = '';
$CHAT_ID = '';
$TOPIC_CHAT_ID = '';

$CPU_ALERT = 80;
$RAM_ALERT = 80;
$DISK_ALERT = 90;

check_status($TOKEN, $CHAT_ID, $TOPIC_CHAT_ID, $CPU_ALERT, $RAM_ALERT, $DISK_ALERT);


