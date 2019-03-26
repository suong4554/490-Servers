$(document).ready(function() {
	$("#ChatText").keyup(function(e){
			if(e.keyCode == 13) {
					
				var ChatText = $("#ChatText").val();
				$.ajax({
					type:'POST',
					url:'insertMessage.php',
					data:{ChatText:ChatText},
					success:function()
					{
						$("#ChatText").val("");
					
					},
					fail:function()
					{
					alert('request failed');
					}

				})
			}
	})
	
	setInterval(function(){
			$("#ChatMessages").load("DisplayMessages.php");
	},1500)
	
	$("#ChatMessages").load("DisplayMessages.php");
	
});