<?php

/*The requestTimeOff api accepts four paramters and applies for a time off for the specfied date period.
  Also checks if there is sufficient leave balance before applying for leave.    
*/

/*Copyright (c) 2017, Harbinger Systems Private Limited
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of HRMleaveapi nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/



    class EmployeeLeaveApplicationHelper
    {
      public function requestTimeOff($empid,$fromdate,$todate,$leavetype,$reason)
      {

        if(!isset($empid)||empty($empid)||$empid==null||$empid=="null")
        {
          $message=array('status'=>'error','code'=>'no_empid','data'=>'Please provide the employee id.');
          return $message;
          //exit();
        }
        if(!isset($fromdate)||empty($fromdate)||$fromdate==null||$fromdate=="null")
        {
          $message=array('status'=>'error','code'=>'no_fromdate','data'=>'Start date not specified.');
          return $message;
          exit();
        }
        if(!isset($todate)||empty($todate)||$todate==null||$todate=="null")
        {
          $message=array('status'=>'error','code'=>'no_todate','data'=>'End date has not been specified.');
          return $message;
          exit();
        }
        if(!isset($leavetype)||empty($leavetype)||$leavetype==null||$leavetype=="null")
        {
          $message=array('status'=>'error','code'=>'no_type','data'=>'Time off type is not mentioned.');
          return $message;
          exit();
        }
        if(!isset($reason))
        {
        	$reason="No comments";
        }
        
	
	$message='';
	
        $leaveTypeObj=new LeaveTypeService();
        $leaveType=$leaveTypeObj->readLeaveTypeByName($leavetype);
        $leaveTypeId=$leaveType["id"];

        //Get the leave balance of that user using empNumber and leaveTypeId as parameters
        $balance=new LeaveEntitlementService();
        $bDetails=$balance->getLeaveBalance($empid,$leaveTypeId);
        $res= (array)$bDetails;
	
	
	$date1=date_create($fromdate);
	$date2=date_create($todate);
	$diff=date_diff($date2,$date1);
	$array=(array)$diff;
	$days=$array["days"];
	$count;
	$start=strtotime($fromdate.' 00:00:00');
	$end=strtotime($todate.' 00:00:00');
	while($start<=$end)
	{
		if(date('w',$start)==0||date('w',$start)==6)
		$count++;
		$start=strtotime('+1 day',strtotime(date("Y",$start).'-'.date("m",$start).'-'.date("d",$start).' 00:00:00'));
	}
	$totalDays=$days-$count;
	if($totalDays>$res["balance"])
	{
		$message=array('status'=>'error','code'=>'no_balance','data'=>'Your leave balance is zero');	
	    	return $message;
            	exit();
	}
        else if((($res["balance"]<=0)))
        {
        	$message=array('status'=>'error','code'=>'no_balance','data'=>'Your leave balance is zero');	
	    	return $message;
            	exit();
        }
        else{
        //Inserting leave record.

         $formParameters=array('txtEmpID'=>$empid,'txtFromDate'=>$fromdate,'txtToDate'=>$todate,'txtFromTime'=>null,'txtToTime'=>null,'txtLeaveType'=>$leaveTypeId,'txtLeaveTotalTime'=>null,'txtComment'=>$reason,'txtEmpWorkShift'=>'8.00','partialDays'=>null,'duration'=>'full_day','firstDuration'=>0,'secondDuration'=>0,'source'=>'webservice');

         $obj=new LeaveParameterObject($formParameters);
        $service=new LeaveApplicationService();
        $res=$service->applyLeave($obj);

        if(!isset($res))
        {
            $message=array('status'=>'error','code'=>'no_data','data'=>'Your leave request could not be registered.');
            return $message;
            exit();
        }
        else if(isset($res))
        {
          $leaveDetails=array('id'=>$res["id"],'leave_type_id'=>$res["leave_type_id"],'date_applied'=>$res["date_applied"],'emp_number'=>$res["emp_number"],'comments'=>$res["comments"]);
          $message=array('status'=>'success','code'=>200,'data'=>$leaveDetails);
          return $message;
          exit();
        }
        else
        {
        	return $res;
        	exit();
        }
        }
      }
    }
?>
