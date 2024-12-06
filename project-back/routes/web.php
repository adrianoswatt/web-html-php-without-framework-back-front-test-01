<?php

$adminRoutes = require 'routes/admin.php';
$clientRoutes = require 'routes/client.php';

return array_merge($clientRoutes, $adminRoutes);