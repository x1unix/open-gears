<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <style>
        .ico {
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OHB4IiB2aWV3Ym94PSIwIDAgNDggNDgiIGZpbGw9Im5vbmUiPgogIDxwYXRoIHN0cm9rZT0iIzVhNWE1YSIgc3Ryb2tlLXdpZHRoPSIzIiBzdHJva2UtbGluZWNhcD0ic3F1YXJlIiBkPSJNMS41IDguNSB2MzQgaDQ1IHYtMjggbS0zLTMgaC0xMCB2LTMgbS0zLTMgaC0xMCBtMTUgNiBoLTE4IHYtMyBtLTMtMyBoLTEwIi8+CiAgPHBhdGggc3Ryb2tlPSIjNWE1YTVhIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJzcXVhcmUiIGQ9Ik0xMiAzNSBoMiBtMi0yIGgxMiBtMiAyIGgzIG0yIDIgaDMgTTExIDIxIGwwIDAgbTAgNCBoMCBtNCAwIGgwIG0wLTQgaDAgbS0yIDIgaDAgTTMzIDIxIGwwIDAgbTAgNCBoMCBtNCAwIGgwIG0wLTQgaDAgbS0yIDIgaDAiLz4KPC9zdmc+Cg==);
            background-position: center;
            background-repeat: no-repeat;
            height:48px;
            width:48px;
            display: block;
            margin-bottom:32pt;
        }
        html, body {
            font: 10pt "Helvetica Neue",helvetica,arial,sans-serif;
            min-height: 100%;
            margin:0;
            padding:0;
            background: #fafafa;
            height:100%;
            color:#595959;
        }
        h1 {
            font-weight: normal;
            font-size:18pt;
        }
        pre {
            font-size:9pt;
        }
        .hand {
            position: relative;
            height:100%;
        }
        .more {
            margin-top:48px;
        }
        .row {
            position: absolute;
            margin:auto;
            top:0;
            left:0;
            right:0;
            bottom:0;
            height:256px;
            width:600px;
        }
        #error {
            display:none;
        }
        #expandText {
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php
if(!isset($title)) $title = "Oops...";
if(!isset($desc))   $desc = "An error has occurred in this application. Click \"Read more\" to see details.";
if(!isset($err))     $err = false;

?>
    <div class="hand">
        <div class="row">
            <div class="ico"></div>
            <div class="msg">
                <h1><?=$title?></h1>
                <p><?=$desc?></p>
            </div>
            <?php if($err != false) { ?>
                <div class="more">
                    <u id="expandText">Read more</u>
                    <div id="error">
                        <pre><?=$err?>
                        </pre>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<script>
    document.querySelector("#expandText").onclick = function(){
        this.style.display = "none";
        document.querySelector("#error").style.display = "block";
        document.querySelector(".row").style.height = "340px";
    };
</script>
</body>
</html>
