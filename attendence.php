<?php 
session_start();
include("studentdbconn.php");
    if(isset($_POST['submit']))
    { 
        include("studentdbconn.php");
        
        //data take from form
        $enr=$_SESSION['enroll'];
        $status=$_POST['status'];
        $foodtype=$_POST['foodtype'];
        $currentdate = date('Y-m-d');
        //echo $currentdate; 
        //echo "<br>";
        $d = date_parse_from_format("Y-m-d", $currentdate);
        $currentmonth = $d["month"];
        //echo $currentmonth;
        $sql = "SELECT * FROM dinningstatus WHERE studentid='$enr' and
        month='$currentmonth'";
        $sqldata = mysqli_query($dbconn, $sql) or die('error getting');
        while($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) 
        {
        //previous data i.e date when the student update his status is taken from database  
        $previousdate = $row['updated_on'];
        $previousstatus = $row['current_status'];
           
        // the date is in timestamp formate so we convert it into only date
        $timestamp = strtotime($previousdate);
           
        //current date when student update his status
        $newpreviousdate =  date('Y-m-d', $timestamp);
        //echo $newpreviousdate;
        //echo "<br>";
        //echo $currentdate;
        //$days=date_diff($currentdate,$newpreviousdate);
        //echo $days;
        
        //echo "<br>"; 
        //create two object of date for calculating the date difference.
        $date = new DateTime($newpreviousdate);
        $now = new DateTime($currentdate);
        //calculate no. of days difference b/w two dates
        $month = $date->diff($now)->format("%d");
            //echo "hey date";
        //echo $month;
        }
        
        //print the current month
        $d = date_parse_from_format("Y-m-d", $currentdate);
        $currentmonth = $d["month"];
         //echo "hey month";
        //echo $currentmonth;

        $result = mysqli_query($dbconn, $sql);
        if((mysqli_num_rows($result)>0)&&($newpreviousdate!=$currentdate)){
            
         $sqlinsert = "UPDATE dinningstatus SET current_status = '$status', current_status = '$status', updated_on = '$currentdate', month = '$currentmonth', foodtype = '$foodtype' WHERE studentid='$enr' and
        month='$currentmonth' ";
        $run =mysqli_query($dbconn, $sqlinsert);
        
        $sql = "SELECT * FROM dinningstatus WHERE studentid='$enr'and
        month='$currentmonth'";
        $sqldata = mysqli_query($dbconn, $sql) or die('error getting');
        while($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) 
        {
        //fetch the current status of student
        $currentstatus = $row['current_status'];
        //echo $currentstatus;
        //check condition and calculate no of absent days or present days
        if(($currentstatus=="on" && $previousstatus=="off")||($currentstatus=="off" && $previousstatus=="off"))
        {
            
        $sqlinsert = "UPDATE dinningstatus SET no_of_days_absent = no_of_days_absent + '$month' WHERE studentid='$enr'and
        month='$currentmonth'";
        mysqli_query($dbconn, $sqlinsert);
        }else if(($currentstatus=="off" && $previousstatus=="on")||($currentstatus=="on" && $previousstatus=="on") )
        {
          $sqlinsert = "UPDATE dinningstatus SET no_of_days_present = no_of_days_present + '$month' WHERE studentid='$enr'and
        month='$currentmonth'";
        mysqli_query($dbconn, $sqlinsert);   
            
        }
        }
        }
        //if the record is not present in the database then insert record in the table
        else if(mysqli_num_rows($result)==0){
             $sqlinsert = "INSERT INTO dinningstatus (studentid, current_status, updated_on, month, no_of_days_absent, no_of_days_present, foodtype) VALUES('$enr','$status', '$currentdate', '$currentmonth', 0, 0, '$foodtype')";
            echo "new user";
            mysqli_query($dbconn, $sqlinsert);
        }
            else
            {
                echo "<script>alert('You have already updated your status today. Update it tomorrow!')</script>";
                
            }
            
           

    /*$sqlinsert = "INSERT INTO dinningstatus (studentid, dinstatus) VALUES('$enr','$status')";
    if(mysqli_query($dbconn, $sqlinsert))
    {
        echo "yes";
    }*/
}

