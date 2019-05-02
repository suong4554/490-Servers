This is the folder for failover system. 

Create a Broker-Prod Failsafe Virtual Machine by cloning the Broker-Prod VM.

Install rsync on both machines. 
> sudo apt-get install rysnc

Make sure you have root access to both machines!

Then run the watcher.txt file.
>sudo bash watcher.txt

This will start syncing the files from the main environment to the failsafe environment.
