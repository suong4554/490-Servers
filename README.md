# 490-Servers
Servers for 490 project

Quick Notes on Each Server:

Client Server:
Client Server is where the client connects and logs in. 
In order to test two users logging in at once and playing a scrabble game together, I installed Chrome on my ubuntu instance(Firefox runs from a single .exe file so multiple private windows will share cookies) and ran a chrome and firefox window side by side to simulate multiple connections.
For the scrabble game itself it saves gamestates to local files on the server and uses an SQL database that is on the server itself.
The reason for using a local SQL database was that the way I performed matchmaking was through putting a call to an SQL database to check for updates. I'm sure this isn't the most efficient way to do things but it's what I came up with for this project and it worked. The issue with using rabbitmq from my experience in this class so far is that when too many requests are put into the queue, the server hangs causing messages to not properly return or to properly send/get received. Thus I put the sql database on the local server itself ONLY FOR MATCHMAKING. If however, this means we get points off, I also coded it to work with RabbitMQ/AMQP calls but left this step commented out in the code as it did not work as well due to the issues mentioned before. You can see evidence that it worked together with the SQL/Broker server if you look into the MySQLRabbitServer.php file in the SQL/Broker Server and if you look at /html/scrabble/matchmaking/executeFunctionRabbit.php file.
The chat server is currently pending...


DMZ Server:
This server just takes in a request and checks it against the words API. You will need to install php-curl in order for it to properly work.
Each request to this server is an array of words, if a single one of the words is wrong it will return false signifying that the pieces the player put down are invalid.


SQL/Broker Server:
This server acts as both the SQL and Broker since we are currently one computer short due to a member being in the hospital.
This server will take in requests and forward it to the DMZ server or execute certain requests that call on SQL such as authentication/registration. 
It is also configured to work with matchmaking but that is currently not used in order to prevent the server from hanging. 



