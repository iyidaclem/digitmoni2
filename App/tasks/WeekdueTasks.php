<?php 
namespace tasks;

use core\Model;
use API\Model\UserInvestment;
use database\DB;


function weeklydue(){
  //run a query to fetch everything where duedate is between current date and next seven days
  //and roll over is off

  //run another query to get all where termination date is within next week and rollover is on


}