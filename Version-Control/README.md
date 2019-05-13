## **Purpose of the version control server**  
This server makes packaging environments from the QA, Prod, and Dev servers easy  by allowing users to interact with a single php script which will
automatically package code from each environment. It also allows for easier rolling out of new code as it allows for users to
interact with the same php script to push specified packages to the specified environments

## **Setting up version control**  
In order for version control to work ssh must be allowed to run to the IP addresses that each server needs to connect to.  
The command to do this is to run "*ssh root@InsertIPaddressOfHost*" and enter "*yes*" for the prompt that pops up then disconnect from the host by using "*ctrl d*".
So the Broker of each environment needs to be able to access the Client, Version Control, and DMZ servers.
The Client needs to access the Broker.
The DMZ needs to access the Broker.
The Version Control server needs to have access to the three Brokers for each environment (Broker-Dev, Broker-QA, Broker-Prod)  


## **Using Version Control**
In order to test version control, log in as sudo into the version control server (192.168.1.50). Once logged in, change directories to the "*/var/www/html/*" folder and type in "*php request.php*". This will display an output of all possible commands which are self explanatory. The commands are:  
######COMMANDS#########  
#getDev (Packages Dev environment into the version control (called by "*php request.php getDev*"))    
#toDev  (Pushes Version control specified version to Dev (called by "*php request.php toDev <version here>*", if no version is specified, defaults to most recently uploaded version))   
#getQA (Packages QA environment into the version control (called by "*php request.php getQA*"))    
#toQA (Pushes Version control specified version to QA (called by "*php request.php toQA <version here>*", if no version is specified, defaults to most recently uploaded version))   
#getProd (Packages Prod environment into the version control (called by "*php request.php getProd*"))     
#toProd (Pushes Version control specified version to Prod (called by "*php request.php toProd <version here>*", if no version is specified, defaults to most recently uploaded version))   
#showVersion (Shows all versions in stored in the version control system and their details such as file location and date created,  "*php request.php showVersion <version here>*")
#deleteVersion (Deletes a version specified, called by "*php request.php deleteVersion <version here>*")  
