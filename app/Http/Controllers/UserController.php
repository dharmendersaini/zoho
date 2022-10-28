<?php
 

 
use App\Models\User;



namespace App\Http\Controllers;
use com\zoho\crm\sample\users;
use com\zoho\crm\api\users\UsersOperations;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\users\GetUsersParam;
use com\zoho\crm\api\users\GetUsersHeader;
use com\zoho\crm\api\users\GetUserHeader;
use com\zoho\crm\sample\initializer;

use com\zoho\crm\api\users\ActionWrapper;
use com\zoho\crm\api\users\RequestWrapper;
use com\zoho\crm\api\users\BodyWrapper;
use com\zoho\crm\api\users\ResponseWrapper;
use com\zoho\api\authenticator\OAuthBuilder;

use com\zoho\api\authenticator\store\DBBuilder;

use com\zoho\api\authenticator\store\FileStore;

use com\zoho\crm\api\InitializeBuilder;

use com\zoho\crm\api\UserSignature;

use com\zoho\crm\api\dc\USDataCenter;

use com\zoho\api\logger\LogBuilder;

use com\zoho\api\logger\Levels;

use com\zoho\crm\api\SDKConfigBuilder;

use com\zoho\crm\api\ProxyBuilder;

require base_path().'/vendor/autoload.php';
 
