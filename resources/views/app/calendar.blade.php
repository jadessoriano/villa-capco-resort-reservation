<x-app-layout>
  <div class="w-11/12 pt-5 mx-auto">
    <a class="block my-3" href="reservations"><x-button>Proceed to Booking</x-button></a>

    <div id='calendar' class=""></div>
  </div>

  @push('styles')
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
  @endpush

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
          var calendarEl = document.getElementById('calendar');

          var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
              left: 'prev,next today',
              center: 'title',
              right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: <?php echo json_encode($dates); ?>,
          });

          calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
