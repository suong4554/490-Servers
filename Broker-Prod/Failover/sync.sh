#!bin/bash

 sshpass -p "ubuntu" rsync -avz --exclude 'Version-Control/' root@192.168.1.20:/home/v2/490-Servers/ /home/v2/490-Servers

 sshpass -p "ubuntu" rsync -avz --exclude 'Version-Control/' root@192.168.1.19:/home/v2/490-Servers/ /home/v2/490-Servers

