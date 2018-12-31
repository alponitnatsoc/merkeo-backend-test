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
        $cache.document = $(document);
        $cache.formSubmit = $cache.document.find('.form_file_submit');
        $cache.formUpload = $cache.document.find('.form-file-wrapper');
        $cache.progressWrapper = $cache.document.find('.js_file_progress_bar');
        $cache.errorModal = $cache.document.find('.js_toggle_error_modal');
        $cache.formActionUrl = $cache.document.find('.form-wrapper').attr('data-action');
        $cache.progressBar = $('#js_progress_percentage');
        $cache.comandLog =  $cache.document.find('.js-response-log');
        $cache.fileUploaded = $cache.document.find('.js_file_uploaded');
        $cache.executeUrl = $cache.document.find('.js_execute_commands_url').val();
    }

    function initializeEvents() {
        $cache.document.on('click','.js_form_file__upload',triggerFileUpload);
        $cache.document.on('click','.js_form_file__submit',triggerFormSubmit);
        $cache.document.on('change','.form-file-wrapper',fileUploaded);
    }

    function startUpload() {
        $cache.comandLog.html('');
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

    function readFile(){
        var reader = new FileReader();
        reader.onload = function(e) {
            var lines = e.target.result.split("\n");
            var buffer = 20;
            var times = Math.floor(lines.length / buffer);
            var residue = lines.length % buffer;

            for (var i = 0; (residue > 0 && i < (times+1)) || (residue <= 0 && (i<times));i++){
                var data = {}, idx = 0;
                for (var j = i*buffer; j < (buffer*(i+1)) && j < lines.length; j++){
                    var values = lines[j].split(",");
                    switch (values.length) {
                        case 2:
                            data[idx] = {"product-id":values[0],"command":values[1].toLowerCase(),"line":(j+1)};
                            break;
                        case 3:
                            data[idx] = {"product-id":values[0],"command":values[1].toLowerCase(),"amount":values[2],"line":(j+1)};
                            break;
                        default:
                            data[idx] = {"line":(j+1)};
                            break;
                    }
                    idx++;
                }
                jQuery.ajax({
                    type: "POST",
                    url: $cache.executeUrl,
                    contentType: "application/json",
                    data: JSON.stringify(data),
                    success: function(data){
                        $cache.comandLog.append(data.response);
                        refreshTable();
                    }
                });
            }

        };

        reader.readAsText(document.getElementById('csv_form_file').files.item(0));
    }

    function refreshTable(){
        jQuery.ajax({
            url: $cache.formActionUrl,
            success: function(data,response){
                var result = $('<div />').append(data).find('.Products-wrapper').html();
                $cache.document.find('.Products-wrapper').html(result);
            }
        })
    }

    function triggerFormSubmit(e) {
        e.preventDefault();
        if (validateFile()){
            readFile();
            // $cache.formSubmit.trigger('click');
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

