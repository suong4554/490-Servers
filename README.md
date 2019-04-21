# 490-Servers
Servers for 490 project




Evidence of work can be seen through commits, Commits by Samuel Uong are normally the work of both Samuel Uong and Edwin Zhou working together. We have another repository where we tested the scrabble game itself before moving it to the 490 servers repository (https://github.com/suong4554/Scrabble-Service)


## **Server IP Configuration**  
##### **Dev Instances:**    
192.168.1.10 - Dev Broker  
192.168.1.7 - Dev DMZ   
192.168.1.2 - Dev Client  
  
##### **QA Instances:**  
192.168.1.30 - QA Broker
192.168.1.27 - QA DMZ
192.168.1.22 - QA Client
  
##### **Prod Instances:**  
192.168.1.20 - Prod Broker
192.168.1.19 - Prod Broker-FailsafeSQL
192.168.1.17 - Prod DMZ
192.168.1.12 - Prod Client
192.168.1.13 - Prod Client-FailsafeSQL

##### **Version Control:**
192.168.1.50 - Version Control  
  
  

## **Packages to Install (Commands are given)**  
##### **Upgrade**    
sudo apt-get update  
sudo apt-get upgrade   
   
##### **Essentials**    
sudo apt-get install php  
sudo apt-get install php-curl  
sudo apt-get install mysql-server  
sudo apt-get install rabbitmq-server  

##### **System Service**    
sudo apt-get install memcached  

##### **System Version Control**    
sudo apt-get install oppenssh-server openssh-client 
sudo apt-get install sshpass  

  
  
  
## **Setting up servers**  
For all servers the "*transfer*" directory will be moved to "*home/transfer*" and the "*html*" directory will be moved to "*/var/www/html*"  
This can be done manually or you can execute the "*move.txt*" file by using the command "*sudo bash move.txt*" (this executes the move.txt file) once each directory is downloaded into their respective machines.
It is assumed that dependencies are already installed and that each server has their respective IP addresses installed.  
Both the Broker and Client server use MYSQL. It is important to create a table called "matches" in the Client server and "userAccounts" in the Broker server with the user "sfu5@localhost" with the password "njit"
  
  
## **Setting up version control**  
In order for version control to work ssh must be allowed to run to the IP addresses that each server needs to connect to.  
The command to do this is to run "*ssh root@InsertIPaddressOfHost*" and enter "*yes*" for the prompt that pops up then disconnect from the host by using "*ctrl d*".
So the Broker of each environment needs to be able to access the Client, Version Control, and DMZ servers.
The Client needs to access the Broker.
The DMZ needs to access the Broker.
The Version Control server needs to have access to the three Brokers for each environment (Broker-Dev, Broker-QA, Broker-Prod)  
 
## **Setting up RabbitMQ as a System Service**  
For all servers the *system* directory will be have a file called *"localRabbit.service"*. This file will go into "*/etc/systemd/system*".
After moving the file, run "*sudo systemctl daemon-reload*" then, "*sudo systemctl enable localRabbit*", this will start the system service on server start




## Quick Notes on Each Server:

##### **Client Server:**  
Client Server is where the client connects and logs in.   
From the login or registration page the user is sent to home.php which has the options to *Play Scrabble*, *View Match History*, and to *Logout*  


##### Play Scrabble (Starting a game with a random person, using API to validate words, integrating a chat platform, resuming game state on disconnect)   
If you select *Play Scrabble* you will be redirected to a finding match page which will have an option to *Cancel* the search at any time.  
Once someone else logs in and starts looking for a match too, you will automatically be redirected to the Scrabble Game itself and your match will start (It is important to note that only one player is allowed to play at once, so you will either be redirected to a scrabble board with some stats at the bottom or to another waiting page).
There will be a chat that keeps the 15 most recent messages in the waiting and scrabble pages (The chat is unique per each game, similar to a private chat).  
If you select the logout/quitButton you will be logged out and the game **will delete itself**, meaning no reconnecting and no ending result.  
If you decide to end the game/Declare the winner, the game will calculate the winner but will still **delete the current session**.  
*These were choices made in order to emphasize not quitting in the middle of the game as the other player will be left waiting*  
If your browser closes out mid game (**This will be important to test *resuming a game state on disconnect* **) when you log back in and click on *Play Scrabble* you will be sent back to the Scrabble Game you were currently in.  
  
##### View Match History  
This will simply view match history in the format of a table from data stored in MySQL. 



##### *Testing the Scrabble Game*
In order to test two users logging in at once and playing a scrabble game together, Chrome was installed and ran along side a Firefox (Firefox runs from a single .exe file so multiple private windows will share cookies) window to simulate multiple connections.
For the scrabble game itself it saves gamestates to local files on the server and uses an SQL database that is on the server itself.
The reason for using a local SQL database was that the way I performed matchmaking was through putting a call to an SQL database to check for updates. I'm sure this isn't the most efficient way to do things but it's what I came up with for this project and it worked.  
The issue with using rabbitmq from my experience in this class so far is sending mutliple requests on the loading of a php file causes errors, the server also hangs sometimes due to too many messages being sent at once (At least for our instances) so we decided that data that is basically only stored as a temporary cache such as matchmaking or chats should be left on the client server while data that should be stored for long term storage will be sent to the Broker/dedicated SQL server.
Thus I put the SQL database on the local server itself for matchmaking and the chat service since this classifies as "temporary" data for us. Chat logs and matches will be deleted from the tables once the game has been terminated/winner declared.
If however, this means we get points off, I also coded it to work with RabbitMQ/AMQP calls but left this step in the "ClientServer/html-attempt" (We also edited a lot of code on the SQL/Broker Server but lost the progress when we reverted back to using SQL folder as it did not work as well due to an error we could not figure out (error.png attached). 
You can see evidence that we attempted to have it work together with the SQL/Broker server if you look into the MySQLRabbitServer.php file in the SQL/Broker Server along with the ClientServer/html-attempt.
View Match History works with RabbitMQ and connects to the Broker/SQL server to fetch from an SQL table  
Checking the words API works with RabbitMQ and calls the Broker which then calls the DMZ  
  
    
	 


##### **DMZ Server:**  
This server just takes in a request, calls the Words API, and captures the response. 
*You will need to install php-curl in order for it to properly work.*
Each request to this server is an array of words, if a single one of the words is wrong it will return false signifying that the pieces the player put down are invalid.
  
    
	 


##### **SQL/Broker Server:**  
This server acts as both the SQL and Broker.  
This server will take in requests and forward it to the DMZ server or execute certain requests that call on SQL such as authentication/registration. 
It is also configured to work with matchmaking but that is currently not used in order to prevent the server from hanging along with the error we posted in error.png. 



