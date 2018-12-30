// if jQuery has not been loaded, load from google cdn
if (!window.jQuery) {
    var s = document.createElement('script');
    s.setAttribute('src', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js');
    s.setAttribute('type', 'text/javascript');
    document.getElementsByTagName('head')[0].appendChild(s);
}
var app = (function (app , $) {
    var $cache = {};
    function initializeCache() {
        console.log('cache');
        $cache.document = $(document);
        $cache.formSubmit = $cache.document.find('.js_form_file__submit');
        $cache.formSubmit = $cache.document.find('.js_form_file__upload');
    }

    function initializeEvents() {
        console.log('events');
        $cache.document.on('click','.js_form_file__upload',triggerFileUpload);
        $cache.document.on('click','.js_form_file__submit',triggerFormSubmit);
    }

    function triggerFormSubmit(e) {
        e.preventDefault();
        var $file = document.getElementById('csv_form_file').files[0];
        if($file){
            $cache.document.find('.form_file_submit').trigger('click');
        } else {
            $cache.document.find('.js_toggle_error_modal').trigger('click');
        }

    }
    function triggerFileUpload(e) {
        $cache.document.find('.form-file-wrapper').trigger('click');
    }

    app.components = app.components || {};
    app.components.product = {
        init: function(){
            initializeCache();
            initializeEvents();
        }
    };

    if($ != undefined){
        $(document).ready(function(){
            app.components.product.init();
        });
    }

    return app;
}(window.app = window.app || {}, jQuery = window.jQuery));

