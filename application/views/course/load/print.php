<html lang="en">
<!--<![endif]-->
<!-- Mirrored from seantheme.com/color-admin-v1.9/admin/html/page_blank.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 15 Apr 2016 04:05:06 GMT -->
	<head>
		<script src="<?php echo base_url('assets/plugins/jquery/jquery-1.9.1.min.js') ?>"></script>
		<link href="<?php echo base_url('assets/plugins/fullcalendar2/fullcalendar.css') ?>" rel="stylesheet" />
		<!-- <link href="<?php echo base_url('assets/plugins/fullcalendar2/fullcalendar.print.css') ?>" rel="stylesheet" /> -->
	</head>
	<body>
		<div id="calendar"></div>
	</body>
	<script src="<?php echo base_url('assets/plugins/moment/moment.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/plugins/fullcalendar2/fullcalendar.js') ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#calendar").fullCalendar({
				// defaultDate: moment(),
				defaultView: 'agendaWeek',
	            header: {
	                left: '',
	                right: ''
	            },
	            minTime: "07:00:00",
	  			maxTime: "22:00:00",		
	  			columnFormat: {
		            week: 'ddd'
		        },
		        height:765,
		        slotMinutes: 30,
		        allDaySlot: false,
		        editable: false,
	    		droppable: false,
	    		firstDay: 1,
			    events: <?php echo json_encode($sched); ?>
	        });
		})
	</script>
	<script type="text/javascript">
		PrintWindow();
		function PrintWindow() {                   
			window.print();           
			CheckWindowState();
		}
		function CheckWindowState()    {          
			if(document.readyState=="complete") {
			window.close();
			} else {          
			setTimeout("CheckWindowState()", 500);
			}
		}
	</script>
</html>