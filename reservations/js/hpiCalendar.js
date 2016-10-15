
angular.module('reservations')
  .directive('hpiCalendar', ['$parse', function($parse) {
    var COLORS = ['#F44336', '#E91E63', '#9C27B0', '#673AB7', '#3F51B5', '#2196F3', '#03A9F4', '#00BCD4', '#009688', '#43A047', '#689F38', '#C0CA33', '#F9A825', '#EF6C00', '#FF5722'];
    var colorOffset = 0;
    var colorCache = {};
    function getColor(id) {
      var current = colorCache[id];
      if (current && id)
        return current;

      return (colorCache[id] = COLORS[colorOffset++ % COLORS.length]);
    }

    function clearDay(date) {
      var d = new Date(date);
      d.setHours(0);
      d.setMinutes(0);
      d.setSeconds(0);
      d.setMilliseconds(0);
      return d;
    }


    function link($scope, $element, $attrs) {
      var onEventClicked = $parse($attrs.onEventClicked, null, true);
      var onEventAdd = $parse($attrs.onEventAdd, null, true);
      var $table = $element.find('.calendar');
      var $calendarScroll = $element.find('.calendar-scroll');

      var HOUR = 60 * 60 * 1000;
      var DAY = 24 * HOUR;

      var currentDate = clearDay(new Date());
      currentDate = currentDate - currentDate.getDay() * DAY;

      var globalOffset = $element.offset();
      var middayOffset = $element.find('.calendar tr:nth-child(9)').offset();
      $calendarScroll.scrollTop(middayOffset.top - globalOffset.top);

      var MONTHS = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

      /**
       * Returns HTML describing the date range within start and end
       */
      function getDateDisplay(start, end) {
        var dayStart = start.getDate();
        var dayEnd = end.getDate();

        var monthStart = start.getMonth();
        var monthEnd = end.getMonth();

        var yearStart = start.getFullYear();
        var yearEnd = end.getFullYear();

        if (monthStart != monthEnd || yearStart != yearEnd)
          return dayStart + '. <small>' + MONTHS[monthStart] + ' ' + yearStart + '</small> - ' +
                 dayEnd + '. <small>' + MONTHS[monthEnd] + ' ' + yearEnd + '</small>';

        return dayStart + '. - ' + dayEnd + '. <small>' + MONTHS[monthStart] + ' ' + yearStart + '</small>';
      }

      $(function() { reposition (); });

      function reposition() {
        var events = $scope.events;

        var mainOffset = $calendarScroll.offset();
        $('.days').width($table.outerWidth());

        var startDate = new Date(currentDate);
        var endDate = new Date(currentDate + DAY * 7);

        var display = $('#cur-date');
        display.html(getDateDisplay(startDate, endDate));

        $calendarScroll.find('.event').remove();
        if (!events)
          return;

        events.forEach(function(event) {
          if(typeof event.date !== 'Date')
            event.date = new Date(event.date);

          var eventStart = new Date(event.date);
          var eventEnd = new Date(+event.date + event.duration * 60 * 1000);

          if ((eventStart < startDate && eventEnd < startDate) || eventStart >= endDate)
            return;

          var dayOffset = startDate - clearDay(eventStart);
          var startDay = eventStart < startDate ? 0 : eventStart.getDay();
          var endDay = eventStart > startDate && eventEnd < endDate ? eventEnd.getDay() :
            3; // TODO multi week, calculate end day, calculate end day

          var firstDayOffset = +eventStart - +clearDay(eventStart);

          var nDays = endDay - startDay + 1;
          var color = getColor(event.id);

          for (var i = 0; i < nDays; i++) {
            var columnNum = startDay + i + 1;
            var column = $('tr:first-child td:nth-child(' + columnNum + ')', $table)
            var offset = column.offset();
            var MARGIN_LEFT = 40;
            var BORDER_OFFSET = 2;

            var totalHeight = $table.height();

            var dayStart, duration;
            if (i == 0)
              dayStart = eventStart;
            else
              dayStart = new Date(clearDay(eventStart) + DAY * i);

            if (i == 0 && (firstDayOffset + event.duration * 60 * 1000) > DAY)
              duration = (DAY - firstDayOffset) / (60 * 1000);
            else if (i == 0)
              duration = event.duration;
            else if (i == nDays - 1)
              duration = event.duration - (i * 60 * 24 - firstDayOffset / (60 * 1000));
            else
              duration = 60 * 24;

            var top = (dayStart.getHours()) * totalHeight / 24 + BORDER_OFFSET;
            var height = (duration / 60) * totalHeight / 24;

            var _title = $scope.eventTitle({ event: event });
            var title = i == 0 ? _title : '<em>' + _title + ' (Fortgesetzt)</em>';
            $('<div class="event">' + title + '</div>')
              .css({
                width: column.outerWidth() - MARGIN_LEFT - 4,
                height: height,
                backgroundColor: color,
                top: top,
                left: MARGIN_LEFT + offset.left - mainOffset.left
              })
              .attr('title', event.title)
              .click(function() {
                $scope.$apply(function() {
                  onEventClicked($scope.$parent, { event: event });
                });
              })
              .appendTo($calendarScroll);
          }
        });
      }

      $scope.addEvent = function addEvent(hour, day) {
        onEventAdd($scope.$parent, { date: new Date(currentDate + DAY * day + hour * HOUR) });
      };

      $scope.prevWeek = function prevWeek() {
        currentDate -= DAY * 7;
        reposition();
      };

      $scope.nextWeek = function nextWeek() {
        currentDate += DAY * 7;
        reposition();
      };

      $scope.$watch('events', function(events) {
        reposition();
      }, true);

      $scope.$watch('repositionTrigger', function() {
        setTimeout(function() {
          reposition();
        }, 200);
      });
    }

    return {
      restrict: 'E',
      scope: {
        events: '=',
        repositionTrigger: '=',
        eventTitle: '&'
      },
      link: link,
      templateUrl: '/templates/calendar.html'
    };
  }]);

