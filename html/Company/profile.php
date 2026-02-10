<?php
require_once "dbconnectcompany.php";

$tab = $_GET['tab'] ?? 'overview';

switch ($tab) {
    case 'overview':
        include "overview.php";
        break;
    case 'education':
        include "education.php";
        break;
    case 'documents':
        include "documents.php";
        break;
    case 'notes':
        include "notes.php";
        break;
    default:
        include "overview.php";
}
?>