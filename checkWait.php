<?php

//Checks for processes running on the listener that represent already active sessions. Returns true if one of these processes is found.

function checkWait() {
    $result = shell_exec('ps -l -U www | grep -v piperd | grep -E \'(select|RUN)\' | grep -v grep' );
    if (!empty($result)) {
        return true;
    } else {
        return false;
    }
}

