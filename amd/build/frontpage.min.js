define(['core/ajax'], function(ajax) {

    start_app = function(pp) {
        var promises = ajax.call([
            { methodname: 'theme_ead_lanes', args: {}},
        ]);

        promises[0].done(function(response) {
            console.log('theme_ead_lanes', response);
            new Vue({
                el: '#mbus',
                delimiters: ["[[", "]]"],
                data: response, 
            });
            $('.mbus_loader').hide();
            $('#mbus').removeClass('hide');
        }).fail(function(ex) {
            $('.mbus_loader').hide();
            console.error(ex);
        });
    };
 
    return { 
        init: function() {
            start_app();
        }
    };
});
