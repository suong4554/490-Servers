This server is the Client server where users can log in to play scrabble with each other. Currently it only supports 1 v 1 matches.
Majority of the scrabble game back-work was written by Samuel Uong and Edwin Zhou with help from Jaydev Patel.
Currently this server has its own SQL instance for matchmaking (This is more of a client side process than authentication as it does not store any sensitive information or information that is kept long term since it just acts as a cache. I technically could have written it to files on the server but that would be bad practice and inefficient).
In order to implement rabbitmq into matchmaking, you would just need to replace all calls to the file executeFunction.php in javascript with executeFunctionRabbit.php along with changing some simple php snippets on scrabbleGame.php, findMatch.php, and waitingForTurn.php.
The changes would include removing the connecting to the local SQL database and changing local calls to the SQL Functions to calling the rabbitmq calls inside executeFunctionRabbit.php's if statement (This is doable, but not as efficient as keeping local temporary things such as match making local, while keeping user credentials and match history in the "cloud" of a specified sql server).
I included our attempt to move it to rabbitMQ but we encountered an error in the rabbitMQ code due to rabbitmq being unable to handle multiple simultaneous requests, online people said this was most likely a bug:
Here is the link that was posted related to this issue:
https://github.com/squaremo/amqp.node/issues/175 

I have also included the error as a png called "error.png" 
