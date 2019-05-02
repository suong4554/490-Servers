This is the folder for failover system. 

Install rsync on both machines. 
> sudo apt-get install rysnc

*Make sure you have root access to both machines!*

On Client Production run 
>ssh root@192.168.1.13
It will prompt- say yes, and enter njit as password.

On Client Production Failsafe run
>ssh root@192.168.1.12
It will prompt- say yes, and enter njit as password.

--Note-- From here work only in the Client Production VM

Copy home/sfu5/490-Servers/Client-Prod/failover to /home/ directory

Then navigate to this folder and run watcher.txt file.
>sudo bash watcher.txt


***Troubleshooting***

1. When initially running the watcher.txt file, if you get
an error saying Permission Denied and  "Rsync error unexplained error(code 255) at rsync.c(644)"
edit sync.sh completely remove the rsync line and copy and paste the following.

sshpass -p "njit" ssh root@192.168.1.13 'sshpass -p "njit" rsync -avz root@192.168.1.12:/var/www/html /var/www'

Then just run watcher.txt again.

Note- The reason why you need to replace the rsync line is initially it doesnt grant access to the client machine, therefore you have to manually replace the rysnc line I provided after you initially run watcher.txt

2. If you get the error ssh exchange connection reset by peer, then simply restart your virtual machines.

