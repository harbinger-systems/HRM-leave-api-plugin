<?php

/*This api will return the leave balance for the different leave types available. It accepts the employee id as a parameter.*/


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

    class EmployeeLeaveBalanceHelper
    {
      public function getLeaveBalance($empId)
      {
        if(!isset($empId))
        {
          $message=array('status'=>'error','code'=>'no_empid','data'=>'Please provide the employee id');
          return json_encode($message);
          exit();
        }
        $message='';

	
	$type=new LeaveTypeService();
	$types=$type->getLeaveTypeList();
	$leaveBalance='';
	for($i=0;$i<sizeof($types);$i++)
	{
		$balance=new LeaveEntitlementService();
        	$bDetails=$balance->getLeaveBalance($empId,$types[$i]["id"]);
        	$res= (array)$bDetails;
		$leaveBalance[]=array($types[$i]["name"]=>$res);
	}
	

        

        if(!isset($leaveBalance))
        {
            $message=array('status'=>'error','code'=>'no_data','data'=>'No data received');
            return $message;
            exit();
        }
        else {
  		      
          $message=array('status'=>'success','code'=>200,'data'=>$leaveBalance);
          return $message;
          exit();
        }
      }
    }
?>
