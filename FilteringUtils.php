<?php
function filterInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

$nameRegex = "/^[a-zA-z-' ]*$/";
# email validating using a filter_var
#password validating
$passwordRegex = "/^[a-zA-Z0-9_]{8,15}$/";

