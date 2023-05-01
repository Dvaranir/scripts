# Scripts that checks server usage and send alert to selected telegram topic if usage too high

## To use bash script with options, on server must be installed jq
    sudo apt-get install jq

## To get topic id, copy link of first message in topic "Copy message link":
    https://t.me/c/1920652164/18/19
## Where 18 is topic id<br><br>

# How to use:
01) Create bot with @botfather and save bot token to options.txt in root of project or inside "no-options" scripts
02) Invite bot to group with topics
03) Tweak scripts options and add them to cron or another schedule daemon on your server

