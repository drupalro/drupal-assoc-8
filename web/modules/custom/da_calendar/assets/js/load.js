(function ($, Drupal) {
  Drupal.behaviors.da_calendar_load_calendar_entries = {
    attach: function (context, settings) {
      $('#calendar').fullCalendar({
        locale: document.documentElement.lang,
        events : '/events/calendar',
        displayEventTime: false,
        error: function() {
          alert('there was an error while fetching events!');
        }
      })
    }
  }
})(jQuery, Drupal);
