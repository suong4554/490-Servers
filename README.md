# 490-Servers
Servers for 490 project




Evidence of work can be seen through commits, Commits by Samuel Uong are normally the work of both Samuel Uong and Edwin Zhou working together. We have another repository where we tested the scrabble game itself before moving it to the 490 servers repository (https://github.com/suong4554/Scrabble-Service)


## **Deliverable Requirements** (*All deliverable Requirements were completed*)  
Start a game with a random person that is also logged in for scrabble  
Use API to validate all words to make sure it exists  
Keep track of history of who you played, wins, losses, scores, etc.   
Integrate a chat platform  
Resume Game State on disconnect  
*SQL and Broker are the same server since one of our members is currently in the hospital*  



## **Moving files over for testing**  
For the Client/Web Server move the ClientServer/html folder into /var/www/html and also replace your apache2.conf with the apache2.conf in the ClientServer folder (Main difference is it calls index.php instead of index.html). Also using clintSQL.sql dump implement tables on the web server (this is explained later on).    
For the DMZ Server simply move the folder over onto the DMZ server and install php-curl to have it work.  
For the SQL-BrokerServer move the folder onto the Broker/SQL Server  and install the sql dump (scheme.sql).
For all servers esnure that RabbitMQ and AMQP are installed along with PHP 7.
Create RabbitMQ queues according to the .ini files for each server (DMZ.ini for the DMZ server and MYSQLRabbitServer.ini for the Broker/SQL server)  
Also note that the ip addresses have to match which are denoted in the .ini files (192.168.10 for Broker and 192.168.7 for DMZ)
 





## Quick Notes on Each Server:

##### **Client Server:**  
Client Server is where the client connects and logs in.   
From the login or registration page the user is sent to home.php which has the options to *Play Scrabble*, *View Match History*, and to *Logout*  


##### Play Scrabble (Starting a game with a random person, using API to validate words, integrating a chat platform, Resuming game State on disconnect)   
If you select *Play Scrabble* you will be redirected to a finding match page which will have an option to *Cancel* the search at any time.  
Once someone else logs in and starts looking for a match too you will automatically be redirected to the Scrabble Game itself and your match will start (It is important to note that only one player is allowed to play at once, so you will either be redirected to a scrabble board with some stats at the bottom or to another waiting page).
There will be a chat that keeps the 15 most recent messages in the waiting and scrabble pages (The chat is unique per each game).  
If you select the logout/quitButton you will be logged out and the game **will delete itself**, meaning no reconnecting and no decision.  
If you decide to end the game/Declare the winner, the game will calculate the winner but will still **delete the current session**.  
*These were choices made in order to emphasize not quitting in the middle of the game as the other player will be left waiting*  
If your browser closes out mid game (**This will be important to test *resuming a game state on disconnect* **) when you log back in and click on *Play Scrabble* you will be sent back to the Scrabble Game you were currently in.  
  
##### View Match History  
This will simply view match history in the format of a table.  



##### *Testing the Scrabble Game*
In order to test two users logging in at once and playing a scrabble game together, I installed Chrome on my ubuntu instance(Firefox runs from a single .exe file so multiple private windows will share cookies) and ran a chrome and firefox window side by side to simulate multiple connections.
For the scrabble game itself it saves gamestates to local files on the server and uses an SQL database that is on the server itself.
The reason for using a local SQL database was that the way I performed matchmaking was through putting a call to an SQL database to check for updates. I'm sure this isn't the most efficient way to do things but it's what I came up with for this project and it worked.  
The issue with using rabbitmq from my experience in this class so far is sending mutliple requests on the loading of a php file causes errors, the server also hangs sometimes due to too many messages being sent at once (At least for our instances) so we decided that data that is basically only stored as a temporary cache such as matchmaking or chats should be left on the client server while data that should be stored for long term storage will be sent to the Broker/dedicated SQL server.
Thus I put the SQL database on the local server itself for matchmaking and the chat service since this clasifies as "temporary" data for us.
If however, this means we get points off, I also coded it to work with RabbitMQ/AMQP calls but left this step in the "ClientServer/html-attempt" (We also edited a lot of code on the SQL/Broker Server but lost the progress when we reverted back to using SQL) folder as it did not work as well due to an error we could not figure out (error.png attached). 
You can see evidence that we attempted to have it work together with the SQL/Broker server if you look into the MySQLRabbitServer.php file in the SQL/Broker Server along with the ClientServer/html-attempt.
View Match History works with RabbitMQ and connects to the Broker/SQL server to fetch from an SQL table  
Checking the words API works with RabbitMQ and calls the Broker which then calls the DMZ  
  
    
	 


##### **DMZ Server:**  
This server just takes in a request and checks it against the words API. 
*You will need to install php-curl in order for it to properly work.*
Each request to this server is an array of words, if a single one of the words is wrong it will return false signifying that the pieces the player put down are invalid.
  
    
	 


##### **SQL/Broker Server:**  
This server acts as both the SQL and Broker since we are currently one computer short due to a member being in the hospital.
This server will take in requests and forward it to the DMZ server or execute certain requests that call on SQL such as authentication/registration. 
It is also configured to work with matchmaking but that is currently not used in order to prevent the server from hanging along with the error we posted in error.png. 



