<?php
function openConnection(): mysqli
{
    return new mysqli(HOST_NAME, DB_USER, DB_PASSWORD, DB_NAME);
}

function closeConnection($connection) {
    mysqli_close($connection);
}