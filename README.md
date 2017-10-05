# INSTRUCTIONS TO WRITE AND CONFIGURE CUSTOM APIs
## PART-I

1. Custom code will be in two parts: wrapper and helper. Helper files will contain the custom implementation and the Wrapper file will call the method inside the Helper file. An important note: implementation should be inside a function and the function name should be same in the Wrapper and the Helper file. This function name is the name of your api. For example if there is a method called getDetails($param1,$param2) in 'getEmployeeDataHelper.php' then the prototype will be the same inside getEmployeeDataWrapper.php'. Will attach example files to make things clear.

2. All wrapper and helper files should be placed inside /symfony/plugins/orangehrmAdminPlugin/lib/wrapper/

3. Add your method name(api name) inside /symfony/plugins/orangehrmAdminPlugin/config/ws_config.yml . The file already has some examples in case it is not clear how to add your method name. Please make sure the indentation is exactly the way as it shown. If not your APIs will not work.

4. Add the complete path of the custom Helper and Wrapper files in /symfony/cache/orangehrm/prod/config/config_autoload.yml.php under the "Orangehrm Admin plugin section"



## PART-II

### Please make the following changes in order to get the APIs working correctly:

1. Make the following changes to /symfony/plugins/orangehrmLeavePlugin/lib/entity/LeaveParameterObject.php:

	a. add a field called source to identify whether the API is called via an http request.
	
	b. add a method that returns the value contained in the source field.(getWebService())
	
	c. call this method in the saveLeaveRequest() method inside /symfony/plugins/orangehrmLeavePlugin/lib/service/LeaveApplicationService.php . If the source is a 
	webservice then call $leaveAssignmentData->getEmployeeNumber() else call $leaveAssignmentData->getEmpNumber(). 
	
2. Make the following changes to the specified files:

    a. Add the source parameter to the method searchLeaveRequests() inside /symfony/plugins/orangehrmeLeavePlugin/lib/service/LeaveRequestService.php . 
    
    b. Modify the method searchLeaveRequests() inside /symfony/plugins/orangehrmLeavePlugin/lib/dao/LeaveRequestDao.php to accept the source parameter. 
    
    At the end of the function check if the source is a web-service. If it is call the method fetchArray() else call method execute().

