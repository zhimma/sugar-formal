var waitingDialog = waitingDialog || (function($) {
    'use strict';
    // Creating dialog's DOM
    var $dialog = $(
        '<div class="loading-font" style="padding-top:0; overflow-y:visible;">' +
        '<div class="loader">' +
        'Loading...' +
        '</div>' +
        '</div>');

    return {
        show: function() {
            // Opening dialog
            $('body, .modal').append($dialog);
            // $('body, .modal').append('sdasdadjasdkalsdjalsdkalsdjalsdjaksdlaskdla');
        },
        /**
         * Closes dialog
         */
        hide: function() {
            $('.loading-font').remove();
            $dialog.remove();
        }
    };

})(jQuery);