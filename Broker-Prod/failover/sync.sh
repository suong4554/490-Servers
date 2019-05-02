#!bin/bash

echo "starting script" 
#creates sql dump in html folder
mysql -u sfu5 -pnjit -h localhost userAccounts < /var/www/html/dump.sql

echo "sql dump created"

#syncs html folder to failsafe
sshpass -p "njit" ssh root@192.168.1.19 'rsync -avz root@192.168.1.20:/var/www/html /var/www'

echo "html folder synced"

#executes sql command to sync sql database on failsafe
sshpass -p "njit" ssh root@192.168.1.19 'mysqldump -u sfu5 -pnjit userAccounts > /var/www/html/dump.sql'

echo "sql dump transferred"

#rewrites permissions 
sshpass -p "njit" ssh root@192.168.1.19 'chown -R www-data:www-data /var/www/html'
sshpass -p "njit" ssh root@192.168.1.19 'chmod -R 755 /var/www/html'

echo "permissions rewritten"

#restarts rabbit service on failsafe
sshpass -p "njit" ssh root@192.168.1.19 'sudo service localRabbit stop'
echo "stopped localRabbit"
sshpass -p "njit" ssh root@192.168.1.19 'sudo service localRabbit start'
echo "started localRabbit"
