#!bin/bash

 sshpass -p "ubuntu" rsync -avz --exclude 'Version-Control/' root@192.168.1.12:/home/v2/490-Servers/ /home/v2/490-Servers

 sshpass -p "ubuntu" rsync -avz --exclude 'Version-Control/' root@192.168.1.13:/home/v2/490-Servers/ /home/v2/490-Servers



