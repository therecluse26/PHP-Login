<?php
require 'autoload.php';
require './vendor/autoload.php';
include_once 'config.php';

$m = new MailSender;

$m->sendMail('staticheroproductions@gmail.com', 'blah', '123123123', 'Verify');
