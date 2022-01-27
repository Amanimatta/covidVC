<?php
session_start();
if(!isset($_SESSION['userId'])){
    die(print_r("The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached."));
}
else{
    try{
                include('dbConnect.php');
                //get patient id from previous session
                $provider_id = intval($_SESSION['userId']);
                
                $postAppointmentId=intval($_POST['compBtn']);
                //sql to update the appointment_matches if the accepted vaccine appointment is cancelled
                $sqlComp = "UPDATE appointment_matches 
                SET match_status='completed'  
                FROM 
                appointment_matches app 
                JOIN available_slots avs 
                ON app.appointment_id=avs.appointment_id
                WHERE app.appointment_id=? 
                AND 
                avs.provider_id=? 
                AND 
                match_status='accepted';";

                //cancel appointment sql execution
                $paramComp = array($postAppointmentId,$provider_id);
                $stmtComp = $conn->prepare($sqlComp);
                $stmtComp->execute($paramComp);

                header("Location: providerDashboard.php");

}
catch(Exception $e){
    die(print_r($e->getMessage()));
}

}
?>