class UserController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show()
    {
        echo "<pre>";
        print_r($_GET);
        die;
    }

    public function initialize()
    {
      /*
        * Create an instance of Logger Class that requires the following
        * level -> Level of the log messages to be logged. Can be configured by typing Levels "::" and choose any level from the list displayed.
        * filePath -> Absolute file path, where messages need to be logged.
      */
      $logger = (new LogBuilder())
      ->level(Levels::INFO)
      ->filePath(base_path()."/php_sdk_log.log")
      ->build();
  
      //Create an UserSignature instance that takes user Email as parameter
      $user = new UserSignature("admin@techoft.com");
  
      /*
        * Configure the environment
        * which is of the pattern Domain::Environment
        * Available Domains: USDataCenter, EUDataCenter, INDataCenter, CNDataCenter, AUDataCenter
        * Available Environments: PRODUCTION(), DEVELOPER(), SANDBOX()
      */
      $environment = USDataCenter::PRODUCTION();
  
      /*
      * Create a Token instance
      * clientId -> OAuth client id.
      * clientSecret -> OAuth client secret.
      * grantToken -> GRANT token.
      * redirectURL -> OAuth redirect URL.
      */
      //Create a Token instance
      $token = (new OAuthBuilder())
      ->clientId("1000.LE3BV012JDYRC5QBMTJEHFMGFV1UWY")
      ->clientSecret("44f7229988052b650df4e5e052816fe4b522aa7832")
      ->grantToken("1000.d88da5c598dc4679a2e0a717f50c896a.13a6541b7b444c2d44ee5907c8926f0d")
      ->redirectURL("http://127.0.0.1:8000/show")
      ->build();
  
      
      /*
       * TokenStore can be any of the following
       * DB Persistence - Create an instance of DBStore
       * File Persistence - Create an instance of FileStore
       * Custom Persistence - Create an instance of CustomStore
      */
  
      /*
      * Create an instance of DBStore.
      * host -> DataBase host name. Default value "localhost"
      * databaseName -> DataBase name. Default  value "zohooauth"
      * userName -> DataBase user name. Default value "root"
      * password -> DataBase password. Default value ""
      * portNumber -> DataBase port number. Default value "3306"
      * tableName -> DataBase table name. Default value "oauthtoken"
      */
      //$tokenstore = (new DBBuilder())->build();
  
      $tokenstore = (new DBBuilder())
      ->host("localhost")
      ->databaseName("zoho")
      ->userName("root")
      ->password("")
      ->portNumber("3306")
      ->tableName("oauthtoken")
      ->build();

  
   new FileStore("/public");
  
      // $tokenstore = new CustomStore();
  
      $autoRefreshFields = false;
  
      $pickListValidation = false;
      $enableSSLVerification = false;
  
      $connectionTimeout = 2;//The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
  
      $timeout = 2;//The maximum number of seconds to allow cURL functions to execute.
  
      $sdkConfig = (new SDKConfigBuilder())->autoRefreshFields($autoRefreshFields)->pickListValidation($pickListValidation)->sslVerification($enableSSLVerification)->connectionTimeout($connectionTimeout)->timeout($timeout)->build();
  
      $resourcePath = "/";
  
    //Create an instance of RequestProxy
    //   $requestProxy = (new ProxyBuilder())
    //   ->host("proxyHost")
    //   ->port("proxyPort")
    //   ->user("proxyUser")
    //   ->password("password")
    //   ->build();
  
      /*
        * Set the following in InitializeBuilder
        * user -> UserSignature instance
        * environment -> Environment instance
        * token -> Token instance
        * store -> TokenStore instance
        * SDKConfig -> SDKConfig instance
        * resourcePath -> resourcePath - A String
        * logger -> Log instance (optional)
        * requestProxy -> RequestProxy instance (optional)
      */
      (new InitializeBuilder())
      ->user($user)
      ->environment($environment)
      ->token($token)
      ->store($tokenstore)
      ->SDKConfig($sdkConfig)
      ->resourcePath($resourcePath)
      ->logger($logger)
      ->initialize();

      $usersOperations = new UsersOperations();

    
     
             //Get instance of ParameterMap Class
             $paramInstance = new ParameterMap();
        
             $paramInstance->add(GetUsersParam::type(), "ActiveUsers");
             
             $paramInstance->add(GetUsersParam::page(), 1);
             
             $paramInstance->add(GetUsersParam::perPage(), 1);
             
             $headerInstance = new HeaderMap();
     
             $ifmodifiedsince = date_create("2022-10-28T17:58:47+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
             
             $headerInstance->add(GetUsersHeader::IfModifiedSince(), $ifmodifiedsince);
             
             //Call getUsers method that takes paramInstance as parameter
             $response = $usersOperations->getUsers($paramInstance);
          


             if($response != null)
        {
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
            }
          
            //Get object from response
            $responseHandler = $response->getObject();
           
            
            if($responseHandler instanceof ResponseWrapper)
            {

              echo "test";
                //Get the received ResponseWrapper instance
                $responseWrapper = $responseHandler;
                
                //Get the list of obtained User instances
                $users = $responseWrapper->getUsers();
               echo "<pre>"; print_r($users);

                
                foreach($users as $user)
                {   
                    //Get the Country of each User
                    echo("User Country: " . $user->getCountry() . "\n");
                    
                    // Get the CustomizeInfo instance of each User
                    $customizeInfo = $user->getCustomizeInfo();
                    
                    //Check if customizeInfo is not null
                    if($customizeInfo != null)
                    {
                        if($customizeInfo->getNotesDesc() != null)
                        {
                            //Get the NotesDesc of each User
                            echo("User CustomizeInfo NotesDesc: " . $customizeInfo->getNotesDesc() . "\n");
                        }
                        
                        if($customizeInfo->getShowRightPanel() != null)
                        {
                            //Get the ShowRightPanel of each User
                            echo("User CustomizeInfo ShowRightPanel: " . $customizeInfo->getShowRightPanel() . "\n");
                        }
                        
                        if($customizeInfo->getBcView() != null)
                        {
                            //Get the BcView of each User
                            echo("User CustomizeInfo BcView: " . $customizeInfo->getBcView() . "\n");
                        }
                        
                        if($customizeInfo->getShowHome() != null)
                        {
                            //Get the ShowHome of each User
                            echo("User CustomizeInfo ShowHome: " . $customizeInfo->getShowHome() . "\n");
                        }
                        
                        if($customizeInfo->getShowDetailView() != null)
                        {
                            //Get the ShowDetailView of each User
                            echo("User CustomizeInfo ShowDetailView: " . $customizeInfo->getShowDetailView() . "\n");
                        }
                        
                        if($customizeInfo->getUnpinRecentItem() != null)
                        {
                            //Get the UnpinRecentItem of each User
                            echo("User CustomizeInfo UnpinRecentItem: " . $customizeInfo->getUnpinRecentItem() . "\n");
                        }
                    }
                    
                    // Get the Role instance of each User
                    $role = $user->getRole();
                    
                    //Check if role is not null
                    if($role != null)
                    {
                        //Get the Name of each Role
                        echo("User Role Name: " . $role->getName() . "\n");
                        
                        //Get the ID of each Role
                        echo("User Role ID: " . $role->getId() . "\n");
                    }
                    
                    //Get the Signature of each User
                    echo("User Signature: " . $user->getSignature() . "\n");
                    
                    //Get the City of each User
                    echo("User City: " . $user->getCity() . "\n");
                    
                    //Get the NameFormat of each User
                    echo("User NameFormat: " . $user->getNameFormat() . "\n");
                    
                    //Get the Language of each User
                    echo("User Language: " . $user->getLanguage() . "\n");
                    
                    //Get the Locale of each User
                    echo("User Locale: " . $user->getLocale() . "\n");
                    
                    //Get the Microsoft of each User
                    echo("User Microsoft: " . $user->getMicrosoft() . "\n");
                    
                    if($user->getPersonalAccount() != null)
                    {
                        //Get the PersonalAccount of each User
                        echo("User PersonalAccount: " . $user->getPersonalAccount() . "\n");
                    }
                    
                    //Get the DefaultTabGroup of each User
                    echo("User DefaultTabGroup: " . $user->getDefaultTabGroup() . "\n");
                    
                    //Get the Isonline of each User
                    echo("User Isonline: " . $user->getIsonline() . "\n");
                    
                    //Get the modifiedBy User instance of each User
                    $modifiedBy = $user->getModifiedBy();
                    
                    //Check if modifiedBy is not null
                    if($modifiedBy != null)
                    {
                        //Get the Name of the modifiedBy User
                        echo("User Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        
                        //Get the ID of the modifiedBy User
                        echo("User Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }
                    
                    //Get the Street of each User
                    echo("User Street: " . $user->getStreet() . "\n");
                    
                    //Get the Currency of each User
                    echo("User Currency: " . $user->getCurrency() . "\n");
                    
                    //Get the Alias of each User
                    echo("User Alias: " . $user->getAlias() . "\n");
                    
                    // Get the Theme instance of each User
                    $theme = $user->getTheme();
                    
                    //Check if theme is not null
                    if($theme != null)
                    {
                        // Get the TabTheme instance of Theme
                        $normalTab = $theme->getNormalTab();
                        
                        //Check if normalTab is not null
                        if($normalTab != null)
                        {
                            //Get the FontColor of NormalTab
                            echo("User Theme NormalTab FontColor: " . $normalTab->getFontColor() . "\n");
                            
                            //Get the Name of NormalTab
                            echo("User Theme NormalTab Name: " . $normalTab->getBackground() . "\n");
                        }
                        
                        // Get the TabTheme instance of Theme
                        $selectedTab = $theme->getSelectedTab();
                        
                        //Check if selectedTab is not null
                        if($selectedTab != null)
                        {
                            //Get the FontColor of SelectedTab
                            echo("User Theme SelectedTab FontColor: " . $selectedTab->getFontColor() . "\n");
                            
                            //Get the Name of SelectedTab
                            echo("User Theme SelectedTab Name: " . $selectedTab->getBackground() . "\n");
                        }
                        
                        //Get the NewBackground of each Theme
                        echo("User Theme NewBackground: " . $theme->getNewBackground() . "\n");
                        
                        //Get the Background of each Theme
                        echo("User Theme Background: " . $theme->getBackground() . "\n");
                        
                        //Get the Screen of each Theme
                        echo("User Theme Screen: " . $theme->getScreen() . "\n");
                        
                        //Get the Type of each Theme
                        echo("User Theme Type: " . $theme->getType() . "\n");
                    }
                    
                    //Get the ID of each User
                    echo("User ID: " . $user->getId() . "\n");
                    
                    //Get the State of each User
                    echo("User State: " . $user->getState() . "\n");
                    
                    //Get the Fax of each User
                    echo("User Fax: " . $user->getFax() . "\n");
                    
                    //Get the CountryLocale of each User
                    echo("User CountryLocale: " . $user->getCountryLocale() . "\n");
                    
                    //Get the FirstName of each User
                    echo("User FirstName: " . $user->getFirstName() . "\n");
                    
                    //Get the Email of each User
                    echo("User Email: " . $user->getEmail() . "\n");
                    
                    //Get the reportingTo User instance of each User
                    $reportingTo = $user->getReportingTo();
                    
                    //Check if reportingTo is not null
                    if($reportingTo != null)
                    {
                        //Get the Name of the reportingTo User
                        echo("User ReportingTo Name: " . $reportingTo->getName() . "\n");
                        
                        //Get the ID of the reportingTo User
                        echo("User ReportingTo ID: " . $reportingTo->getId() . "\n");
                    }
                    
                    //Get the DecimalSeparator of each User
                    echo("User DecimalSeparator: " . $user->getDecimalSeparator() . "\n");
                    
                    //Get the Zip of each User
                    echo("User Zip: " . $user->getZip() . "\n");
                    
                    //Get the CreatedTime of each User
                    echo("User CreatedTime: ");
                    
                    print_r($user->getCreatedTime());

                    echo("\n");
                    
                    //Get the Website of each User
                    echo("User Website: " . $user->getWebsite() . "\n");
                    
                    //Get the ModifiedTime of each User
                    echo("User ModifiedTime: ");

                    print_r($user->getModifiedTime());

                    echo("\n");
                    
                    //Get the TimeFormat of each User
                    echo("User TimeFormat: " . $user->getTimeFormat() . "\n");
                    
                    //Get the Offset of each User
                    echo("User Offset: " . $user->getOffset() . "\n");
                    
                    //Get the Profile instance of each User
                    $profile = $user->getProfile();
                    
                    //Check if profile is not null
                    if($profile != null)
                    {
                        //Get the Name of each Profile
                        echo("User Profile Name: " . $profile->getName() . "\n");
                        
                        //Get the ID of each Profile
                        echo("User Profile ID: " . $profile->getId() . "\n");
                    }
                    
                    //Get the Mobile of each User
                    echo("User Mobile: " . $user->getMobile() . "\n");
                    
                    //Get the LastName of each User
                    echo("User LastName: " . $user->getLastName() . "\n");
                    
                    //Get the TimeZone of each User
                    echo("User TimeZone: " . $user->getTimeZone() . "\n");
                    
                    //Get the createdBy User instance of each User
                    $createdBy = $user->getCreatedBy();
                    
                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Name of the createdBy User
                        echo("User Created By User-Name: " . $createdBy->getName() . "\n");
                        
                        //Get the ID of the createdBy User
                        echo("User Created By User-ID: " . $createdBy->getId() . "\n");
                    }

                    //Get the Zuid of each User
                    echo("User Zuid: " . $user->getZuid() . "\n");
                    
                    //Get the Confirm of each User
                    echo("User Confirm: " . $user->getConfirm() . "\n");
                    
                    //Get the FullName of each User
                    echo("User FullName: " . $user->getFullName() . "\n");
                    
                    //Get the list of obtained Territory instances
                    $territories = $user->getTerritories();
                    
                    //Check if territories is not null
                    if($territories != null)
                    {
                        foreach($territories as $territory)
                        {
                            //Get the Manager of the Territory
                            echo("User Territory Manager: " . $territory->getManager() . "\n");
                            
                            //Get the Name of the Territory
                            echo("User Territory Name: " . $territory->getName() . "\n");
                            
                            //Get the ID of the Territory
                            echo("User Territory ID: " . $territory->getId() . "\n");
                        }
                    }
                    
                    //Get the Phone of each User
                    echo("User Phone: " . $user->getPhone() . "\n");
                    
                    //Get the DOB of each User
                    echo("User DOB: " . $user->getDob() . "\n");
                    
                    //Get the DateFormat of each User
                    echo("User DateFormat: " . $user->getDateFormat() . "\n");
                    
                    //Get the Status of each User
                    echo("User Status: " . $user->getStatus() . "\n");
                }
                
                //Get the Object obtained Info instance
                $info = $responseWrapper->getInfo();
                
                //Check if info is not null
                if($info != null)
                {
                    if($info->getPerPage() != null)
                    {
                        //Get the PerPage of the Info
                        echo("User Info PerPage: " . $info->getPerPage() . "\n");
                    }
                    
                    if($info->getCount() != null)
                    {
                        //Get the Count of the Info
                        echo("User Info Count: " . $info->getCount() . "\n");
                    }
                    
                    if($info->getPage() != null)
                    {
                        //Get the Page of the Info
                        echo("User Info Page: " . $info->getPage() . "\n");
                    }
                    
                    if($info->getMoreRecords() != null)
                    {
                        //Get the MoreRecords of the Info
                        echo("User Info MoreRecords: " . $info->getMoreRecords() . "\n");
                    }
                }
            }
          }

      
    }

   
}