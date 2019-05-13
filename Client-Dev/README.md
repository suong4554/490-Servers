## **Purpose of the Client**  
The client server is the front facing server that allows the whole system to be shown to users. It contains the code for the webpages and the scrabble game itself.
The client also stores some data into local SQL tables such as a timer, and a local matchmaking system. 
A lof of the scrabble game is also stored locally into files which are called actively by the scrabble game throughout a match. Utilizing
python to parse through words recursively in order to find all new words per turn (letters that touch new letters are considered new words).
The client then sends this data to the broker which then sends the data to the DMZ to validate whether a word is true or false. If it's true, the user turn will progress, if false, it will block the turn from progressing.
