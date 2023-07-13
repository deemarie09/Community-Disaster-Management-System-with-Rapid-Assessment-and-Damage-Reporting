<?php
require('html_table.php');

$htmlTable='<table>
<tr>
<td>S. No.</td>
<td>Name</td>
<td>Age</td>
<td>Gender</td>
<td>Location</td>
</tr>

<tr>
<td>1</td>
<td>Azeem</td>
<td>24</td>
<td>Male</td>
<td>Pakistan</td>
</tr>

<tr>
<td>2</td>
<td>Atiq</td>
<td>24</td>
<td>Male</td>
<td>Pakistan</td>
</tr>

<tr>
<td>3</td>
<td>Shahid</td>
<td>24</td>
<td>Male</td>
<td>Pakistan</td>
</tr>

<tr>
<td>4</td>
<td>Roy Montgome</td>
<td>36</td>
<td>Male</td>
<td>USA</td>
</tr>

<tr>
<td>5</td>
<td>M.Bony</td>
<td>18</td>
<td>Female</td>
<td>&nbsp;</td>
</tr>
</table>';

$pdf=new PDF_HTML_Table();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->WriteHTML("Start of the HTML table.<br>$htmlTable<br>End of the table.");
$pdf->Output();
?>