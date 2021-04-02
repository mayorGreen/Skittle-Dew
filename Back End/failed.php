<?php

if (isset($_GET['failed']) && $_GET['failed'] == 1) {
    // treat the succes case ex:
    echo('<script type="text/javascript">alert("Failed to login. Incorrect Username or Password. Please hit OK to be redirected back to the login page.");location="../Front End/public_html/index.html";</script>');
}
