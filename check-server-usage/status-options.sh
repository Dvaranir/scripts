#!/bin/bash

options_content=$(cat options.txt)

TOKEN=$(echo $options_content | jq -r '.options.token')
CHAT_ID=$(echo $options_content | jq -r '.options.chat_id')
TOPIC_CHAT_ID=$(echo $options_content | jq -r '.options.topic_chat_id')
CPU_ALERT=($(echo $options_content | jq -r '.options.cpu_alert') + 0)
RAM_ALERT=($(echo $options_content | jq -r '.options.ram_alert') + 0)
DISK_ALERT=($(echo $options_content | jq -r '.options.disk_alert') + 0)

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

