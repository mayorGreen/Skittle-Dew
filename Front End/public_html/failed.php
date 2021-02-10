<?php

if (isset($_GET['failed']) && $_GET['failed'] == 1) {
    // treat the succes case ex:
    echo('<script type="text/javascript">alert("Failed to login!");location="http://localhost/Buch_County/index.html";</script>');
}
