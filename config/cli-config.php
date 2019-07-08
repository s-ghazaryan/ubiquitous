<?php

use App\Database\Connection;

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(Connection::getEntityManager());