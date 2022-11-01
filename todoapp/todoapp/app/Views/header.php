<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">

  <head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
    <title>Cynic Todo App</title>
    <link rel="stylesheet" type="text/css" href="/css/water.css" /> 
  </head>

<body>
<h1>Cynic Todo App</h1>

<?= session()->getFlashdata('error') ?>
<?= session()->getFlashdata('message') ?>
<?= service('validation')->listErrors() ?>

<ul>
<li><a href="/">Home</a></li>
<?php if (isset($jwt) && $jwt !== NULL): ?>
    <li><a href="/logout">Logout</a></li>
<?php else: ?>
    <li><a href="/login">Login</a></li>
<?php endif ?>
</ul>