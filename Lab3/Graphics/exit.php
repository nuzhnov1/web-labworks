<?php
session_start();
session_unregister('username');
session_unregister('login');
session_unregister('user_level');
session_destroy();
header("Location: index.php");
