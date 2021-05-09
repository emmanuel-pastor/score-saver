<?php
function openConnection(): mysqli
{
    return new mysqli("localhost", "manu", "root", "score_saver");
}

function closeConnection($connection) {
    mysqli_close($connection);
}