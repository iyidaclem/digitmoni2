<?php 
//database loading
require_once 'config/BasicConfig.php';
require_once 'database/DataBase.php';

//loading model
require_once 'model/Airtime.php';
require_once 'model/Image.php';
require_once 'model/Investment.php';
require_once 'model/Serch.php';
require_once 'model/Session.php';
require_once 'model/Transaction.php';
require_once 'model/TV.php';
require_once 'model/User.php';
require_once 'model/UserInvestment.php';

//loading helpers 
require_once 'helpers/debugHelper.php';
require_once 'helpers/helper.php';

//laoding validators 
require_once 'validationRules/EmailValidation.php';
require_once 'validationRules/MaxValidation.php';
require_once 'validationRules/MinValidation.php';
require_once 'validationRules/NoSpaceValidation.php';
require_once 'validationRules/SpecialCharValidation.php';










