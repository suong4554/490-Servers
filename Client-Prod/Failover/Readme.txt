This is the folder for failover system for the Client.

Create a Client-Prod Failsafe Virtual Machine by cloning the Client-Prod VM.

Install rsync on both machines. 
> sudo apt-get install rysnc

Make sure you have root access to both machines!

Then run the watcher.txt file.
>sudo bash watcher.txt

**Note**
When running the watcher.txt file for the first time, there will be a "Host not resolved" error, to fix this simply remove ssh -p "ubuntu" from sync.sh file and then run sudo bash watcher.txt file. This will prompt to establish a ssh key, enter yes and then input the password for the root of the machine you are connecting to. In my VM's case, all of my root passwords are "ubuntu". If you want to change to your own password, edit ssh -p "ENTERPASSWORDHERE" inside sync.sh file. 
This will start syncing the files from the main environment to the failsafe environment.
