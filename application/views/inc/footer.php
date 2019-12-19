</div>
</div>
</div>
</body>
<script type="text/javascript">
<?php

// Error message display
if (isset($tpl_msg)) {
    if (isset($tpl_msgtype) && isset($tpl_timeout)) {
        echo "createNotification('" . $tpl_msgtype . "', '" . $tpl_msg . "', '" . $tpl_timeout . "');";
        echo "console.log('".$tpl_msgtype.": ".$tpl_msg."');";
    } elseif (isset($tpl_msgtype)) {
        echo "createNotification('" . $tpl_msgtype . "', '" . $tpl_msg . "');";
        echo "console.log('".$tpl_msgtype.": ".$tpl_msg."');";
    } else {
        echo "createNotification('error', '" . $tpl_msg . "');";
        echo "console.log('error: ".$tpl_msg."');";
    }
}
?>
    
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-47855127-22', 'auto');
    ga('send', 'pageview');
</script>
</html>
