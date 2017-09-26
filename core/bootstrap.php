<?php
require 'core/App.php';
require 'core/Request.php';
require 'core/Router.php';
require 'core/Database.php';
require 'app/controller/ActionController.php';
require 'app/controller/PagesController.php';
require 'app/model/Manager.php';
require 'core/functions.php';

use App\Core\App;
use App\Core\Database\Database;

session_start();

App::$config = require 'config.php';
Database::connect();
App::auth();
