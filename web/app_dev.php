<?php
// This file is mainly here for use with symfony server commands.
// Recommended rewrite rules for apache and nginx does not use this.

putenv('SYMFONY_ENV=dev');

require 'app.php';
