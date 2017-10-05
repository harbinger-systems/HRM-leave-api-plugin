<?php

/*getLeaveList will return the list of all the leaves applied for by an employee,
the applied date and the status i.e whether the leave request has been approved or not
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

    class EmployeeLeaveRequestHelper
    {
      public function getLeaveList($id)
      {

        $leaveList='';
        $data='';
        $status;
        $leaveTypeObj=new LeaveTypeService();
        $leaveType=$leaveTypeObj->readLeaveTypeByName($leaveType);
        $leaveTypeId=$leaveType["id"];

        $obj1=new ParameterObject();
        $obj1->setParameter("empNumber",$id);
        $request=new LeaveRequestService();
        $array=$request->searchLeaveRequests($obj1,'webservice','true');
        //return $array;
	
	if(!isset($array))
	{
		$message=array('status'=>'error','code'=>'no_data','data'=>'No data received');
		return $message;
		exit();
	}
        else if(isset($array))
        {
        for($i=0;$i<sizeof($array["list"]);$i++)
        {

          if($array["list"][$i]["emp_number"]==$id)
          {
            $status=$array["list"][$i]["Leave"][0]["status"];
            if($status=="1")
            {
            	$data[]=array('emp_id'=>$id,'Applied date'=>$array["list"][$i]["date_applied"],'Leave type'=>$array["list"][$i]["LeaveType"]["name"],'status'=>'pending');
            }
            if($status=="2")
            {
            	$data[]=array('emp_id'=>$id,'Applied date'=>$array["list"][$i]["date_applied"],'Leave type'=>$array["list"][$i]["LeaveType"]["name"],'status'=>'approved');
            }
            if($status=="-1")
            {
            	$data[]=array('emp_id'=>$id,'Applied date'=>$array["list"][$i]["date_applied"],'Leave type'=>$array["list"][$i]["LeaveType"]["name"],'status'=>'rejected');
            }
            if($status=="0")
            {
            	$data[]=array('emp_id'=>$id,'Applied date'=>$array["list"][$i]["date_applied"],'Leave type'=>$array["list"][$i]["LeaveType"]["name"],'status'=>'cancelled');
            }
            if($status=="3")
            {
            	$data[]=array('emp_id'=>$id,'Applied date'=>$array["list"][$i]["date_applied"],'Leave type'=>$array["list"][$i]["LeaveType"]["name"],'status'=>'taken');
            }
          }
          }
          if(empty($data))
          {
          	$message=array('status'=>'error','code'=>'no_records','data'=>'No records for entered id.');
		return $message;
		exit();
	  }
          else{
          $leaveList=array('status'=>'success','data'=>$data);
          return $leaveList;}
      }
    }
    }
?>
