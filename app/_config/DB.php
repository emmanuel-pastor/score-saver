<?php
function openConnection(): mysqli
{
    createUserTableIfNotExists();
    createScoreTableIfNotExists();

    return openBaseConnection();
}

function createUserTableIfNotExists()
{
    $db = openBaseConnection();
    $userTableCreationQuery = "CREATE TABLE IF NOT EXISTS `user` ( 
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(256) NOT NULL,
    `password` varchar(256) NOT NULL,
    `is_valid` tinyint(1) NOT NULL DEFAULT '0',
    `validation_key` varchar(256) NOT NULL,
    PRIMARY KEY (`id`) )
    ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
    $db->query($userTableCreationQuery);
    closeConnection($db);
}

function createScoreTableIfNotExists() {
    $db = openBaseConnection();
    $scoreTablesCreationQuery = "CREATE TABLE IF NOT EXISTS `score` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `value` int(5) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
    $db->query($scoreTablesCreationQuery);
    closeConnection($db);
}

function openBaseConnection() {
    return mysqli_connect('localhost', DB_USER, DB_PASSWORD, DB_NAME);
}

function closeConnection($connection)
{
    mysqli_close($connection);
}