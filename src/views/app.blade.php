<!DOCTYPE html>
<html>
<head>
    <title>Console</title>
    <link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>
<body>
    <div class="fluid-container" style="width:98%;margin:auto 1%;">
    	<div class="row">
    	<div class="col-md-12">
    		<br>
    	</div>
    	</div>
    	<div class="row">
	        <div class="col-md-5">
	           <header><b>#Console</b></header>
	        	<form mehod="post">
<textarea cols="66" rows="15" placeholder="commands.." id="commands">
SET empdata 1 ‘Jack Petter’ ‘192.168.10.10’
GET empdata ‘192.168.10.10’
#UNSET empdata ‘192.168.10.10’
GET empdata ‘192.168.10.10’
SET empwebhistory 192.168.10.10 ‘http://google.com’
#GET empwebhistory  192.168.10.10
UNSET empwebhistory  192.168.10.10
GET empwebhistory 192.168.10.10
END
</textarea><br>
	        		<button type="button" id="input">GO</button>
	        	</form>
	        </div>
	        <div class="col-md-7">
	        <header><b>#Output</b></header>
	        <pre style="height:500px; overflow: scroll;">
	        	<code>
	        		<div id="output"></div>
	        	</code>
	        </pre>
	        </div>
        </div>
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
    	$("#input").click(function() {
    		$.ajax({
			   url: "{{ url('output/console') }}",
			   type: "POST",
			   data: $("#commands").val(),
			   contentType: "text/plain",
			   success: function(output){
			   	 //$("#output").html(output);
			   	 document.querySelector('#output').innerHTML = JSON.stringify(output, null, 6).replace(/\n( *)/g, function (match, p1) {
			         return '<br>' + '&nbsp;'.repeat(p1.length);
			     });
			   },
			   error: function() {
			   	$("#output").html("Error");
			   }
			});
    	});
    </script>
</body>
</html>