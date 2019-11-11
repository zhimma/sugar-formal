(function($) {
    $.extend({
        ms_DatePicker: function(options) {
            var defaults = {
                Container: ".birthday_selector",
                YearSelector: ".sel_year",
                MonthSelector: ".sel_month",
                DaySelector: ".sel_day",
                YearFrom: 1900,
            };
            var opts = $.extend({}, defaults, options);

            // 向前相容
            var $container = $(opts.Container);
            if ($container.length === 0) {
                if ($(opts.YearSelector) > 1 || $(opts.MonthSelector) > 1 || $(opts.DaySelector) > 1) {
                    throw '多組日期應將 select 以 ".birthday-container" 或設定的 "Container" 包著';
                }
                $container = $(document);
            }

            // 初始化
            $.each($container, (i) => {
                ms_DatePicker($container.eq(i));
            });

            function ms_DatePicker($container) {
                var $year = $container.find(opts.YearSelector);
                var $month = $container.find(opts.MonthSelector);
                var $day = $container.find(opts.DaySelector);

                // 年月日 預設值
                year = $year.attr("rel") || $year.data("value") || Number($year.find("option[selected]").val()) || null;
                month = $month.attr("rel") || $month.data("value") || Number($month.find("option[selected]").val()) || null;
                day = $day.attr("rel") || $day.data("value") || Number($day.find("option[selected]").val()) || null;

                buildYear(year);
                buildMonth(month);
                buildDay(day);

                $month.on("change", function() {
                    buildDay($day.val());
                });

                $year.on("change", function() {
                    buildDay($day.val());
                });

                // 年份列表
                function buildYear(selectedYear) {
                    var yearNow = new Date().getFullYear();
                    $year.html($(`<option value="">年</option>`).prop("selected", !Boolean(selectedYear)));
                    for (var i = yearNow; i >= opts.YearFrom; --i) {
                        var isSelected = selectedYear == i ? "selected" : "";
                        $year.append($(`<option value="${i}" ${isSelected}>${i}</option>`));
                    }
                }

                // 月份列表
                function buildMonth(selectedMonth) {
                    $month.html($(`<option value="">月</option>`).prop("selected", !Boolean(selectedMonth)));
                    for (var i = 1; i <= 12; ++i) {
                        var isSelected = selectedMonth == i ? "selected" : "";
                        $month.append($(`<option value="${i}" ${isSelected}>${i}</option>`));
                    }
                }

                // 日期列表
                function buildDay(selectedDay) {
                    var dayCount = new Date($year.val(), $month.val(), 0).getDate();
                    if (selectedDay > dayCount) selectedDay = null;
                    $day.html($(`<option value="">日</option>`).prop("selected", !Boolean(selectedDay)));
                    for (var i = 1; i <= dayCount; ++i) {
                        var isSelected = selectedDay == i ? "selected" : "";
                        $day.append($(`<option value="${i}" ${isSelected}>${i}</option>`));
                    }
                }
            }
        },
    });
})(jQuery);