<?php 

class chat {
	private $ChatId,$ChatUsername,$ChatText,$ChatTextGameId;
	
	public function getChatId(){
		return $this->ChatId;
	}
	public function setChatId($ChatId){
		return $this->ChatId=$ChatId;
	}	
	
	public function getChatUsername(){
		return $this->ChatUsername;
	}
	public function setChatUsername($ChatUsername){
		return $this->ChatUsername=$ChatUsername;
	}	
	
	public function getChatText(){
		return $this->ChatText;
	}
	public function setChatText($ChatText){
		return $this->ChatText=$ChatText;
	}	
	public function getChatGameId(){
		return $this->ChatTextGameId;
	}
	public function setChatGameId($ChatTextId){
		return $this->ChatTextGameId=$ChatTextId;
	}	
	
	public function InsertChatMessage(){
//		include "../../connectToDB.php";
		$chatInsert=$_db->prepare("INSERT INTO chats (ChatUsername,ChatGameId,ChatText)
		VALUES(:ChatUsername,:ChatGameId,:ChatText)");
		
		$chatInsert->execute(array(
		'ChatUsername'=>$this->getChatUsername(), 
		'chatGameId'=>$this->getChatGameId(), 
		'ChatText'=>$this->getChatText() 
		));	
	}
	
	public function DisplayMessage(){
//		include "../../connectToDB.php";
		$ChatReq=$_db->prepare("SELECT * FROM chats WHERE chatGameId=:chatGameId ORDER BY ChatId DESC");
		$ChatReq->execute(array(
		'chatGameId'=>"0"  
		));	
		$chatIdForGame=$this->getChatGameId();
		$existCount = $ChatReq->rowCount();
		if ($existCount == 0) { // evaluate the count
			return "Tom";
		}
		if ($existCount > 0) {
			while($rowChat=$ChatReq->fetch()){
				$UserReq=$_db->prepare("SELECT * FROM userTable WHERE Username=:Username");
				$UserReq->execute(array(
				'Username'=>$rowChat['ChatUsername']
				));	
				
				$rowUser = $UserReq->fetch();
				if ($rowChat["chatGameId"] == 0) {
					?>
					<span class="UserNameS"><?php echo $rowUser['Username'];?></span> says: 
					<span class="ChatMessageS"><?php echo $rowChat['ChatText'];?></span></br>
					<?php
				}
			}
		}
		//public function Delete15Chats(){
		if ($existCount > 15) {
			$_db->exec("DELETE FROM chats ORDER BY ChatId LIMIT 1");
		}
		$GameOnReq=$_db->prepare("SELECT * FROM userTable WHERE Username=:Username LIMIT 1");
		$GameOnReq->execute(array(
		'Username'=>$this->getChatUsername()  
		));	
		$existCount = $GameOnReq->rowCount();
		if ($existCount > 0) {
			while($rowGameOn=$GameOnReq->fetch()){
				if ($rowGameOn["GameId"] != 0) {
					//session_destroy();
					$token = $rowGameOn["GameId"];
					$opponent = $rowGameOn["GameOpponent"];
					$string="?id=" . $token . "&name=" .$opponent;
					$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/redirect.php' . $string;
					print "<script>document.location.href='$home_url' ;</script>";
				}
				//return $rowGameOn["GameId"];
			}
		} else {
			return "";
		}
	}
	public function DisplayMessagesInGame(){
//	include "../../connectToDB.php";
		$ChatReq=$_db->prepare("SELECT * FROM chats WHERE chatGameId=:chatGameId ORDER BY ChatId DESC");
		$ChatReq->execute(array(
		'chatGameId'=>$this->getChatGameId()  
		));	
		$existCount = $ChatReq->rowCount();
		if ($existCount == 0) { // evaluate the count
			//return "";
		}
		if ($existCount > 0) {
			while($rowChat=$ChatReq->fetch()){	
				$UserReq=$_db->prepare("SELECT * FROM userTable WHERE Username=:Username");
				$UserReq->execute(array(
				'Username'=>$rowChat['ChatUsername']
				));		
				$rowUser = $UserReq->fetch();
				?>
				<span class="UserNameS"><?php echo $rowUser['UserName'];?></span> says: 
				<span class="ChatMessageS"><?php echo $rowChat['ChatText'];?></span></br>
				<?php
			
			}
		}
		$GameOnReq=$_db->prepare("SELECT * FROM userTable WHERE Username=:Username LIMIT 1");
		$GameOnReq->execute(array(
		'Username'=>$this->getChatUsername()  
		));	
		$existCount = $GameOnReq->rowCount();
		
		if ($existCount > 0) {
			while($rowGameOn=$GameOnReq->fetch()){
				if ($rowGameOn["GameId"] == 0) {
					//session_destroy();
					$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/indexMult.php';
					print "<script>document.location.href='$home_url' ;</script>";
					exit;
				}
				//return $rowGameOn["GameId"];
			}
		} else {
			return "";
		}		
	}
}
?>
