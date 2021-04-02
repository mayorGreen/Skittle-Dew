<?php
if ( isset($_GET['success']) && $_GET['success'] == 1 )
{
    // treat the succes case ex:
    echo('<script type="text/javascript">alert("Successfully Logged in. Hit OK to be redirected.");location="../Front End/public_html/tabs.html";</script>');
}