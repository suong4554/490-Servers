## **Purpose of the DMZ Server**  
This server contains the API calls for our project. In our case we communicate with a words API that we use to check whether a word exists.
To do this we send a word to the API and if it returns an object the word exists, if not, then the word does not exist.
This server communicates with the broker in order to receive messages from the Client server.
The Client sends messages to the Broker which then sends a message to the DMZ containing an array of words to check. The DMZ then returns an answer
which is then returned by the Broker to the Client.
In order to get the API call to work, internet access is required and the DMZServer.php needs to be run (php DMZServer.php)
php-curl also needs to be installed in order for our php function to call the API.
