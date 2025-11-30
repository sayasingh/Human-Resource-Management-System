<?php
// session_config.php

// Set session cookie parameters
session_set_cookie_params(3600); // Session cookie expires after 1 hour

// Set session GC (Garbage Collection) max lifetime
ini_set('session.gc_maxlifetime', 3600); // Server session expires after 1 hour

// Start the session
session_start();
