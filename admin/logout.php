<?php
session_start();

/* hapus semua data session */
session_unset();

/* hancurkan session */
session_destroy();

/* balik ke login */
header("Location: index.php");
exit;
