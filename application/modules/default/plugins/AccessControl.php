<?php
class Default_Plugin_AccessControl extends Zend_Controller_Plugin_Abstract
{
  private $_acl,$id_param;
          
  public function preDispatch(Zend_Controller_Request_Abstract $request)
  {
	$storage = new Zend_Auth_Storage_Session();
	$data = $storage->read();
	$role = $data['emprole'];
	if($role == 1)
		$role = 'admin';
	else if($role == 2)
	 $role = 'MGMT';
	else if($role == 3)
	 $role = 'MNGR';
	else if($role == 5)
	 $role = 'EMPL';
	else if($role == 10)
	 $role = 'HOD';
	else if($role == 12)
	 $role = 'HRE';
	else if($role == 14)
	 $role = 'SYSAD';
	
  	$request->getModuleName();
        $request->getControllerName();
        $request->getActionName();
    	
        
        $module = $request->getModuleName();
	$resource = $request->getControllerName();
	$privilege = $request->getActionName();
	$this->id_param = $request->getParam('id');
	$allowed = false;
        $acl = $this->_getAcl();
	$moduleResource = "$module:$resource";
	
	if($resource == 'profile')
            $role = 'viewer';
		
	if($resource == 'services')
            $role = 'services';
		
	if($role != '') 
        {
            if ($acl->has($moduleResource)) 
            {						
                $allowed = $acl->isAllowed($role, $moduleResource, $privilege);	
			    	 
            }	 
            if (!$allowed)//  && $role !='admin') 
            {				
                $request->setControllerName('error');
	        $request->setActionName('error');
            }
	}
  }
  
protected function _getAcl()
{
    if ($this->_acl == null ) 
    {
	   $acl = new Zend_Acl();

	   $acl->addRole('admin');            
	   $acl->addRole('viewer');            
	   
	 $acl->addRole('MGMT');
	 $acl->addRole('MNGR');
	 $acl->addRole('EMPL');
	 $acl->addRole('HOD');
	 $acl->addRole('HRE');
	 $acl->addRole('SYSAD');
	   $storage = new Zend_Auth_Storage_Session();
	   $data = $storage->read();
	   $role = $data['emprole'];
		
	$auth = Zend_Auth::getInstance(); 
	$tmroleText=array();
	$tmroleText = array('1'=>'admin','2'=>'MGMT','3'=>'MNGR','5'=>'EMPL','10'=>'HOD','12'=>'HRE','14'=>'SYSAD');
	
		if($auth->hasIdentity())
		{
			$tm_role = Zend_Registry::get('tm_role');
			$timeManagementRole = new Zend_Session_Namespace('tm_role');
			if(empty($timeManagementRole->tmrole))
			{
				$tm_role = $timeManagementRole->tmrole;
			}				
		}
			if(!empty($tm_role) && $tm_role == 'Admin') { 
	if(!isset($role))
								$tmroleText[$role] = 'admin';
		 $acl->addResource(new Zend_Acl_Resource('timemanagement:index'));
									$acl->allow($tmroleText[$role], 'timemanagement:index', array('index','week','edit','view','getstates','converdate'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:reports'));
									$acl->allow($tmroleText[$role], 'timemanagement:reports', array('index','employeereports','projectsreports','getempduration','getprojecttaskduration','tmreport'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:clients'));
									$acl->allow($tmroleText[$role], 'timemanagement:clients', array('index','edit','view','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:configuration'));
									$acl->allow($tmroleText[$role], 'timemanagement:configuration', array('index','add'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:currency'));
									$acl->allow($tmroleText[$role], 'timemanagement:currency', array('index'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:defaulttasks'));
									$acl->allow($tmroleText[$role], 'timemanagement:defaulttasks', array('index','edit','view','delete','checkduptask'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:emptimesheets'));
									$acl->allow($tmroleText[$role], 'timemanagement:emptimesheets', array('index','displayweeks','getmonthlyspan','accordion','employeetimesheet','empdisplayweeks','emptimesheetmonthly','emptimesheetweekly','enabletimesheet','approvetimesheet','rejecttimesheet','approvedaytimesheet','rejectdaytimesheet','getweekstartenddates'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:expenses'));
									$acl->allow($tmroleText[$role], 'timemanagement:expenses', array('index','edit','view','delete','download','uploadpreview','getprojectbyclientid','getfilename','submitexpense','expensereports','viewexpenses','viewexpensereports','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:expensecategory'));
									$acl->allow($tmroleText[$role], 'timemanagement:expensecategory', array('index','edit','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projectresources'));
									$acl->allow($tmroleText[$role], 'timemanagement:projectresources', array('index','resources','view','addresourcesproject','viewemptasks','addresources','deleteprojectresource','assigntasktoresources','taskassign','resourcetaskdelete','resourcetaskassigndelete'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projects'));
									$acl->allow($tmroleText[$role], 'timemanagement:projects', array('index','edit','view','add','tasks','addtasksproject','addtasks','delete','checkempforprojects'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projecttasks'));
									$acl->allow($tmroleText[$role], 'timemanagement:projecttasks', array('index','viewtasksresources','deletetask','assignresourcestotask','saveresources','edittaskname'));
 } elseif(!empty($tm_role) && $tm_role == 'Manager') { 
		 $acl->addResource(new Zend_Acl_Resource('timemanagement:index'));
									$acl->allow($tmroleText[$role], 'timemanagement:index', array('index','week','save','submit','eraseweek','getstates','getapprovedtimesheet','closeapprovealert','converdate'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:notifications'));
									$acl->allow($tmroleText[$role], 'timemanagement:notifications', array('pendingsubmissions','pendingsubmissionsweeklyview','weeklymonthlyview'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:clients'));
									$acl->allow($tmroleText[$role], 'timemanagement:clients', array('index','edit','view','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:defaulttasks'));
									$acl->allow($tmroleText[$role], 'timemanagement:defaulttasks', array('index','edit','view','delete','checkduptask'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projects'));
									$acl->allow($tmroleText[$role], 'timemanagement:projects', array('index','edit','view','add','tasks','addtasksproject','addtasks','delete','checkempforprojects'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projectresources'));
									$acl->allow($tmroleText[$role], 'timemanagement:projectresources', array('index','resources','view','addresourcesproject','viewemptasks','addresources','deleteprojectresource','assigntasktoresources','taskassign','resourcetaskdelete','resourcetaskassigndelete'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projecttasks'));
									$acl->allow($tmroleText[$role], 'timemanagement:projecttasks', array('index','viewtasksresources','deletetask','assignresourcestotask','saveresources','edittaskname'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:reports'));
									$acl->allow($tmroleText[$role], 'timemanagement:reports', array('index','employeereports','projectsreports','getempduration','getprojecttaskduration','tmreport'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:emptimesheets'));
									$acl->allow($tmroleText[$role], 'timemanagement:emptimesheets', array('index','displayweeks','getmonthlyspan','accordion','employeetimesheet','empdisplayweeks','emptimesheetmonthly','emptimesheetweekly','enabletimesheet','approvetimesheet','rejecttimesheet','approvedaytimesheet','rejectdaytimesheet','getweekstartenddates'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:expenses'));
									$acl->allow($tmroleText[$role], 'timemanagement:expenses', array('index','edit','view','delete','download','uploadpreview','getprojectbyclientid','getfilename','submitexpense','expensereports','viewexpenses','viewexpensereports','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus'));
 } elseif(!empty($tm_role) && $tm_role == 'Employee') { 
		 $acl->addResource(new Zend_Acl_Resource('timemanagement:index'));
									$acl->allow($tmroleText[$role], 'timemanagement:index', array('index','week','save','submit','eraseweek','getstates','getapprovedtimesheet','closeapprovealert','converdate'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:employeeprojects'));
									$acl->allow($tmroleText[$role], 'timemanagement:employeeprojects', array('index','view','emptasksgrid'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:notifications'));
									$acl->allow($tmroleText[$role], 'timemanagement:notifications', array('getnotifications','index'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:expenses'));
									$acl->allow($tmroleText[$role], 'timemanagement:expenses', array('index','edit','view','delete','download','uploadpreview','getprojectbyclientid','getfilename','submitexpense','expensereports','viewexpenses','viewexpensereports','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:reports'));
									$acl->allow($tmroleText[$role], 'timemanagement:reports', array('index','employeereports','projectsreports','getempduration','getprojecttaskduration','tmreport'));
 } 
		
	   $acl->addResource(new Zend_Acl_Resource('login:index'));	
	   $acl->allow('viewer', 'login:index', array('index','confirmlink','forgotpassword','forgotsuccess','login','pass','browserfailure','forcelogout','useractivation'));

	   if($role == 1 ) 
	   {				 		    	
			   
		 $acl->addResource(new Zend_Acl_Resource('default:accountclasstype'));
                    $acl->allow('admin', 'default:accountclasstype', array('index','view','edit','addpopup','saveupdate','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:addemployeeleaves'));
                    $acl->allow('admin', 'default:addemployeeleaves', array('index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                    $acl->allow('admin', 'default:announcements', array('index','add','view','edit','getdepts','delete','uploadsave','uploaddelete'));

		 $acl->addResource(new Zend_Acl_Resource('default:attendancestatuscode'));
                    $acl->allow('admin', 'default:attendancestatuscode', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:bankaccounttype'));
                    $acl->allow('admin', 'default:bankaccounttype', array('index','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                    $acl->allow('admin', 'default:businessunits', array('index','edit','view','delete','getdeptnames'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                    $acl->allow('admin', 'default:categories', array('index','add','edit','view','delete','addnewcategory'));

		 $acl->addResource(new Zend_Acl_Resource('default:cities'));
                    $acl->allow('admin', 'default:cities', array('index','view','edit','delete','getcitiescand','addpopup','addnewcity'));

		 $acl->addResource(new Zend_Acl_Resource('default:clients'));
                    $acl->allow('admin', 'default:clients', array('index','edit','view','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:competencylevel'));
                    $acl->allow('admin', 'default:competencylevel', array('index','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:countries'));
                    $acl->allow('admin', 'default:countries', array('index','view','edit','saveupdate','delete','getcountrycode','addpopup','addnewcountry'));

		 $acl->addResource(new Zend_Acl_Resource('default:currency'));
                    $acl->allow('admin', 'default:currency', array('index','view','edit','addpopup','delete','gettargetcurrency'));

		 $acl->addResource(new Zend_Acl_Resource('default:currencyconverter'));
                    $acl->allow('admin', 'default:currencyconverter', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                    $acl->allow('admin', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork','employeeimageupdate'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                    $acl->allow('admin', 'default:departments', array('index','view','viewpopup','edit','editpopup','getdepartments','delete','getempnames'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryallincidents'));
                    $acl->allow('admin', 'default:disciplinaryallincidents', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryincident'));
                    $acl->allow('admin', 'default:disciplinaryincident', array('index','view','edit','add','getemployees','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinarymyincidents'));
                    $acl->allow('admin', 'default:disciplinarymyincidents', array('index','view','edit','saveemployeeappeal','getdisciplinaryincidentpdf'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryteamincidents'));
                    $acl->allow('admin', 'default:disciplinaryteamincidents', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryviolation'));
                    $acl->allow('admin', 'default:disciplinaryviolation', array('index','add','view','edit','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationlevelcode'));
                    $acl->allow('admin', 'default:educationlevelcode', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:eeoccategory'));
                    $acl->allow('admin', 'default:eeoccategory', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:emailcontacts'));
                    $acl->allow('admin', 'default:emailcontacts', array('index','add','edit','getgroupoptions','view','delete','getmailcnt'));

		 $acl->addResource(new Zend_Acl_Resource('default:empconfiguration'));
                    $acl->allow('admin', 'default:empconfiguration', array('index','edit','add'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleavesummary'));
                    $acl->allow('admin', 'default:empleavesummary', array('index','statusid','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                    $acl->allow('admin', 'default:employee', array('getemprequi','index','getmoreemployees','changeorghead','add','edit','view','getdepartments','getpositions','delete','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeleavetypes'));
                    $acl->allow('admin', 'default:employeeleavetypes', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:employmentstatus'));
                    $acl->allow('admin', 'default:employmentstatus', array('index','view','edit','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:ethniccode'));
                    $acl->allow('admin', 'default:ethniccode', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:gender'));
                    $acl->allow('admin', 'default:gender', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:geographygroup'));
                    $acl->allow('admin', 'default:geographygroup', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                    $acl->allow('admin', 'default:heirarchy', array('index','edit','addlist','editlist','saveadddata','saveeditdata','deletelist'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaydates'));
                    $acl->allow('admin', 'default:holidaydates', array('index','add','addpopup','view','viewpopup','edit','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaygroups'));
                    $acl->allow('admin', 'default:holidaygroups', array('index','add','view','edit','delete','getempnames','getholidaynames','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitycodes'));
                    $acl->allow('admin', 'default:identitycodes', array('index','add','addpopup','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitydocuments'));
                    $acl->allow('admin', 'default:identitydocuments', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                    $acl->allow('admin', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps'));

		 $acl->addResource(new Zend_Acl_Resource('default:jobtitles'));
                    $acl->allow('admin', 'default:jobtitles', array('index','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:language'));
                    $acl->allow('admin', 'default:language', array('index','view','edit','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:leavemanagement'));
                    $acl->allow('admin', 'default:leavemanagement', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                    $acl->allow('admin', 'default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails'));

		 $acl->addResource(new Zend_Acl_Resource('default:licensetype'));
                    $acl->allow('admin', 'default:licensetype', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:managemenus'));
                    $acl->allow('admin', 'default:managemenus', array('index','save'));

		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                    $acl->allow('admin', 'default:manageremployeevacations', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:maritalstatus'));
                    $acl->allow('admin', 'default:maritalstatus', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:militaryservice'));
                    $acl->allow('admin', 'default:militaryservice', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                    $acl->allow('admin', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','delete','documents','assetdetailsview'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                    $acl->allow('admin', 'default:myemployees', array('index','view','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','add','edit','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                    $acl->allow('admin', 'default:myholidaycalendar', array('index','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationality'));
                    $acl->allow('admin', 'default:nationality', array('index','view','edit','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationalitycontextcode'));
                    $acl->allow('admin', 'default:nationalitycontextcode', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:numberformats'));
                    $acl->allow('admin', 'default:numberformats', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                    $acl->allow('admin', 'default:organisationinfo', array('index','edit','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead'));

		 $acl->addResource(new Zend_Acl_Resource('default:payfrequency'));
                    $acl->allow('admin', 'default:payfrequency', array('index','addpopup','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                    $acl->allow('admin', 'default:pendingleaves', array('index','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                    $acl->allow('admin', 'default:policydocuments', array('index','add','edit','view','delete','uploaddoc','deletedocument','addmultiple','uploadmultipledocs'));

		 $acl->addResource(new Zend_Acl_Resource('default:positions'));
                    $acl->allow('admin', 'default:positions', array('index','add','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:prefix'));
                    $acl->allow('admin', 'default:prefix', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:projects'));
                    $acl->allow('admin', 'default:projects', array('index','view','delete','viewpopup','editpopup','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:racecode'));
                    $acl->allow('admin', 'default:racecode', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:remunerationbasis'));
                    $acl->allow('admin', 'default:remunerationbasis', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:reports'));
                    $acl->allow('admin', 'default:reports', array('getrolepopup','emprolesgrouppopup','performancereport','previousappraisals','getselectedappraisaldata','getinterviewroundsdata','interviewrounds','rolesgroup','exportemprolesgroup','exportrolesgroupreport','exportinterviewrpt','exportactiveuserrpt','exportemployeereport','rolesgrouprptpdf','activeuserrptpdf','emprptpdf','interviewrptpdf','rolesgroupdata','emprolesgroup','emprolesgroupdata','activeuser','getactiveuserdata','getempreportdata','empauto','servicedeskreport','getsddata','servicedeskpdf','servicedeskexcel','employeereport','getdeptsemp','index','holidaygroupreports','getpdfreportholiday','getexcelreportholiday','leavesreport','getpdfreportleaves','getexcelreportleaves','leavesreporttabheader','leavemanagementreport','getpdfreportleavemanagement','getexcelreportleavemanagement','bunitauto','bunitcodeauto','getexcelreportbusinessunit','getbusinessunitspdf','businessunits','userlogreport','departments','exportdepartmentpdf','getexcelreportdepartment','candidaterptexcel','candidaterptpdf','getcandidatesreportdata','candidatesreport','requisitionauto','requisitionrptexcel','requisitionrptpdf','getrequisitionsstatusreportdata','requisitionstatusreport','activitylogreport','downloadreport','agencylistreport','agencynameauto','agencysebsiteauto','empscreening','getspecimennames','getagencynames','getexcelreportempscreening','getempscreeningpdf'));

		 $acl->addResource(new Zend_Acl_Resource('default:roles'));
                    $acl->allow('admin', 'default:roles', array('index','view','edit','saveupdate','delete','getgroupmenu'));

		 $acl->addResource(new Zend_Acl_Resource('default:sitepreference'));
                    $acl->allow('admin', 'default:sitepreference', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:states'));
                    $acl->allow('admin', 'default:states', array('index','view','edit','delete','getstates','getstatescand','addpopup','addnewstate'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                    $acl->allow('admin', 'default:structure', array('index'));

		 $acl->addResource(new Zend_Acl_Resource('default:timezone'));
                    $acl->allow('admin', 'default:timezone', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:usermanagement'));
                    $acl->allow('admin', 'default:usermanagement', array('index','view','edit','saveupdate','delete','getemailofuser'));

		 $acl->addResource(new Zend_Acl_Resource('default:vendors'));
                    $acl->allow('admin', 'default:vendors', array('index','view','delete','edit','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:veteranstatus'));
                    $acl->allow('admin', 'default:veteranstatus', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:wizard'));
                    $acl->allow('admin', 'default:wizard', array('index','managemenu','savemenu','configuresite','configureorganisation','updatewizardcompletion','configureunitsanddepartments','savebusinessunit','configureservicerequest','savecategory'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydoctypes'));
                    $acl->allow('admin', 'default:workeligibilitydoctypes', array('index','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assetcategories'));
                    $acl->allow('admin', 'assets:assetcategories', array('index','edit','view','delete','addpopup','addsubcatpopup','assetuserlog'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assets'));
                    $acl->allow('admin', 'assets:assets', array('index','edit','delete','uploadsave','uploaddelete','view','getsubcategories','deleteimage','downloadimage','getemployeesdata'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                    $acl->allow('admin', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                    $acl->allow('admin', 'expenses:employeeadvances', array('index','edit','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expensecategories'));
                    $acl->allow('admin', 'expenses:expensecategories', array('index','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                    $acl->allow('admin', 'expenses:expenses', array('index','edit','clone','view','delete','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                    $acl->allow('admin', 'expenses:myemployeeexpenses', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:paymentmode'));
                    $acl->allow('admin', 'expenses:paymentmode', array('index','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                    $acl->allow('admin', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                    $acl->allow('admin', 'expenses:trips', array('index','edit','view','delete','addpopup','tripstatus','deleteexpense','downloadtrippdf'));

		 $acl->addResource(new Zend_Acl_Resource('exit:allexitproc'));
                    $acl->allow('admin', 'exit:allexitproc', array('index','edit','view','editpopup','updateexitprocess','assignquestions'));

		 $acl->addResource(new Zend_Acl_Resource('exit:configureexitqs'));
                    $acl->allow('admin', 'exit:configureexitqs', array('index','add','edit','view','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitproc'));
                    $acl->allow('admin', 'exit:exitproc', array('index','questions','view','add','savequestions'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitprocsettings'));
                    $acl->allow('admin', 'exit:exitprocsettings', array('index','view','add','edit','delete','getdepartments'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exittypes'));
                    $acl->allow('admin', 'exit:exittypes', array('index','add','edit','view','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                    $acl->allow('admin', 'default:processes', array('index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                    $acl->allow('admin', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                    $acl->allow('admin', 'default:empperformanceappraisal', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                    $acl->allow('admin', 'default:emppayslips', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                    $acl->allow('admin', 'default:empbenefits', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                    $acl->allow('admin', 'default:emprequisitiondetails', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                    $acl->allow('admin', 'default:empremunerationdetails', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                    $acl->allow('admin', 'default:empsecuritycredentials', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                    $acl->allow('admin', 'default:apprreqcandidates', array('index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                    $acl->allow('admin', 'default:emppersonaldetails', array('index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                    $acl->allow('admin', 'default:employeedocs', array('index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                    $acl->allow('admin', 'default:empcommunicationdetails', array('index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                    $acl->allow('admin', 'default:trainingandcertificationdetails', array('index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                    $acl->allow('admin', 'default:experiencedetails', array('index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                    $acl->allow('admin', 'default:educationdetails', array('index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                    $acl->allow('admin', 'default:medicalclaims', array('index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                    $acl->allow('admin', 'default:empleaves', array('index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                    $acl->allow('admin', 'default:empskills', array('index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                    $acl->allow('admin', 'default:disabilitydetails', array('index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                    $acl->allow('admin', 'default:workeligibilitydetails', array('index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                    $acl->allow('admin', 'default:empadditionaldetails', array('index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                    $acl->allow('admin', 'default:visaandimmigrationdetails', array('index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                    $acl->allow('admin', 'default:creditcarddetails', array('index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                    $acl->allow('admin', 'default:dependencydetails', array('index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                    $acl->allow('admin', 'default:empholidays', array('index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                    $acl->allow('admin', 'default:empjobhistory', array('index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                    $acl->allow('admin', 'default:assetdetails', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                    $acl->allow('admin', 'default:empsalarydetails', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:logmanager'));
                    $acl->allow('admin', 'default:logmanager', array('index','view','empnamewithidauto'));

		 $acl->addResource(new Zend_Acl_Resource('default:userloginlog'));
                    $acl->allow('admin', 'default:userloginlog', array('index','empnameauto','empidauto','empipaddressauto','empemailauto'));
			   		  	   				   
	   }  
	   if($role == 2 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:accountclasstype'));
                            $acl->allow('MGMT', 'default:accountclasstype', array('index','addpopup','saveupdate','add','edit','delete','view','Account Class Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:addemployeeleaves'));
                            $acl->allow('MGMT', 'default:addemployeeleaves', array('index','add','edit','view','Add Employee Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('MGMT', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','add','edit','delete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:attendancestatuscode'));
                            $acl->allow('MGMT', 'default:attendancestatuscode', array('index','add','edit','delete','view','Attendance Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:bankaccounttype'));
                            $acl->allow('MGMT', 'default:bankaccounttype', array('index','addpopup','add','edit','delete','view','Bank Account Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('MGMT', 'default:businessunits', array('index','getdeptnames','add','edit','delete','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                            $acl->allow('MGMT', 'default:categories', array('index','addnewcategory','add','edit','delete','view','Manage Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:cities'));
                            $cities_add = 'yes';
                                if($this->id_param == '' && $cities_add == 'yes')
                                    $acl->allow('MGMT','default:cities', array('index','getcitiescand','addpopup','addnewcity','add','delete','view','Cities','edit'));

                                else
                                    $acl->allow('MGMT','default:cities', array('index','getcitiescand','addpopup','addnewcity','add','delete','view','Cities'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:clients'));
                            $acl->allow('MGMT', 'default:clients', array('index','addpopup','add','edit','delete','view','Clients'));

		 $acl->addResource(new Zend_Acl_Resource('default:competencylevel'));
                            $acl->allow('MGMT', 'default:competencylevel', array('index','addpopup','add','edit','delete','view','Competency Levels'));

		 $acl->addResource(new Zend_Acl_Resource('default:countries'));
                            $countries_add = 'yes';
                                if($this->id_param == '' && $countries_add == 'yes')
                                    $acl->allow('MGMT','default:countries', array('index','saveupdate','getcountrycode','addpopup','addnewcountry','add','delete','view','Countries','edit'));

                                else
                                    $acl->allow('MGMT','default:countries', array('index','saveupdate','getcountrycode','addpopup','addnewcountry','add','delete','view','Countries'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:currency'));
                            $acl->allow('MGMT', 'default:currency', array('index','addpopup','gettargetcurrency','add','edit','delete','view','Currencies'));

		 $acl->addResource(new Zend_Acl_Resource('default:currencyconverter'));
                            $acl->allow('MGMT', 'default:currencyconverter', array('index','add','edit','delete','view','Currency Conversions'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('MGMT', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork','employeeimageupdate'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('MGMT', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','add','edit','delete','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryallincidents'));
                            $acl->allow('MGMT', 'default:disciplinaryallincidents', array('index','view','All Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryincident'));
                            $acl->allow('MGMT', 'default:disciplinaryincident', array('index','getemployees','add','edit','delete','view','Raise An Incident'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinarymyincidents'));
                            $acl->allow('MGMT', 'default:disciplinarymyincidents', array('index','saveemployeeappeal','getdisciplinaryincidentpdf','edit','view','My Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryteamincidents'));
                            $acl->allow('MGMT', 'default:disciplinaryteamincidents', array('index','view','Team Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryviolation'));
                            $acl->allow('MGMT', 'default:disciplinaryviolation', array('index','addpopup','add','edit','delete','view','Violation Type'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationlevelcode'));
                            $acl->allow('MGMT', 'default:educationlevelcode', array('index','add','edit','delete','view','Education Levels'));

		 $acl->addResource(new Zend_Acl_Resource('default:eeoccategory'));
                            $acl->allow('MGMT', 'default:eeoccategory', array('index','add','edit','delete','view','EEOC Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:emailcontacts'));
                            $acl->allow('MGMT', 'default:emailcontacts', array('index','getgroupoptions','getmailcnt','add','edit','delete','view','Email Contacts'));

		 $acl->addResource(new Zend_Acl_Resource('default:empconfiguration'));
                            $acl->allow('MGMT', 'default:empconfiguration', array('index','edit','Employee Tabs'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleavesummary'));
                            $acl->allow('MGMT', 'default:empleavesummary', array('index','statusid','view','Employee Leave Summary'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('MGMT', 'default:employee', array('getemprequi','index','getmoreemployees','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','add','edit','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeleavetypes'));
                            $acl->allow('MGMT', 'default:employeeleavetypes', array('index','add','edit','delete','view','Leave Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:employmentstatus'));
                            $acl->allow('MGMT', 'default:employmentstatus', array('index','addpopup','add','edit','delete','view','Employment Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:ethniccode'));
                            $acl->allow('MGMT', 'default:ethniccode', array('index','saveupdate','addpopup','add','edit','delete','view','Ethnic Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:gender'));
                            $acl->allow('MGMT', 'default:gender', array('index','saveupdate','addpopup','add','edit','delete','view','Gender'));

		 $acl->addResource(new Zend_Acl_Resource('default:geographygroup'));
                            $acl->allow('MGMT', 'default:geographygroup', array('index','add','edit','delete','view','Geo Groups'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('MGMT', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaydates'));
                            $acl->allow('MGMT', 'default:holidaydates', array('index','addpopup','viewpopup','editpopup','add','edit','delete','view','Manage Holidays'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaygroups'));
                            $acl->allow('MGMT', 'default:holidaygroups', array('index','getempnames','getholidaynames','addpopup','add','edit','delete','view','Manage Holiday Group'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitycodes'));
                            $acl->allow('MGMT', 'default:identitycodes', array('index','addpopup','edit','Identity Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitydocuments'));
                            $acl->allow('MGMT', 'default:identitydocuments', array('index','add','edit','delete','view','Identity Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('MGMT', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps'));

		 $acl->addResource(new Zend_Acl_Resource('default:jobtitles'));
                            $acl->allow('MGMT', 'default:jobtitles', array('index','addpopup','add','edit','delete','view','Job Titles'));

		 $acl->addResource(new Zend_Acl_Resource('default:language'));
                            $acl->allow('MGMT', 'default:language', array('index','addpopup','add','edit','delete','view','Languages'));

		 $acl->addResource(new Zend_Acl_Resource('default:leavemanagement'));
                            $acl->allow('MGMT', 'default:leavemanagement', array('index','add','edit','delete','view','Leave Management Options'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('MGMT','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('MGMT','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:licensetype'));
                            $acl->allow('MGMT', 'default:licensetype', array('index','add','edit','delete','view','License Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('MGMT', 'default:manageremployeevacations', array('index','edit','view','Employee Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:maritalstatus'));
                            $acl->allow('MGMT', 'default:maritalstatus', array('index','saveupdate','addpopup','add','edit','delete','view','Marital Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:militaryservice'));
                            $acl->allow('MGMT', 'default:militaryservice', array('index','add','edit','delete','view','Military Service Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('MGMT', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('MGMT', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('MGMT', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationality'));
                            $acl->allow('MGMT', 'default:nationality', array('index','addpopup','add','edit','delete','view','Nationalities'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationalitycontextcode'));
                            $acl->allow('MGMT', 'default:nationalitycontextcode', array('index','add','edit','delete','view','Nationality Context Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:numberformats'));
                            $acl->allow('MGMT', 'default:numberformats', array('index','add','edit','delete','view','Number Formats'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('MGMT', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','edit','view','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:payfrequency'));
                            $acl->allow('MGMT', 'default:payfrequency', array('index','addpopup','add','edit','delete','view','Pay Frequency'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('MGMT', 'default:pendingleaves', array('index','delete','view','My Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('MGMT', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','add','edit','delete','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:positions'));
                            $acl->allow('MGMT', 'default:positions', array('index','addpopup','add','edit','delete','view','Positions'));

		 $acl->addResource(new Zend_Acl_Resource('default:prefix'));
                            $acl->allow('MGMT', 'default:prefix', array('index','saveupdate','addpopup','add','edit','delete','view','Prefixes'));

		 $acl->addResource(new Zend_Acl_Resource('default:projects'));
                            $acl->allow('MGMT', 'default:projects', array('index','viewpopup','editpopup','add','edit','delete','view','Projects'));

		 $acl->addResource(new Zend_Acl_Resource('default:racecode'));
                            $acl->allow('MGMT', 'default:racecode', array('index','saveupdate','addpopup','add','edit','delete','view','Race Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:remunerationbasis'));
                            $acl->allow('MGMT', 'default:remunerationbasis', array('index','add','edit','delete','view','Remuneration Basis'));

		 $acl->addResource(new Zend_Acl_Resource('default:reports'));
                            $acl->allow('MGMT', 'default:reports', array('getrolepopup','emprolesgrouppopup','performancereport','previousappraisals','getselectedappraisaldata','getinterviewroundsdata','interviewrounds','rolesgroup','exportemprolesgroup','exportrolesgroupreport','exportinterviewrpt','exportactiveuserrpt','exportemployeereport','rolesgrouprptpdf','activeuserrptpdf','emprptpdf','interviewrptpdf','rolesgroupdata','emprolesgroup','emprolesgroupdata','activeuser','getactiveuserdata','getempreportdata','empauto','servicedeskreport','getsddata','servicedeskpdf','servicedeskexcel','employeereport','getdeptsemp','index','holidaygroupreports','getpdfreportholiday','getexcelreportholiday','leavesreport','getpdfreportleaves','getexcelreportleaves','leavesreporttabheader','leavemanagementreport','getpdfreportleavemanagement','getexcelreportleavemanagement','bunitauto','bunitcodeauto','getexcelreportbusinessunit','getbusinessunitspdf','businessunits','userlogreport','departments','exportdepartmentpdf','getexcelreportdepartment','candidaterptexcel','candidaterptpdf','getcandidatesreportdata','candidatesreport','requisitionauto','requisitionrptexcel','requisitionrptpdf','getrequisitionsstatusreportdata','requisitionstatusreport','activitylogreport','downloadreport','agencylistreport','agencynameauto','agencysebsiteauto','empscreening','getspecimennames','getagencynames','getexcelreportempscreening','getempscreeningpdf','Analytics'));

		 $acl->addResource(new Zend_Acl_Resource('default:roles'));
                            $acl->allow('MGMT', 'default:roles', array('index','saveupdate','getgroupmenu','add','edit','delete','view','Roles & Privileges'));

		 $acl->addResource(new Zend_Acl_Resource('default:sitepreference'));
                            $acl->allow('MGMT', 'default:sitepreference', array('index','view','add','edit','Site Preferences'));

		 $acl->addResource(new Zend_Acl_Resource('default:states'));
                            $states_add = 'yes';
                                if($this->id_param == '' && $states_add == 'yes')
                                    $acl->allow('MGMT','default:states', array('index','getstates','getstatescand','addpopup','addnewstate','add','delete','view','States','edit'));

                                else
                                    $acl->allow('MGMT','default:states', array('index','getstates','getstatescand','addpopup','addnewstate','add','delete','view','States'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('MGMT', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('default:timezone'));
                            $acl->allow('MGMT', 'default:timezone', array('index','saveupdate','addpopup','add','edit','delete','view','Time Zones'));

		 $acl->addResource(new Zend_Acl_Resource('default:usermanagement'));
                            $acl->allow('MGMT', 'default:usermanagement', array('index','saveupdate','getemailofuser','add','edit','view','External Users'));

		 $acl->addResource(new Zend_Acl_Resource('default:vendors'));
                            $acl->allow('MGMT', 'default:vendors', array('index','addpopup','add','edit','delete','view','Vendors'));

		 $acl->addResource(new Zend_Acl_Resource('default:veteranstatus'));
                            $acl->allow('MGMT', 'default:veteranstatus', array('index','add','edit','delete','view','Veteran Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydoctypes'));
                            $acl->allow('MGMT', 'default:workeligibilitydoctypes', array('index','addpopup','add','edit','delete','view','Work Eligibility Document Types'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('MGMT', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('MGMT', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('MGMT', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('MGMT', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('MGMT', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('MGMT', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('exit:allexitproc'));
                            $acl->allow('MGMT', 'exit:allexitproc', array('index','editpopup','updateexitprocess','assignquestions','add','edit','view','All Exit Procedures'));

		 $acl->addResource(new Zend_Acl_Resource('exit:configureexitqs'));
                            $acl->allow('MGMT', 'exit:configureexitqs', array('index','addpopup','add','edit','delete','view','Exit Interview Questions'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitproc'));
                            $acl->allow('MGMT', 'exit:exitproc', array('index','questions','savequestions','add','edit','view','Initiate/Check Status'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitprocsettings'));
                            $acl->allow('MGMT', 'exit:exitprocsettings', array('index','getdepartments','add','edit','delete','view','Settings'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exittypes'));
                            $acl->allow('MGMT', 'exit:exittypes', array('index','addpopup','add','edit','delete','view','Exit Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('MGMT', 'default:processes', array('index','addpopup','editpopup','viewpopup','savecomments','displaycomments','savefeedback','index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('MGMT', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                            $acl->allow('MGMT', 'default:empperformanceappraisal', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                            $acl->allow('MGMT', 'default:emppayslips', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                            $acl->allow('MGMT', 'default:empbenefits', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                            $acl->allow('MGMT', 'default:emprequisitiondetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                            $acl->allow('MGMT', 'default:empremunerationdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                            $acl->allow('MGMT', 'default:empsecuritycredentials', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('MGMT', 'default:apprreqcandidates', array('index','viewpopup','index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('MGMT', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('MGMT', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('MGMT', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('MGMT', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('MGMT', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('MGMT', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('MGMT', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('MGMT', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('MGMT', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('MGMT', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('MGMT', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('MGMT', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('MGMT', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('MGMT', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('MGMT', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('MGMT', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('MGMT', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('MGMT', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                            $acl->allow('MGMT', 'default:empsalarydetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:logmanager'));
                            $acl->allow('MGMT', 'default:logmanager', array('index','view','empnamewithidauto','index','view','empnamewithidauto'));

		 $acl->addResource(new Zend_Acl_Resource('default:userloginlog'));
                            $acl->allow('MGMT', 'default:userloginlog', array('index','empnameauto','empidauto','empipaddressauto','empemailauto','index','empnameauto','empidauto','empipaddressauto','empemailauto'));
}if($role == 3 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('MNGR', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('MNGR', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:clients'));
                            $acl->allow('MNGR', 'default:clients', array('index','addpopup','add','edit','delete','view','Clients'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('MNGR', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork','employeeimageupdate'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('MNGR', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinarymyincidents'));
                            $acl->allow('MNGR', 'default:disciplinarymyincidents', array('index','saveemployeeappeal','getdisciplinaryincidentpdf','edit','view','My Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryteamincidents'));
                            $acl->allow('MNGR', 'default:disciplinaryteamincidents', array('index','view','Team Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('MNGR', 'default:employee', array('getemprequi','index','getmoreemployees','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('MNGR', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('MNGR', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('MNGR','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('MNGR','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('MNGR', 'default:manageremployeevacations', array('index','edit','view','Employee Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('MNGR', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('MNGR', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','add','edit','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('MNGR', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('MNGR', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('MNGR', 'default:pendingleaves', array('index','delete','view','My Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('MNGR', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:projects'));
                            $acl->allow('MNGR', 'default:projects', array('index','viewpopup','editpopup','add','edit','delete','view','Projects'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('MNGR', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('MNGR', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('MNGR', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('MNGR', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('MNGR', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('MNGR', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('MNGR', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('exit:allexitproc'));
                            $acl->allow('MNGR', 'exit:allexitproc', array('index','editpopup','updateexitprocess','assignquestions','add','edit','view','All Exit Procedures'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitproc'));
                            $acl->allow('MNGR', 'exit:exitproc', array('index','questions','savequestions','add','edit','view','Initiate/Check Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('MNGR', 'default:emppersonaldetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('MNGR', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('MNGR', 'default:empcommunicationdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('MNGR', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('MNGR', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('MNGR', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('MNGR', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('MNGR', 'default:empleaves', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('MNGR', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('MNGR', 'default:disabilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('MNGR', 'default:workeligibilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('MNGR', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('MNGR', 'default:creditcarddetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('MNGR', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('MNGR', 'default:empholidays', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('MNGR', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('MNGR', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('MNGR', 'default:assetdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('MNGR', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('MNGR', 'default:apprreqcandidates', array('index','viewpopup'));
}if($role == 5 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('EMPL', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('EMPL', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:clients'));
                            $acl->allow('EMPL', 'default:clients', array('index','addpopup','add','edit','delete','view','Clients'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('EMPL', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork','employeeimageupdate'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('EMPL', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinarymyincidents'));
                            $acl->allow('EMPL', 'default:disciplinarymyincidents', array('index','saveemployeeappeal','getdisciplinaryincidentpdf','edit','view','My Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryteamincidents'));
                            $acl->allow('EMPL', 'default:disciplinaryteamincidents', array('index','view','Team Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('EMPL', 'default:employee', array('getemprequi','index','getmoreemployees','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('EMPL', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('EMPL', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('EMPL','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('EMPL','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('EMPL', 'default:manageremployeevacations', array('index','edit','view','Employee Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('EMPL', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('EMPL', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('EMPL', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('EMPL', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('EMPL', 'default:pendingleaves', array('index','delete','view','My Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('EMPL', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:projects'));
                            $acl->allow('EMPL', 'default:projects', array('index','viewpopup','editpopup','add','edit','delete','view','Projects'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('EMPL', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('EMPL', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('EMPL', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('EMPL', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('EMPL', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('EMPL', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('EMPL', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('exit:allexitproc'));
                            $acl->allow('EMPL', 'exit:allexitproc', array('index','editpopup','updateexitprocess','assignquestions','add','edit','view','All Exit Procedures'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitproc'));
                            $acl->allow('EMPL', 'exit:exitproc', array('index','questions','savequestions','add','edit','view','Initiate/Check Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('EMPL', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('EMPL', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('EMPL', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('EMPL', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('EMPL', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('EMPL', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('EMPL', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('EMPL', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('EMPL', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('EMPL', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('EMPL', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('EMPL', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('EMPL', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('EMPL', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('EMPL', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('EMPL', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('EMPL', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('EMPL', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('EMPL', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('EMPL', 'default:apprreqcandidates', array('index','viewpopup'));
}if($role == 10 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('HOD', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('HOD', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:clients'));
                            $acl->allow('HOD', 'default:clients', array('index','addpopup','add','edit','delete','view','Clients'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('HOD', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork','employeeimageupdate'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('HOD', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinarymyincidents'));
                            $acl->allow('HOD', 'default:disciplinarymyincidents', array('index','saveemployeeappeal','getdisciplinaryincidentpdf','edit','view','My Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryteamincidents'));
                            $acl->allow('HOD', 'default:disciplinaryteamincidents', array('index','view','Team Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('HOD', 'default:employee', array('getemprequi','index','getmoreemployees','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('HOD', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('HOD', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('HOD','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('HOD','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('HOD', 'default:manageremployeevacations', array('index','edit','view','Employee Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('HOD', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('HOD', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','add','edit','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('HOD', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('HOD', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('HOD', 'default:pendingleaves', array('index','delete','view','My Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('HOD', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:projects'));
                            $acl->allow('HOD', 'default:projects', array('index','viewpopup','editpopup','add','edit','delete','view','Projects'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('HOD', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('HOD', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('HOD', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('HOD', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('HOD', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('HOD', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('HOD', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('exit:allexitproc'));
                            $acl->allow('HOD', 'exit:allexitproc', array('index','editpopup','updateexitprocess','assignquestions','add','edit','view','All Exit Procedures'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitproc'));
                            $acl->allow('HOD', 'exit:exitproc', array('index','questions','savequestions','add','edit','view','Initiate/Check Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('HOD', 'default:emppersonaldetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('HOD', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('HOD', 'default:empcommunicationdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('HOD', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('HOD', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('HOD', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('HOD', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('HOD', 'default:empleaves', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('HOD', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('HOD', 'default:disabilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('HOD', 'default:workeligibilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('HOD', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('HOD', 'default:creditcarddetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('HOD', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('HOD', 'default:empholidays', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('HOD', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('HOD', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('HOD', 'default:assetdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('HOD', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('HOD', 'default:apprreqcandidates', array('index','viewpopup'));
}if($role == 12 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:addemployeeleaves'));
                            $acl->allow('HRE', 'default:addemployeeleaves', array('index','add','edit','view','Add Employee Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('HRE', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','add','edit','delete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:attendancestatuscode'));
                            $acl->allow('HRE', 'default:attendancestatuscode', array('index','add','edit','view','Attendance Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:bankaccounttype'));
                            $acl->allow('HRE', 'default:bankaccounttype', array('index','addpopup','add','edit','view','Bank Account Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('HRE', 'default:businessunits', array('index','getdeptnames','add','edit','delete','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                            $acl->allow('HRE', 'default:categories', array('index','addnewcategory','add','edit','delete','view','Manage Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:clients'));
                            $acl->allow('HRE', 'default:clients', array('index','addpopup','add','edit','delete','view','Clients'));

		 $acl->addResource(new Zend_Acl_Resource('default:competencylevel'));
                            $acl->allow('HRE', 'default:competencylevel', array('index','addpopup','add','edit','view','Competency Levels'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('HRE', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork','employeeimageupdate'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('HRE', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','add','edit','delete','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryallincidents'));
                            $acl->allow('HRE', 'default:disciplinaryallincidents', array('index','view','All Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinarymyincidents'));
                            $acl->allow('HRE', 'default:disciplinarymyincidents', array('index','saveemployeeappeal','getdisciplinaryincidentpdf','edit','view','My Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryteamincidents'));
                            $acl->allow('HRE', 'default:disciplinaryteamincidents', array('index','view','Team Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationlevelcode'));
                            $acl->allow('HRE', 'default:educationlevelcode', array('index','add','edit','view','Education Levels'));

		 $acl->addResource(new Zend_Acl_Resource('default:eeoccategory'));
                            $acl->allow('HRE', 'default:eeoccategory', array('index','add','edit','view','EEOC Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:empconfiguration'));
                            $acl->allow('HRE', 'default:empconfiguration', array('index','edit','Employee Tabs'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleavesummary'));
                            $acl->allow('HRE', 'default:empleavesummary', array('index','statusid','view','Employee Leave Summary'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('HRE', 'default:employee', array('getemprequi','index','getmoreemployees','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','add','edit','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeleavetypes'));
                            $acl->allow('HRE', 'default:employeeleavetypes', array('index','add','edit','view','Leave Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:employmentstatus'));
                            $acl->allow('HRE', 'default:employmentstatus', array('index','addpopup','add','edit','view','Employment Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('HRE', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaydates'));
                            $acl->allow('HRE', 'default:holidaydates', array('index','addpopup','viewpopup','editpopup','add','edit','view','Manage Holidays'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaygroups'));
                            $acl->allow('HRE', 'default:holidaygroups', array('index','getempnames','getholidaynames','addpopup','add','edit','view','Manage Holiday Group'));

		 $acl->addResource(new Zend_Acl_Resource('default:hrwizard'));
                            $acl->allow('HRE', 'default:hrwizard', array('index','configureleavetypes','configureholidays','saveholidaygroup','configureperformanceappraisal','savecategory','updatewizardcompletion','index','configureleavetypes','configureholidays','saveholidaygroup','configureperformanceappraisal','savecategory','updatewizardcompletion'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitydocuments'));
                            $acl->allow('HRE', 'default:identitydocuments', array('index','add','edit','view','Identity Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('HRE', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps'));

		 $acl->addResource(new Zend_Acl_Resource('default:jobtitles'));
                            $acl->allow('HRE', 'default:jobtitles', array('index','addpopup','add','edit','view','Job Titles'));

		 $acl->addResource(new Zend_Acl_Resource('default:leavemanagement'));
                            $acl->allow('HRE', 'default:leavemanagement', array('index','add','edit','view','Leave Management Options'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('HRE','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('HRE','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('HRE', 'default:manageremployeevacations', array('index','edit','view','Employee Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('HRE', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('HRE', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('HRE', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('HRE', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','edit','view','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:payfrequency'));
                            $acl->allow('HRE', 'default:payfrequency', array('index','addpopup','add','edit','view','Pay Frequency'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('HRE', 'default:pendingleaves', array('index','delete','view','My Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('HRE', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','add','edit','delete','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:positions'));
                            $acl->allow('HRE', 'default:positions', array('index','addpopup','add','edit','view','Positions'));

		 $acl->addResource(new Zend_Acl_Resource('default:projects'));
                            $acl->allow('HRE', 'default:projects', array('index','viewpopup','editpopup','add','edit','delete','view','Projects'));

		 $acl->addResource(new Zend_Acl_Resource('default:remunerationbasis'));
                            $acl->allow('HRE', 'default:remunerationbasis', array('index','add','edit','view','Remuneration Basis'));

		 $acl->addResource(new Zend_Acl_Resource('default:reports'));
                            $acl->allow('HRE', 'default:reports', array('getrolepopup','emprolesgrouppopup','performancereport','previousappraisals','getselectedappraisaldata','getinterviewroundsdata','interviewrounds','rolesgroup','exportemprolesgroup','exportrolesgroupreport','exportinterviewrpt','exportactiveuserrpt','exportemployeereport','rolesgrouprptpdf','activeuserrptpdf','emprptpdf','interviewrptpdf','rolesgroupdata','emprolesgroup','emprolesgroupdata','activeuser','getactiveuserdata','getempreportdata','empauto','servicedeskreport','getsddata','servicedeskpdf','servicedeskexcel','employeereport','getdeptsemp','index','holidaygroupreports','getpdfreportholiday','getexcelreportholiday','leavesreport','getpdfreportleaves','getexcelreportleaves','leavesreporttabheader','leavemanagementreport','getpdfreportleavemanagement','getexcelreportleavemanagement','bunitauto','bunitcodeauto','getexcelreportbusinessunit','getbusinessunitspdf','businessunits','userlogreport','departments','exportdepartmentpdf','getexcelreportdepartment','candidaterptexcel','candidaterptpdf','getcandidatesreportdata','candidatesreport','requisitionauto','requisitionrptexcel','requisitionrptpdf','getrequisitionsstatusreportdata','requisitionstatusreport','activitylogreport','downloadreport','agencylistreport','agencynameauto','agencysebsiteauto','empscreening','getspecimennames','getagencynames','getexcelreportempscreening','getempscreeningpdf','Analytics'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('HRE', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('default:usermanagement'));
                            $acl->allow('HRE', 'default:usermanagement', array('index','saveupdate','getemailofuser','add','edit','view','External Users'));

		 $acl->addResource(new Zend_Acl_Resource('default:vendors'));
                            $acl->allow('HRE', 'default:vendors', array('index','addpopup','add','edit','delete','view','Vendors'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydoctypes'));
                            $acl->allow('HRE', 'default:workeligibilitydoctypes', array('index','addpopup','add','edit','view','Work Eligibility Document Types'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assetcategories'));
                            $acl->allow('HRE', 'assets:assetcategories', array('index','addpopup','addsubcatpopup','assetuserlog','add','edit','delete','view','Asset Categories'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assets'));
                            $acl->allow('HRE', 'assets:assets', array('index','uploadsave','uploaddelete','getsubcategories','deleteimage','downloadimage','getemployeesdata','add','edit','delete','view','Assets'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('HRE', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('HRE', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expensecategories'));
                            $acl->allow('HRE', 'expenses:expensecategories', array('index','add','edit','delete','view','Category'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('HRE', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('HRE', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:paymentmode'));
                            $acl->allow('HRE', 'expenses:paymentmode', array('index','add','edit','delete','view','Payment Mode'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('HRE', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('HRE', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('exit:allexitproc'));
                            $acl->allow('HRE', 'exit:allexitproc', array('index','editpopup','updateexitprocess','assignquestions','add','edit','view','All Exit Procedures'));

		 $acl->addResource(new Zend_Acl_Resource('exit:configureexitqs'));
                            $acl->allow('HRE', 'exit:configureexitqs', array('index','addpopup','add','edit','delete','view','Exit Interview Questions'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitproc'));
                            $acl->allow('HRE', 'exit:exitproc', array('index','questions','savequestions','add','edit','view','Initiate/Check Status'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitprocsettings'));
                            $acl->allow('HRE', 'exit:exitprocsettings', array('index','getdepartments','add','edit','delete','view','Settings'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exittypes'));
                            $acl->allow('HRE', 'exit:exittypes', array('index','addpopup','add','edit','delete','view','Exit Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('HRE', 'default:processes', array('index','addpopup','editpopup','viewpopup','savecomments','displaycomments','savefeedback','index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('HRE', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                            $acl->allow('HRE', 'default:empperformanceappraisal', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                            $acl->allow('HRE', 'default:emppayslips', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                            $acl->allow('HRE', 'default:empbenefits', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                            $acl->allow('HRE', 'default:emprequisitiondetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                            $acl->allow('HRE', 'default:empremunerationdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                            $acl->allow('HRE', 'default:empsecuritycredentials', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('HRE', 'default:apprreqcandidates', array('index','viewpopup','index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('HRE', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('HRE', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('HRE', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('HRE', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('HRE', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('HRE', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('HRE', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('HRE', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('HRE', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('HRE', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('HRE', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('HRE', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('HRE', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('HRE', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('HRE', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('HRE', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('HRE', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('HRE', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                            $acl->allow('HRE', 'default:empsalarydetails', array('index','view','index','edit','view'));
}if($role == 14 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:accountclasstype'));
                            $acl->allow('SYSAD', 'default:accountclasstype', array('index','addpopup','saveupdate','add','edit','delete','view','Account Class Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('SYSAD', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('SYSAD', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                            $acl->allow('SYSAD', 'default:categories', array('index','addnewcategory','add','edit','view','Manage Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:cities'));
                            $cities_add = 'yes';
                                if($this->id_param == '' && $cities_add == 'yes')
                                    $acl->allow('SYSAD','default:cities', array('index','getcitiescand','addpopup','addnewcity','add','delete','view','Cities','edit'));

                                else
                                    $acl->allow('SYSAD','default:cities', array('index','getcitiescand','addpopup','addnewcity','add','delete','view','Cities'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:clients'));
                            $acl->allow('SYSAD', 'default:clients', array('index','addpopup','add','edit','delete','view','Clients'));

		 $acl->addResource(new Zend_Acl_Resource('default:countries'));
                            $countries_add = 'yes';
                                if($this->id_param == '' && $countries_add == 'yes')
                                    $acl->allow('SYSAD','default:countries', array('index','saveupdate','getcountrycode','addpopup','addnewcountry','add','delete','view','Countries','edit'));

                                else
                                    $acl->allow('SYSAD','default:countries', array('index','saveupdate','getcountrycode','addpopup','addnewcountry','add','delete','view','Countries'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:currency'));
                            $acl->allow('SYSAD', 'default:currency', array('index','addpopup','gettargetcurrency','add','edit','delete','view','Currencies'));

		 $acl->addResource(new Zend_Acl_Resource('default:currencyconverter'));
                            $acl->allow('SYSAD', 'default:currencyconverter', array('index','add','edit','delete','view','Currency Conversions'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('SYSAD', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork','employeeimageupdate'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('SYSAD', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinarymyincidents'));
                            $acl->allow('SYSAD', 'default:disciplinarymyincidents', array('index','saveemployeeappeal','getdisciplinaryincidentpdf','edit','view','My Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:disciplinaryteamincidents'));
                            $acl->allow('SYSAD', 'default:disciplinaryteamincidents', array('index','view','Team Incidents'));

		 $acl->addResource(new Zend_Acl_Resource('default:emailcontacts'));
                            $acl->allow('SYSAD', 'default:emailcontacts', array('index','getgroupoptions','getmailcnt','add','edit','delete','view','Email Contacts'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('SYSAD', 'default:employee', array('getemprequi','index','getmoreemployees','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:ethniccode'));
                            $acl->allow('SYSAD', 'default:ethniccode', array('index','saveupdate','addpopup','add','edit','delete','view','Ethnic Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:gender'));
                            $acl->allow('SYSAD', 'default:gender', array('index','saveupdate','addpopup','add','edit','delete','view','Gender'));

		 $acl->addResource(new Zend_Acl_Resource('default:geographygroup'));
                            $acl->allow('SYSAD', 'default:geographygroup', array('index','add','edit','delete','view','Geo Groups'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('SYSAD', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitycodes'));
                            $acl->allow('SYSAD', 'default:identitycodes', array('index','addpopup','add','edit','Identity Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('SYSAD', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('SYSAD','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('SYSAD','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:licensetype'));
                            $acl->allow('SYSAD', 'default:licensetype', array('index','add','edit','delete','view','License Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('SYSAD', 'default:manageremployeevacations', array('index','edit','view','Employee Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:maritalstatus'));
                            $acl->allow('SYSAD', 'default:maritalstatus', array('index','saveupdate','addpopup','add','edit','delete','view','Marital Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('SYSAD', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('SYSAD', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('SYSAD', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationality'));
                            $acl->allow('SYSAD', 'default:nationality', array('index','addpopup','add','edit','delete','view','Nationalities'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationalitycontextcode'));
                            $acl->allow('SYSAD', 'default:nationalitycontextcode', array('index','add','edit','delete','view','Nationality Context Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:numberformats'));
                            $acl->allow('SYSAD', 'default:numberformats', array('index','add','edit','delete','view','Number Formats'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('SYSAD', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('SYSAD', 'default:pendingleaves', array('index','delete','view','My Leave'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('SYSAD', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','add','edit','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:prefix'));
                            $acl->allow('SYSAD', 'default:prefix', array('index','saveupdate','addpopup','add','edit','delete','view','Prefixes'));

		 $acl->addResource(new Zend_Acl_Resource('default:projects'));
                            $acl->allow('SYSAD', 'default:projects', array('index','viewpopup','editpopup','add','edit','delete','view','Projects'));

		 $acl->addResource(new Zend_Acl_Resource('default:racecode'));
                            $acl->allow('SYSAD', 'default:racecode', array('index','saveupdate','addpopup','add','edit','delete','view','Race Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:roles'));
                            $acl->allow('SYSAD', 'default:roles', array('index','saveupdate','getgroupmenu','add','edit','delete','view','Roles & Privileges'));

		 $acl->addResource(new Zend_Acl_Resource('default:sitepreference'));
                            $acl->allow('SYSAD', 'default:sitepreference', array('index','view','add','edit','Site Preferences'));

		 $acl->addResource(new Zend_Acl_Resource('default:states'));
                            $states_add = 'yes';
                                if($this->id_param == '' && $states_add == 'yes')
                                    $acl->allow('SYSAD','default:states', array('index','getstates','getstatescand','addpopup','addnewstate','add','delete','view','States','edit'));

                                else
                                    $acl->allow('SYSAD','default:states', array('index','getstates','getstatescand','addpopup','addnewstate','add','delete','view','States'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('SYSAD', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('default:timezone'));
                            $acl->allow('SYSAD', 'default:timezone', array('index','saveupdate','addpopup','add','edit','delete','view','Time Zones'));

		 $acl->addResource(new Zend_Acl_Resource('default:usermanagement'));
                            $acl->allow('SYSAD', 'default:usermanagement', array('index','saveupdate','getemailofuser','add','edit','view','External Users'));

		 $acl->addResource(new Zend_Acl_Resource('default:vendors'));
                            $acl->allow('SYSAD', 'default:vendors', array('index','addpopup','add','edit','delete','view','Vendors'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assetcategories'));
                            $acl->allow('SYSAD', 'assets:assetcategories', array('index','addpopup','addsubcatpopup','assetuserlog','add','edit','delete','view','Asset Categories'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assets'));
                            $acl->allow('SYSAD', 'assets:assets', array('index','uploadsave','uploaddelete','getsubcategories','deleteimage','downloadimage','getemployeesdata','add','edit','delete','view','Assets'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('SYSAD', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('SYSAD', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('SYSAD', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('SYSAD', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('SYSAD', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('SYSAD', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('exit:allexitproc'));
                            $acl->allow('SYSAD', 'exit:allexitproc', array('index','editpopup','updateexitprocess','assignquestions','add','edit','view','All Exit Procedures'));

		 $acl->addResource(new Zend_Acl_Resource('exit:exitproc'));
                            $acl->allow('SYSAD', 'exit:exitproc', array('index','questions','savequestions','add','edit','view','Initiate/Check Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:managemenus'));
                            $acl->allow('SYSAD', 'default:managemenus', array('index','save','index','save'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('SYSAD', 'default:emppersonaldetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('SYSAD', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('SYSAD', 'default:empcommunicationdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('SYSAD', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('SYSAD', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('SYSAD', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('SYSAD', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('SYSAD', 'default:empleaves', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('SYSAD', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('SYSAD', 'default:disabilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('SYSAD', 'default:workeligibilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('SYSAD', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('SYSAD', 'default:creditcarddetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('SYSAD', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('SYSAD', 'default:empholidays', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('SYSAD', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('SYSAD', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('SYSAD', 'default:assetdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('SYSAD', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('SYSAD', 'default:apprreqcandidates', array('index','viewpopup'));
}

     // setup acl in the registry for more
           Zend_Registry::set('acl', $acl);
           $this->_acl = $acl;
    }
   return $this->_acl;
}
  }
  
  ?>