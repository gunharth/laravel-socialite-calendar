@extends ('master')

@section('styles')
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">

    <link href="css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="css/plugins/fullcalendar/fullcalendar.print.css" rel='stylesheet' media='print'>

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
@endsection

@section('content')
<div class="row animated fadeInDown">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div id="calendar"></div>

                    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                
                                            <h4 class="modal-title">Create a new event</h4>
                                            
                                        </div>
                                        <div class="modal-body">
                                            
                                    {!! Form::open(['data-remote', 'method' => 'POST', 'url' => 'events', 'class' => 'form-inline']) !!}
                
                                      <div class="form-group">
                                        <label for="start-date">from </label>
                                        <input type="text" class="form-control" name="start-date" value="{{ date('d.m.Y') }}">
                                        <input type="text" class="form-control" name="start-time" value="{{ date('H') }}:00">

                                      </div>
                                      <div class="form-group">
                                        <label>to </label>
                                        <input type="text" class="form-control" name="end-date" value="{{ date('d.m.Y') }}">
                                        <input type="text" class="form-control" name="end-time" value="{{ date('H') }}:00">

                                      </div>

                                      <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            {!! Form::submit('Create Event', ['class' => 'btn btn-primary']) !!}
                                        </div>
                                      </form>

                                        </div>
                                        
                                    </div>  
                                </div>
                            </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

<!-- Mainly scripts -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="js/inspinia.js"></script>
<script src="js/plugins/pace/pace.min.js"></script>

<!-- jQuery UI custom -->
<script src="js/jquery-ui.custom.min.js"></script>

<!-- iCheck -->
<script src="js/plugins/iCheck/icheck.min.js"></script>

<!-- Full Calendar -->
<script src="js/plugins/fullcalendar/moment.min.js"></script>
<script src="js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script>

    $(document).ready(function() {

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });

        /* initialize the external events
         -----------------------------------------------------------------*/


        $('#external-events div.external-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1111999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });


        /* initialize the calendar
         -----------------------------------------------------------------*/
        // var date = new Date();
        // var d = date.getDate();
        // var m = date.getMonth();
        // var y = date.getFullYear();

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'agendaWeek',
            views: {
        agenda: {
            // options apply to agendaWeek and agendaDay views
            columnFormat: 'ddd D.M'
        }
    },
            //timeFormat: 'HH:mm',
            //axisFormat: 'HH:mm',
            firstDay: 1,
            //weekends: false,
            forceEventDuration: true,
            defaultTimedEventDuration: '00:45:00',
            slotDuration: '00:15:00',
            minTime: '08:00:00',
            maxTime: '20:00:00',
            businessHours: true,
            
            /*columnFormat: {
                week: 'ddd D MMM',
            },*/
            editable: true,
            selectable: true,
            selectHelper: true,
            select: function(start, end) {
                $("#myModal").modal();
                var title = prompt('Event Title:');
                var eventData;
                if (title) {
                    eventData = {
                        title: title,
                        start: start,
                        //end: end
                    };
                    $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                }
                $('#calendar').fullCalendar('unselect');
            },
            droppable: true, // this allows things to be dropped onto the calendar
            drop: function() {
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            },
            events: '/calendar-google-events'
        });


    });

</script>

@endsection