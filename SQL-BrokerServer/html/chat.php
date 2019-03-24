<?php 

class chat 
{
	private $ChatId,$ChatUsername,$ChatText,$ChatGameId;
	
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
		return $this->ChatGameId;
	}
	public function setChatGameId($ChatGameId){
		return $this->ChatGameId=$ChatGameId;
	}	
	

	public function InsertChatMessage()

	{       
		include "connectToDB.php";
		$stmt = $db->prepare("INSERT INTO chats (ChatUsername,ChatGameId,ChatText) VALUES (?,?,?)");
		$stmt->bind_param("sis",$ChatUsername,$ChatGameId,$ChatText);
		$ChatUsername = $this->getChatUsername();
		$ChatGameId = $this->getChatGameId();
		$ChatText = $this->getChatText();
		$stmt->execute();
		$stmt->close();
		$db->close();
		
		
			
	
	}	

	public function DisplayMessage()
	{
		include "connectToDB.php";
		$ChatGameId = 0;
		$s = "select * from chats where ChatGameId = $ChatGameId ORDER BY ChatId DESC";
		$t = mysqli_query($db,$s) or die (mysqli_error($db));
		$numRows = mysqli_num_rows($t);
		if($numRows > 0)
		{
			while($rowChat = mysqli_fetch_assoc($t))
			{
				if($rowChat["ChatGameId"] == 0) 
				{?>
					<span class="UserNameS"><?php echo $rowChat['ChatUsername'];?></span> says:
					<span class="ChatMessageS"><?php echo $rowChat['ChatText'];?></span></br>
					<?php

				}
			}
		}
		if($numRows > 15)
		{
			$delete = "DELETE FROM chats ORDER BY ChatId LIMIT 1";
			$statement = mysqli_query($db,$delete) or die (mysqli_error($db));
		}
	}
}	
?>
