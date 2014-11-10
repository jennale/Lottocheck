<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Jenna Le | Lottery Number Checker</title>
    <link rel="stylesheet" href="./css/hw2.css" type="text/css" media="screen"/>
	<script type="text/JavaScript" src="https://code.jquery.com/jquery-1.11.1.min.js" ></script>
    <script type="text/JavaScript" src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
    <script type="text/JavaScript">

    // Your JS/jQuery/jQueryUI code here
    $(document).disableSelection().ready(function() {
    	sort = true;
		$request = null;


		$('#numcontainer').sortable({
            connectWith:"#dropzone" //nums from container can be put into the dropzone
        });

		$('#dropzone').sortable({
            connectWith:"#numcontainer", //nums from dropzone can be returned to container
            receive : function(event,ui){
	        	var $list = $(this);
				$('#loader').show();
				if ($list.children().length > 6){
		        	$(ui.sender).sortable('cancel'); //Cancel selection of more than 6 numbers
					$('#loader').hide();
		        	request.abort();
		        	}
		        },
            remove : function(event,ui) {
				$('#loader').show();
			},
		}).on({
			sortupdate : function(event,ui) {
				//Creates array of selected numbers
				var numberArray = $("#dropzone").sortable("toArray",{attribute:'data-lotto'});
				//Array into string format: numbers='#,#,#'
				var string = "numbers='" + numberArray.join("-") + "\'"; 
				//If a request is already in process, cancel it and send a new one 
				if ($request != null)
					$request.abort();
					$request = $.get("process.php",string,function(data){
						$('#results').html(data);
					})
					.done(function(data){
						$('#loader').hide(); //Reveal results
						});
					}
			});
			
	    $('#dropzone,#numcontainer').sortable({
	    	update: function (event,ui){
 				//For every update, make sure the numbers are in increasing order in both areas
 				var $list = $('#dropzone');
    			var $list2 = $('#numcontainer');
				//Sorting function
				function sorter(a,b){
	    		 	return $(a).text()-$(b).text();
	    		 }    			
	    		 //Sort lists on update
				$list.children().sort(sorter).appendTo($list);
				$list2.children().sort(sorter).appendTo($list2);
			},
			opacity:0.5 //Opacity when dragging to sort
		});			
  });
    </script>
</head>

<body>
    <div id="mainbody">
        <h3>Lotto 6/49 Combinations Finder</h3>

        <div id="numcontainer">
            <?php
				for ($i=1; $i<=49; $i++) {
					echo "<div data-lotto=\"".$i."\" class=\"lottonums\">".$i."</div>";
					};
				?>
        </div><!-- end of numcontainer -->

        <div id="sidebar">
            <div id="pickarea">
                <p id="picktitle">Pick Your Numbers:</p>
                <div id="dropzone"></div>
            </div><!-- end of pickarea -->
            <img id="loader" alt ="Loading, please wait." src="images/loading.gif" />
            <div id="results"></div>
        </div><!-- end of sidebar -->
    </div><!-- end of mainbody -->
</body><!-- end of body -->
</html>
