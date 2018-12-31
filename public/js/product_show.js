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
        $cache.formSubmit = $cache.document.find('.form_file_submit');
        $cache.formUpload = $cache.document.find('.form-file-wrapper');
        $cache.progressWrapper = $cache.document.find('.js_file_progress_bar');
        $cache.errorModal = $cache.document.find('.js_toggle_error_modal');
        $cache.progressBar = $('#js_progress_percentage');
        $cache.fileUploaded = $cache.document.find('.js_file_uploaded')
    }

    function initializeEvents() {
        console.log('events');
        $cache.document.on('click','.js_form_file__upload',triggerFileUpload);
        $cache.document.on('click','.js_form_file__submit',triggerFormSubmit);
        $cache.document.on('change','.form-file-wrapper',fileUploaded);
    }

    function startUpload() {
        $cache.progressWrapper.show();
        $cache.progressBar.attr('data-progress',75);
        $cache.progressBar.css('width','75%');
        if (!validateFile()) {
            clearProgress();
        }
    }

    function fileUploaded(e) {
        $cache.progressBar.attr('data-progress',100);
        $cache.progressBar.css('width','100%');
        $cache.progressWrapper.hide();
        $cache.fileUploaded.show();
        if ( !validateFile() ) {
            clearProgress();
            $cache.errorModal.trigger('click');
        }
    }

    function clearProgress() {
        $cache.progressWrapper.hide();
        $cache.fileUploaded.hide();
    }

    function validateFile() {
        var $file = document.getElementById('csv_form_file').files[0];

        if(!$file) {
            return false;
        }

        if($file && $file.type !== 'text/csv') {
            document.getElementById('csv_form_file').value ="";
            return false;
        }

        return true;
    }

    function triggerFormSubmit(e) {
        e.preventDefault();
        if (validateFile()){

            $cache.formSubmit.trigger('click');
        } else {
            $cache.errorModal.trigger('click');
        }
    }
    function triggerFileUpload(e) {
        document.getElementById('csv_form_file').value ="";
        $cache.formUpload.trigger('click');
        startUpload();
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

