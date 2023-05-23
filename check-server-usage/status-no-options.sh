#!/bin/bash

TOKEN=""
CHAT_ID=""
TOPIC_CHAT_ID=""

CPU_ALERT=80
RAM_ALERT=80
DISK_ALERT=90

CPU=($(top -b -n1 | grep "Cpu(s)" | awk '{print $2}') + 0)
RAM=($(free | grep Mem | awk '{print $3/$2 * 100.0}') + 0)
DISK=($(df -h / | awk 'NR==2{print $5}' | sed 's/%//') + 0)

if (( $(echo "$CPU > CPU_ALERT" | bc -l) )) || (( $(echo "$RAM > RAM_ALERT" | bc -l) )) || (( $(echo "$DISK > DISK_ALERT" | bc -l) )); then
MESSAGE="ALERT: Resource usage is high!
CPU: $CPU%,
RAM: $RAM%,
Disk: $(echo -n $DISK)%"
    
    curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" \
        -d chat_id="$CHAT_ID" \
        -d text="$MESSAGE" \
        -d disable_notification="true" \
        -d reply_to_message_id="$TOPIC_CHAT_ID"
fi

