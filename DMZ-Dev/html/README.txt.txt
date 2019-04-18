install php-curl in order to run WordCheck:

sudo apt install php-curl



Purpose:
This server is the DMZ so it will call the API and return true or false depending on if a word in an array exists in the wordsAPI.
If any element in the array returns false, it will return false as that means that the move for that turn was invalid.

i.e.
["what", "okay", "xxxxNotWordxxx"]

will return False