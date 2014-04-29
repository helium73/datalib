<?php
require_once("common/database/report.php");
$report = new Report("select * from documents");
$report->set_primary_key_column("document_id");
$report->print_report(30,5, "report_test.php");
?>