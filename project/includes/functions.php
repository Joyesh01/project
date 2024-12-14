<?php
function redirect($url) {
    header("Location: $url");
    exit();
}

function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
