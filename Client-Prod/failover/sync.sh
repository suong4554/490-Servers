#!bin/bash

echo "starting script" 
#creates sql dump in html folder
mysql -u sfu5 -pnjit -h localhost matches < /var/www/html/dump.sql

echo "sql dump created"

#syncs html folder to failsafe
sshpass -p "njit" ssh root@192.168.1.13 'rsync -avz root@192.168.1.12:/var/www/html /var/www'

echo "html folder synced"

#executes sql command to sync sql database on failsafe
sshpass -p "njit" ssh root@192.168.1.13 'mysqldump -u sfu5 -pnjit userAccounts > /var/www/html/dump.sql'

echo "sql dump transferred"

#rewrites permissions 
sshpass -p "njit" ssh root@192.168.1.13 'chown -R www-data:www-data /var/www/html'
sshpass -p "njit" ssh root@192.168.1.13 'chmod -R 755 /var/www/html'

echo "done"