?>

<!DOCTYPE html>
<html>
    <head> 

            <title>index::</title>
            <link rel="stylesheet" href="../css/nav.css">
             <link rel="stylesheet" href="../css/icon.css">

        <style>
            body{
                
                   background-color: whitesmoke;
        background-image: url(../img/salad-2068220_960_720.jpg);
        background-size: cover;
    
            }
            table {
                border-collapse: collapse;
                width: 500px;
                float: right;
                margin-right: 2px;
                margin-top: 0px; 
                background-color: rgb(0,0,0,0.0);
                
                }

            th, td {
                text-align: center;
                padding: 8px;
                width: auto;
                background-color: rgb(0,0,0,0.0); 
                color:white;
                }

           tr:nth-child(even){background-color: rgb(0,0,0,0.0); color:white;}
         tr:nth-child(odd){background-color: rgb(0,0,0,0.0); color:white;}
           
             th {
           background-color: rgb(0,0,0,0.0);;
           color: darkorange;
          width: auto;
        }
 .button {
            display: inline-block;
            background-color: darkorange;
            border: none;
            color: #CC4700;
            text-align: center;
            font-size: 15px;
            padding: 10px 18px;
            width: 100px;
            transition: all 0.5s;
            cursor: pointer;
            margin-top: 30px;
            
             margin-right: 20px;
             box-shadow: 3px 3px #FF9F6B;
             border-radius: 10px;
             height: 40px;
 
}

            .button span {
                cursor: pointer;
                display: inline-block;
                position: relative;
                transition: 0.5s;
                }

            .button span:after {
                content: '\00bb';
                position: absolute;
                opacity: 0;
                top: 0;
                right: -20px;
                transition: 0.5s;
                }

            .button:hover span {
                padding-right: 25px;
                }

            .button:hover span:after {
                opacity: 1;
                right: 0;
                }
    
            .legend{
                color: darkorange;
                }
             .i img{
        width:180px; 
        height:200px;
        margin-top: 50px; 
     
        border: 2px solid black;
           }
            #profile{
        width:22%; 
        height:585px; 
        background-color: rgb(0,0,0,0.5); 
        
        margin-bottom:12px; 
        float:left;
        margin-left: 7%;
        margin-top: 50px;
        color: white;
        
    }
            .ima{
                background-color: white;
            }
           
        </style>  
                 <script type="text/javascript">
function status()
{
if(document.getElementById('check').checked)
    {
        alert('hello');
    }
   
}
        </script>  
</head>

<body>
     <?php include '../html/header.php'; ?> 
    
    <!-- navigation bar -->
    
    <div class="navbar">
        <ul>
            <a href="#">Dashboard</a>
            <a href="attendence.php">Attendance</a>

            <a href="logout.php" style="float: right; margin-right: 20px;">Log out</a>      
        </ul>
    </div>

    <fieldset style="width:30%;margin-top:5%;">
             <legend class="legend"><b>Attendance</b></legend>
             
              <?php 
                $enr=$_SESSION['enroll'];
                $sqlget = "SELECT * FROM dinningstatus WHERE studentid = '$enr'";
                $sqldata = mysqli_query($dbconn, $sqlget) or die('error getting');
            ?>
       
                
                <table>
                        <tr>
                            <th>Date</th>
                            <th>FingerPrint</th>
                            <th>Status</th>
                            


                           
                            
                        </tr>
                         
                        <?php  while($row = mysqli_fetch_array($sqldata)) { ?><br>
							<tr>
                    
                           <td> <?php echo $row['updated_on']; ?></td>
                            <td>Scanned</td>
                           <td> <?php echo $row['current_status']; ?></td>
                          
                           
                            </tr>
								<?php } ?>
  
                  				
                    </table>
  
        </fieldset>

