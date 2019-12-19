<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Importeren');
define('PAGE', 'settings');

include 'application/views/inc/header.php';
?>

<!-- /. NAV SIDE  -->
<div id="page-content-wrapper">
    <div class="container-fluid xyz">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    Migrate
                </h1>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-block"> 
                        <div class="notification-bar alert" style="display: none;"></div>

                        <div class="progress">
                            <div id="progress-bar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                0%
                            </div>
                        </div>

                        <a onclick="ajax_stream();" class="btn btn-secondary">Start migrate</a>
                        
                        <form method="post">
                            <button type="submit">ABC</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-block"> 
                        <div id="progress-log">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function ajax_stream() {
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
                //try {
                    if (xhr.readyState == 4) {
                        console.log('[XHR] Done')
                    }
                    else if (xhr.readyState > 2) {
                        var new_response = xhr.responseText.substring(xhr.previous_text.length);
                        var result = JSON.parse(new_response);

                        document.getElementById("progress-log").innerHTML += result.message + '<br />';
                        document.getElementById('progress-bar').style.width = result.progress + "%";
                        document.getElementById('progress-bar').innerHTML = result.progress + "%";

                        xhr.previous_text = xhr.responseText;
                    }
                /*}
                catch (e) {
                    console.log("[XHR STATECHANGE] Exception: " + e);
                }*/
            };
            xhr.open("POST", "<?=base_url()?>settings/migrate", true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send();
        }
        catch (e) {
            console.log("[XHR REQUEST] Exception: " + e);
        }
    }
</script>

<?php include 'application/views/inc/footer.php'; ?>