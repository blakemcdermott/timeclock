<?php
$to = $recipients; 
$from = 'Silverstone Management <management@silverstonepayments.com>';
$subject = 'New Silverstone Appointment';

$comments = stripcslashes($comments);
$ipconfig = "<a target=\"_blank\" href=\"http://www.ipconfig.co\" alt=\"Ipconfig.co\">".$ip." - by ipconfig.co</a>";

$message = "<html><head></head><body>
<h4>A new appointment has been set</h4>
<p><strong>Business Name:</strong> " . $company . "</p>
<p><strong>Street Address:</strong> " . $address1 . "</p>
<p><strong>City:</strong> " . $city . "</p>
<p><strong>State:</strong> " . $state . "</p>
<p><strong>Zip:</strong> "  . $zip . "</p>
<p><strong>First Name:</strong> "  . $firstname . "</p>
<p><strong>Last Name:</strong> "  . $lastname . "</p>
<p><strong>Title:</strong> "  . $title . "</p>
<p><strong>Business Phone:</strong> "  . $businessphone . "</p>
<p><strong>Cell Phone:</strong> "  . $mobilephone . "</p>
<p><strong>Email:</strong> "  . $email . "</p>
<p><strong>Monthly Volume:</strong> "  . $monthly_volume . "</p>
<p><strong>Average Ticket:</strong> " . $average_ticket . "</p>
<p><strong>Current Processor:</strong> "  . $current_processor . "</p>
<p><strong>Appt Date:</strong> "  . $appt_date . "</p>
<p><strong>Appt Time:</strong> "  . $appt_time . "</p>
<p><strong>Comments:</strong> "  . $comments . "</p><br />
<p><strong>Submitter Name:</strong> "  . $submitter . "</p>
<p><strong>Submitter's IP:</strong> "  . $ipconfig . "</p></body></html>";

$replyto = 'Silverstone Management <management@silverstonepayments.com>';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'From: ' . $from . "\r\n";
$headers .= 'Cc: ' . $cc . "\r\n";
$headers .= 'Bcc: ' . $bcc . "\r\n";

function sendMail($to, $subject, $message, $headers){
$mailResult = mail($to, $subject, $message, $headers);
if($mailResult){

} else {}
}
?>