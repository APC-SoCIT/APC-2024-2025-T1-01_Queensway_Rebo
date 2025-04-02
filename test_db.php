
<?php
include 'connector.php';

if ($dbconn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}
?>