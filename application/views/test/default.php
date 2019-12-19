<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Test');
define('PAGE', 'dashboard');

include 'application/views/inc/header.php';
?>

<!-- Page Content -->
<div id="page-content-wrapper">
    <div class="container-fluid xyz">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-lg-12 col-ms-12">

                <div class="notification-bar alert" style="display: none;"></div>

                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Stream voorbeeld
                        </h1>
                    </div>
                </div> 

                <div class="row">
                    <div class="col-md-8 col-xs-8 col-lg-8">
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-block">
                                <progress id="progressBar" class="progress progress-striped" value="0" max="100">0%</progress>
                            </div>
                            
                            <button type="button" class="btn btn-secondary" onclick="ajax_stream();">Start!</button>
                            
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-4 col-lg-4">
                        <div class="card">
                            <div class="card-header">Voortgang</div>
                            <div class="card-block">
                                <div id="progress"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    function ajax_stream() {
        console.log("Start AjaxStream");
        if (!window.XMLHttpRequest) {
            console.log("Your browser does not support the native XMLHttpRequest object.");
            return;
        }
        try {
            var xhr = new XMLHttpRequest();
            xhr.previous_text = '';

            xhr.onerror = function () {
                console.log("[XHR] Fatal Error.");
            };
            xhr.onreadystatechange = function () {
                try {
                    if (xhr.readyState == 4) {
                        console.log('[XHR] Done')
                    }
                    else if (xhr.readyState > 2) {
                        var new_response = xhr.responseText.substring(xhr.previous_text.length);
                        var result = JSON.parse(new_response);

                        document.getElementById("progress").innerHTML += result.message + '<br />';
                        document.getElementById("progressBar").value  = result.progress;

                        xhr.previous_text = xhr.responseText;
                    }
                }
                catch (e) {
                    console.log("[XHR STATECHANGE] Exception: " + e);
                }
            };
            xhr.open("GET", "ajax", true);
            xhr.send();
        }
        catch (e) {
            console.log("[XHR REQUEST] Exception: " + e);
        }
    }
</script>

<?php include 'application/views/inc/footer.php'; ?>