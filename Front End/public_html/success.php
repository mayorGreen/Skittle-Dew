<?php
if ( isset($_GET['success']) && $_GET['success'] == 1 )
{
    // treat the succes case ex:
    echo('<script type="text/javascript">alert("Successfully Logged in!");location="http://localhost/Buch_County/index.html";</script>');
}