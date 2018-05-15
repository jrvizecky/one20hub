<?php if ( true ) :
	error_log( print_r( isset($_GET["type"]),true ) );

	if(isset($_GET["type"]) && $_GET["type"] == "signup"){
		$isLogin = false;
	} else {
		$isLogin = true;
	}
	if ( isset($_GET['redirect_to']) ) {
        $redirectUrl = $_GET['redirect_to'];
    } else {
	    $redirectUrl = '';
    }


	?>
    <style>
        .login-form-container {
            width:222px;
        }

        #mailbutton,
        #btn-signup-mail{
            position: relative;
            margin-top: 30px;
        }
    </style>
    <div id="firebaseui-auth-container"></div>
    <script>
        // Facebook, Google or Email
        var loginMethod = "";

        var updatedDriverType = "";

        var homeUrl = "<?php echo home_url()?>";
        var env = "QA";
        if (homeUrl === "https://one20.com") {
            env = "Prod";
        }
        var config = [];
        var ProfileURL = []; // add var to hold URL - TOMM

        ProfileURL.QA = 'https://4hgtx911f9.execute-api.us-west-2.amazonaws.com/qa/v1/userprofile/';
        ProfileURL.Prod = 'https://9hqcqyyon0.execute-api.us-east-1.amazonaws.com/production/v1/userprofile/';

        config.QA = {
            apiKey: "AIzaSyCpHBbd6ykX2fmtgZslXFM3S7TGz4wBkLo",
            authDomain: "one20-qa.firebaseapp.com",
            databaseURL: "https://one20-qa.firebaseio.com",
            storageBucket: "one20-qa.appspot.com",
            messagingSenderId: "775537782451",
        };
        config.Prod = {
            apiKey: "AIzaSyBbVDfsi5L7wfP6JMOTocfmpjFZ0fcJdao",
            authDomain: "one20-production.firebaseapp.com",
            databaseURL: "https://one20-production.firebaseio.com",
            projectId: "one20-production",
            storageBucket: "one20-production.appspot.com",
            messagingSenderId: "613584757068"
        };

        var currentConfig = config[env];
        firebase.initializeApp(currentConfig);

        var state ="login";

        var dbhelper = {
            createNew: function(){
                var dbhelper = {};
                dbhelper.updateUserProfile = function(userinfo){
                    debugPrint("updateUserProfile " + ProfileURL[env]);
                    var token = sessionStorage.userToken;
                    jQuery.ajax({
                        url: ProfileURL[env],
                        type: 'PUT',
                        async: false,
                        cache: false,
                        data: JSON.stringify(userinfo),
                        dataType: "json",
                        headers: { "access-token":token,"content-type":"application/json"},
                        success: function( response ) {
                            debugPrint(JSON.stringify(response));
                            // return JSON.stringify(response);
                            return true;
                        },
                        error: function(error){
                            debugPrint(error);
                            throw { code:'profile/update-profile-faild', message:'none'};
                        }
                    });
                };

                dbhelper.createUserCMD = function(userinfo, token){
                    debugPrint(token);
                    debugPrint(JSON.stringify(userinfo) );
                    jQuery.ajax({
                        url: ProfileURL[env],
                        type: 'POST',
                        async: false,
                        cache: false,
                        data: JSON.stringify(userinfo),
                        dataType: "json",
                        headers: { "access-token":token,"content-type":"application/json"},
                        success: function( response ) {
                            debugPrint(JSON.stringify(response));
                            return true;
                        },
                        error: function(error){
                            debugPrint(error);
                            throw { code:'profile/create-uesr-faild', message:error};
                            return false;
                        }
                    });
                }

                dbhelper.createUser = function(userinfo){
                    debugPrint("createUser");
                    var token = sessionStorage.userToken;
                    this.createUserCMD(userinfo, token);
                };

                dbhelper.getUserProfile= function(userid){
                    debugPrint("dbhelper.getUserProfile user = " + userid + " " + ProfileURL[env]) ;
                    var token = sessionStorage.userToken; // TOM used the sesseion Token
                    debugPrint("dbhelper.getUserProfile token = " + token ) ;

                    try {
                        // For a new account, a message will be returned by the API
                        // We should return null value for this case.
                        return jQuery.ajax({
                            url: ProfileURL[env],
                            type: 'GET',
                            async: false,
                            cache: false,
                            headers: { "access-token":token,"content-type":"application/json"}
                        });
                    } catch ( error) {
                        debugPrint("getUserProfile AJAX return " + error);
                        throw {code:"profile/get-profile-faild", message:error};
                    }
                };

                return dbhelper;
            }
        }

        var db_helper = dbhelper.createNew();

        function loginGoogle() {
            loginMethod = "Google";

            var provider = new firebase.auth.GoogleAuthProvider();

						jQuery('#dvLoading').show();

            firebase.auth().signInWithPopup(provider).then(function (result) {

                // The signed-in user info.
                var user = result.user;
                segmentIdentify(user);
                debugPrint("GoogleAuthProvider success for " + user);

                firebase.auth().currentUser.getIdToken(true).then((token) => {
                    sessionStorage.userToken = token; // TOM Need to store the token
                debugPrint("loginGoogle sessionStorage.userToken = " + token);

                var userId = firebase.auth().currentUser.uid;

                getUserProfile().then(function(snapshot) {
                    // User will be required to fill in extra data only the first time
                    // No snapshot means now is the first time current user sign in on One20
                    debugPrint("GoogleAuthProvider saveUserToken = " + JSON.stringify(user));
                    saveUserToken(user);
                    debugPrint("GoogleAuthProvider snapshot = " + JSON.stringify(snapshot) );
                    if(snapshot == null ) { // TOM Check NULL
                        segmentOnboardingStarted(user);
                        createUser(user, user.email).then(()=>{
                            // Go to step 2
                            jQuery("#main-login").toggle();
                        jQuery("#pickusertype").toggle();
                        jQuery("#step3-next").hide();
                        jQuery("#step3-next-fake").show();
												jQuery('#dvLoading').hide();
                    }).catch((error)=>{
                            debugPrint("GoogleAuthProvider createUser failure = " + JSON.stringify(error));
							jQuery('#dvLoading').hide();
                    });
                    } else {
                        segmentLogInEvent(user);
                        // Continu with wordpress login process
                        wpLogin();
                    }
                })
            }); // TOM end getIdToken
            }).catch(function (error) {
                // Handle Errors here.
                var errorCode = error.code;
                var errorMessage = error.message;
                // The email of the user's account used.
                var email = error.email;
                // The firebase.auth.AuthCredential type that was used.
                var credential = error.credential;
                debugPrint("GoogleAuthProvider signInPopupfailure = " + JSON.stringify(error) );
                alert("GoogleAuthProvider failure = " + errorCode + " " + errorMessage);
								jQuery('#dvLoading').hide();
            });
        }

        function loginFacebook() {
            loginMethod = "Facebook";

            var provider = new firebase.auth.FacebookAuthProvider();

						jQuery('#dvLoading').show();

            firebase.auth().signInWithPopup(provider).then(function (result) {
                // The signed-in user info.
                var user = result.user;
                debugPrint("FacebookAuthProvider success for " + user);
                // This gives you a Facebook Access Token. You can use it to access the Facebook API.
                user.getIdToken(true).then((token) => {
                    sessionStorage.userToken = token; // TOM Need to store the token
                debugPrint("loginFacebook sessionStorage.userToken = " + token);

                var user = firebase.auth().currentUser;

                getUserProfile().then(function(snapshot) {

                    if(snapshot == null ) { // TOM Check NULL
                        jQuery("#main-login").toggle();
                        // TODO: verify email
                        if(user.email == null){
                            // jump to ask for email page
                            debugPrint("email required");
                            jQuery("#main-login").hide();
                            jQuery("#main-signup").hide();
                            jQuery("#facebook-email").show();
														jQuery('#dvLoading').hide();
                        } else {
                            // Go to step 3
                            segmentIdentify(user);
                            createUser(user, user.email).then(()=>{
                                jQuery("#pickusertype").toggle();
                                jQuery("#step3-next").hide();
                                jQuery("#step3-next-fake").show();
                        }).catch((error)=>{
                                debugPrint("FacebookAuthProvider createUser failure = " + JSON.stringify(error));
                        });
                            segmentOnboardingStarted(user);
												jQuery('#dvLoading').hide();
                        }
                    } else {
                        // Continu with wordpress login process
                        debugPrint(user);
                        if(user.email == null){
                            user.updateEmail(JSON.parse(snapshot).email);
                            user.reload();
                            debugPrint("user updated");
                            debugPrint(user);
                            // document.getElementById('user_login').value = snapshot.email;
														jQuery('#dvLoading').hide();
                        }
                        segmentLogInEvent(user);
                        wpLogin();
                    }
                })
            }); // TOM END getIdToken
            }).catch(function (error) {
                // Handle Errors here.
                var errorCode = error.code;
                var errorMessage = error.message;
                // The email of the user's account used.
                var email = error.email;
                // The firebase.auth.AuthCredential type that was used.
                var credential = error.credential;
                debugPrint("FacebookAuthProvider failure = " + errorCode + " " + errorMessage + " " + email + " " + credential);
                alert("FacebookAuthProvider failure = " + errorCode + " " + errorMessage);
                debugPrint(error);
								jQuery('#dvLoading').hide();
            });
        }

        function loginEmailCheck() {

            loginMethod = "Email";

            var email = document.getElementById('log-email').value;


            if(state == "bad-email"){
                email = document.getElementById('bad-email-enter').value;
            } else if(state == "sign-up"){
                email = document.getElementById('sign-email').value;
            }

            // A mock password to do sign in and verify whether the mail existent.
            var pw = "55be5bed-7c00-4e00-a279-527a5887b4cf";
            debugPrint("EmailAuthProvider for email:" + email);

						// spinner
						jQuery('#dvLoading').show();

            firebase.auth().fetchProvidersForEmail(email).then(function(result){
                debugPrint(result);
                debugPrint('state: ' + state);

                if(result.length > 0){
                    // existent account
                    if(state == "login") {
                        jQuery("#main-login").hide();
												jQuery('#dvLoading').hide();
                    } else if(state == "bad-email") {
                        jQuery("#bad-email").hide();
												jQuery('#dvLoading').hide();
                    } else if(state == "reset-pwd"){
                        jQuery("#reset-pwd").hide();
												jQuery('#dvLoading').hide();
                    }

                    if(result[0] == "password"){
                        // email account
                        // go to enter password
                        state = "mailstep2";
                        jQuery("#log-email").val(email);
                        jQuery("#mailstep2").show();
												jQuery('#dvLoading').hide();
                    } else {
                        // go to special login
                        if(result[0] == "google.com") {
                            jQuery("#facebook").hide();
                            jQuery("#google").show();
                            jQuery("#account-type").html("Google");
														jQuery('#dvLoading').hide();
                        } else {
                            jQuery("#facebook").show();
                            jQuery("#google").hide();
                            jQuery("#account-type").html("Facebook");
														jQuery('#dvLoading').hide();
                        }

                        jQuery("#special-login").show();
												jQuery('#dvLoading').hide();
                    }

                } else {
                    // new account
                    jQuery("#pre-state").html(state);

                    if(state == "login") {
                        jQuery("#main-login").hide();

                    } else if(state == "sign-up") {
                        jQuery("#main-signup").hide();
                    } else if(state == "bad-email") {
                        jQuery("#bad-email").hide();
                    }

                    state = "addpwd";
                    jQuery("#new-email").val(email);
                    jQuery("#add-pwd").show();
										jQuery('#dvLoading').hide();
                }


            }).catch(function (error) {
                // Handle Errors here.
                // auth/invalid-email
                // auth/user-not-found
                // auth/wrong-password wilcome back
                var errorCode = error.code;
                var errorMessage = error.message;

                if (errorCode == 'auth/wrong-password') {
                    // Existent user
                    jQuery("#main-login").toggle();
                    jQuery("#mailstep2").toggle();
                } else if(errorCode == 'auth/user-not-found')
                {
                    // jump to password adding page
                    jQuery("#main-login").toggle();
                    jQuery("#add-pwd-title").val("Create a unique password for your account");
                    jQuery("#new-email").val(email);
                    jQuery("#pwdnotmatch").hide();
                    jQuery("#add-pwd").toggle();
                } else if(errorCode == "auth/invalid-email") {
                    switch(state){
                        case "login":
                            jQuery("#main-login").toggle();
                            jQuery("#bad-email-enter").val(email);
                            state = "bad-email";
                            jQuery("#bad-email").toggle();
														jQuery('#dvLoading').hide();
                            break;
                        case "bad-email":
                            break;
                        case "sign-up":
                            // display error message
                            jQuery("#invalid-signup-email").show();
														jQuery('#dvLoading').hide();
                            break;
                    }
                }
                debugPrint(error);
                debugPrint("EmailAuthProvider failure = " + errorCode + " " + errorMessage);
								jQuery('#dvLoading').hide();
            });
        }

        function createUser(user, email)
        {
            return saveUserToken(user).then(()=>{
                var userinfo = {
                    "drivertype": "",
                    "email": "",
                    "firstName": "",
                    "lastName": "",
                    "middleInitial": "",
                    "one20Handle": "",
                    "phoneNumber": "",
                    "userTypeOther": "",
                    "userType": ""
                };


            userinfo.email = user.email;
            // <!-- TOM Fix value -->
            userinfo.drivertype = "INDEPENDENT_OWNER_OPERATOR";
            userinfo.firstName = "TBD";
            userinfo.lastName = "TBD";
            userinfo.phoneNumber = (user.phoneNumber==null?"":user.phoneNumber); // TOM
            userinfo.one20Handle = "";
            // <!-- TOM Fix value -->
            userinfo.userType = "driver";

            return new Promise(function(resolve, reject){
                try {
                    db_helper.createUser(userinfo);
                    //firebase.auth().currentUser.delete();
                    resolve(userinfo);
                } catch(err){
                    //firebase.auth().currentUser.delete();
                    reject(err);
                }
            });
        });

        }

        function updateUserDetail(user, firstname, lastname, phonenumber, one20handle){

            var userinfo = { "driverType":"", "firstName": "", "lastName":"", "phoneNumber":"", "one20Handle":"" };

            if(updatedDriverType != "") {
                userinfo.driverType = updatedDriverType;
            } else {
                delete userinfo.driverType;
            }

            if(firstname != "")	{
                userinfo.firstName = firstname;
            } else {
                delete userinfo.firstName;
            }

            if(lastname != "")	{
                userinfo.lastName = lastname;
            } else {
                delete userinfo.lastName;
            }

            if(phonenumber != "")	{
                userinfo.phoneNumber = phonenumber;
            } else {
                delete userinfo.phoneNumber;
            }

            if(one20handle != "")	{
                userinfo.one20Handle = one20handle;
            } else {
                delete userinfo.one20Handle;
            }

            return new Promise(function(resolve, reject){
                try {
                    db_helper.updateUserProfile(userinfo);
                    resolve(userinfo);
                } catch(err){
                    // firebase.auth().currentUser.delete();
                    reject(err);
                }
            });
        }

        function updateDriverType(user, drivertype) {

            var userinfo = { "driverType":drivertype };

            return new Promise(function(resolve, reject){
                try {
                    db_helper.updateUserProfile(userinfo);
                    resolve(userinfo);
                } catch(err){
                    //firebase.auth().currentUser.delete();
                    reject(err);
                }
            });

        }

        function getUserProfile(){
            debugPrint("getUserProfile = " + currentConfig.dbtype );

            return new Promise(function(resolve, reject){
                try {
                    debugPrint("getUserProfile start");
                    var profile = db_helper.getUserProfile(null); // TOM pass null no user object???
                    debugPrint(profile.responseText);
                    var obj = JSON.parse(profile.responseText);
                    <!-- TOM Fix value -->
                    debugPrint("getUserProfile end profile.responseText = " + JSON.stringify(obj) ) ;
                    if(obj.createdOn >= 0) {
                        resolve(profile.responseText);
                    } else {
                        resolve(null);
                    }
                } catch(err){
                    // responseText
                    debugPrint("getUserProfile end error " + err);
                    reject(err);
                }
            });

        }

        function showMainLogin(state) {
            switch(state){
                case "login":
                    break;
                case "":
                    break;
            }
        }

        function moveToStep4()
        {
            // Save user type to firebase
            // <!-- TOM fix value -->
            var drivertype =  jQuery("input[name='optionsRadios']:checked").val();
            debugPrint("usertype=" + drivertype);

            //var user = firebase.auth().currentUser;

            updatedDriverType = drivertype;

            debugPrint("This is what I'm changing");

            jQuery('#dvLoading').show();
            jQuery("#pickusertype").toggle();
            jQuery("#step4").toggle();

            jQuery("#step-done").hide();
            jQuery("#step-done-fake").show();
             /*
            updateDriverType(user, drivertype).then(function(value){
                debugPrint(value);
                jQuery("#pickusertype").toggle();
                jQuery("#step4").toggle();
            }).catch(function(error){
                debugPrint(error);
                debugPrint("Save user type failure = " + errorCode + " " + errorMessage);
            });*/
             jQuery('#dvLoading').delay(300).hide(0);
        }
        function loginDone()
        {
            // Save user details
            var firstname =  jQuery("#first-name").val();
            var lastname =  jQuery("#last-name").val();
            var phonenumber =  jQuery("#phone-number").val();
            var one20handle =  jQuery("#one20-handle").val();

            var user = firebase.auth().currentUser;

						jQuery('#dvLoading').show();

            updateUserDetail(user, firstname, lastname, phonenumber, one20handle).then(function(value){

                debugPrint(value);

                jQuery('#dvLoading').show();

                wpLogin();

                user.sendEmailVerification().then(function(){
                    user.reload();
                    jQuery("#verify-email").html(user.email);
                    //jQuery("#done").show();
                }).catch(function(error){
                    debugPrint(error);
										jQuery('#dvLoading').delay(300).hide(0);
                });
            }).catch(function(error){
                debugPrint(error);
                debugPrint("Save user detail failure = " + errorCode + " " + errorMessage);
                alert("Save user detail failure = " + errorCode + " " + errorMessage);
            });


            segmentOnboardingCompleted();
        }

        function wpLogin()
        {
            debugPrint("wp login...");

            var user = firebase.auth().currentUser;

            document.getElementById('user_id').value = user.uid;
            document.getElementById('user_name').value = user.displayName;
            document.getElementById('user_login').value = user.email;
            document.getElementById('user_pass').value = user.uid;
            document.getElementById('loginForm').submit();
            debugPrint("wp login end...");
        }

        function loginMail()
        {
						jQuery('#dvLoading').show();
            var email = document.getElementById('log-email').value;
            var pw = document.getElementById('mailpassword').value;

            firebase.auth().signInWithEmailAndPassword(email,pw).then(function(){
                var user = firebase.auth().currentUser;
                saveUserToken(user);
                segmentIdentify(user);
                segmentLogInEvent(user);

                debugPrint("EmailAuthProvider success = " + user.email );
                // jQuery("#mailstep2").hide();

                wpLogin();
								// jQuery('#dvLoading').hide();
            }).catch(function (error) {
                // Handle Errors here.
                // auth/invalid-email
                // auth/user-not-found
                // auth/wrong-password wilcome back
                var errorCode = error.code;
                var errorMessage = error.message;

                if (errorCode == 'auth/wrong-password') {
                    // Existent user
                    jQuery("#mailErrorMsg").show();
                }
                debugPrint(error);
                debugPrint("EmailAuthProvider failure = " + errorCode + " " + errorMessage);
				jQuery('#dvLoading').hide();
            });
        }

        function addpwd()
        {
            jQuery('#dvLoading').show();

            var email = document.getElementById('new-email').value;
            var pwd1 = jQuery("#pwd1").val();
            var pwd2 = jQuery("#pwd2").val();

            if(pwd1 != pwd2){
                jQuery("#pwdnotmatch").show();
                jQuery(function(){
                    jQuery(".action-button").prop('disabled', false);
                });
                jQuery('#dvLoading').delay(300).hide(0);
            } else {
                // add user to firebase
                firebase.auth().createUserWithEmailAndPassword(email, pwd1).then(function(){
									jQuery('#dvLoading').show();
                    var user = firebase.auth().currentUser;
                    segmentIdentify(user);
                    segmentOnboardingStarted(user);
                    return createUser(user, email).then(()=>{
                        jQuery("#add-pwd").toggle();
                    jQuery("#pickusertype").toggle();
                    jQuery("#step3-next").hide();
                    jQuery("#step3-next-fake").show();
                    jQuery('#dvLoading').delay(300).hide(0);
                });
                }).catch(function(error) {
                    // Handle Errors here.
                    var errorCode = error.code;
                    var errorMessage = error.message;
                    debugPrint(error);
                    debugPrint("Create user by email failure = " + errorCode + " " + errorMessage);
                    alert("Create user by email failure = " + errorCode + " " + errorMessage);
                    jQuery('#dvLoading').hide();
                });
            }
        }

        function movetosignup()
        {
						jQuery('#dvLoading').show();
            if(state == "bad-email") {
                jQuery()
                jQuery("#bad-email").toggle();
                state = "sign-up"
                jQuery('#sign-email').val("");
                jQuery("#main-signup").toggle();
								jQuery('#dvLoading').delay(300).hide(0);
            }

        }

        function showforgotpwd(){
					jQuery('#dvLoading').show();
            jQuery("#mailstep2").hide();
            jQuery("#confirm-email").val(jQuery("#log-email").val());
            jQuery("#forgot-pwd").show();
						jQuery('#dvLoading').delay(300).hide(0);
        }

        function resetpwd() {
            // send mail
            var email = jQuery("#confirm-email").val();
						jQuery('#dvLoading').show();
            firebase.auth().sendPasswordResetEmail(email)
                .then(function(result){
                    // goto login;
                    jQuery("#forgot-pwd").hide();
                    jQuery("#pwd-send").show();
										jQuery('#dvLoading').hide();
                })
                .catch(function(error){
                    debugPrint(error);
										jQuery('#dvLoading').hide();
                });
        }

        function onInput(event) {
            if(jQuery("#pwd1").val().length >= 6 && jQuery("#pwd2").val().length >=6){
                jQuery("#step-addpwd").show();
                jQuery("#step-addpwd-fake").hide();
            } else {
                jQuery("#step-addpwd").hide();
                jQuery("#step-addpwd-fake").show();
            }
        };

        function saveUserToken(user){
            debugPrint("saveUserToken " + user);
            return user.getIdToken(true).then(function(token) {
                debugPrint( "AccessToken = " + token );
                sessionStorage.userToken=token;
            }).then(function(){
                var token = sessionStorage.userToken;
                head = {'access-token': token};
                debugPrint(head);
            }).catch(function(error){
                var errorCode = error.code;
                var errorMessage = error.message;
                debugPrint(error);
                debugPrint("Save access token failure = " + errorCode + " " + errorMessage);
            });

        }

        function facebookEmailCheck(){
            // not null, match patten
            debugPrint(jQuery("#facebook-email1")[0].validity.patternMismatch);
            var mail1 = jQuery("#facebook-email1");
            var mail2 = jQuery("#facebook-email2");

            if(!mail1[0].validity.patternMismatch && !mail2[0].validity.patternMismatch && mail1.val() == mail2.val()) {
                jQuery("#email-next").show();
                jQuery("#email-next-fake").hide();
            } else {
                jQuery("#email-next").hide();
                jQuery("#email-next-fake").show();
            }
        }

        function facebookEmailDone() {
            var email = jQuery("#facebook-email1").val();
            jQuery('#user_login').val(email);
            var user = firebase.auth().currentUser;

            // update email on firebase auth
            user.updateEmail(email).then(function(){
                debugPrint("Updated current user's email address");

                segmentIdentify(user);

                createUser(user, email).then(function(result){
                    jQuery('#facebook-email').hide();
                    jQuery('#pickusertype').show();
                    jQuery("#step3-next").hide();
                    jQuery("#step3-next-fake").show();
                }).catch(function(error){
                    debugPrint(error);
                    debugPrint("Save user type failure = " + errorCode + " " + errorMessage);
                });

                segmentOnboardingStarted(user);
            }).catch(function(error){
                debugPrint(error);
            });
        }

        jQuery(function(){
            jQuery("#facebook-email1").bind("input", facebookEmailCheck);
            jQuery("#facebook-email2").bind("input", facebookEmailCheck);
            jQuery("#facebook-email1").bind("propchanged", facebookEmailCheck);
            jQuery("#facebook-email2").bind("propchanged", facebookEmailCheck);
        });

        jQuery(function(){
            jQuery("#pwd1").bind("input", onInput);
            jQuery("#pwd2").bind("input", onInput);
            jQuery("#pwd1").bind("propchanged", onInput);
            jQuery("#pwd2").bind("propchanged", onInput);
        });

        jQuery(function(){
            jQuery("#first-name").bind("input", onDriverInfoInput);
            jQuery("#last-name").bind("input", onDriverInfoInput);
            jQuery("#one20-handle").bind("input", onDriverInfoInput);
        });

        function scrollWin() {
            window.scrollTo(0, 30);
        }

        /**
         * Function called when radio button is selected in step 3 (driver type selection)
         */
        function radioButton() {
            jQuery("#step3-next").show();
            jQuery("#step3-next-fake").hide();
        }

        /**
         * Called whenever input is changed in any of the driver info fields (step 4 of onboarding)
         */
        function onDriverInfoInput() {
            if(jQuery("#first-name").val().length > 0 && jQuery("#last-name").val().length > 0 && jQuery("#one20-handle").val().length > 0){
                jQuery("#step-done").show();
                jQuery("#step-done-fake").hide();
            } else {
                jQuery("#step-done").hide();
                jQuery("#step-done-fake").show();
            }
        }

        /**
         * Pushes all user data to GTM which calls Identify in Segment
         * @param user: User object from Firebase
         */
        function segmentIdentify(user) {
            localStorage.setItem('uid', user.uid);
            localStorage.setItem('email', user.email);

            window.dataLayer.push({
                "event": "event",
                "identify": "true",
                "user": {
                    "email": user.email,
                    "id": user.uid
                }
            });
        }

        function segmentLogInEvent(user) {
            window.dataLayer.push({
                "event": "event",
                "userLogIn": "true",
                "identify": "false",
                "user": {
                    "id": user.uid,
                    "email": user.email
                }
            });
        }

        /**
         * Pushes data needed to GTM to call onboarding_started event in Segment
         */
        function segmentOnboardingStarted(user) {
            window.dataLayer.push({
                "event": "event",
                "onboardingStarted": "true",
                "identify": "false",
                "user": {
                    "id": user.uid,
                    "email": user.email
                }
            });
        }

        /**
         * Pushes data needed to GTM to call onboarding_completed event in Segment
         */
        function segmentOnboardingCompleted() {
            var profile = db_helper.getUserProfile(null);
            debugPrint(profile.responseText);
            var obj = JSON.parse(profile.responseText);

            window.dataLayer.push({
                "event": "event",
                "onboardingStarted": "false",
                "onboardingCompleted": "true",
                "user": {
                    "email": obj["email"],
                    "firstName": obj["firstName"],
                    "lastName": obj["lastName"],
                    "one20Handle": obj["one20Handle"],
                    "phoneNumber": obj["phoneNumber"],
                    "driverType": obj["driverType"],
                    "loginMethod": loginMethod,
                    "createdOn": obj["createdOn"]
                }
            });
        }

        function debugPrint(statement) {
            if (env !== "Prod") {
                console.log(statement);
            }
        }


    </script>

    <div class="login-form-container">

        <div id="main-signup" style="display:<?php echo $isLogin?'none':'block' ?>;text-align: center;">
            <p class="top-content" style=";">Sign up</p>
            <ul class="firebaseui-idp-list">
                <li class="firebaseui-list-item">
                    <button onClick="scrollWin();javascript:loginFacebook();" class="firebaseui-idp-button mdl-button mdl-js-button mdl-button--raised firebaseui-idp-facebook firebaseui-id-idp-button" data-provider-id="facebook.com" data-upgraded=",MaterialButton">
				<span class="firebaseui-idp-icon-wrapper">
					<img class="firebaseui-idp-icon" alt="" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/facebook.svg"></span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-long">SIGN UP WITH FACEBOOK</span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-short">Facebook</span>
                    </button>
                </li>
                <li class="firebaseui-list-item">
                    <button onClick="scrollWin();javascript:loginGoogle();" class="firebaseui-idp-button mdl-button mdl-js-button mdl-button--raised firebaseui-idp-google firebaseui-id-idp-button" data-provider-id="google.com" data-upgraded=",MaterialButton">
                        <span class="firebaseui-idp-icon-wrapper"><img class="firebaseui-idp-icon" alt="" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"></span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-long">SIGN UP WITH GOOGLE</span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-short">Google</span>
                    </button>
                </li>
            </ul>

            <div class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-upgraded" data-upgraded=",MaterialTextfield">
                <div>
                    <label class="mdl-textfield__label firebaseui-label" for="email">Email</label>
                    <input name="email" id="sign-email" autocomplete="on" class="mdl-textfield__input firebaseui-input firebaseui-id-email" value="" type="email">
                    <div id="invalid-signup-email" style="color:#d0021b;display:none;">Don't recognize your email.</div>
                </div>
                <button type="button" id="btn-signup-mail" onClick="scrollWin();javascript:loginEmailCheck();"
                        class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 48px;" class="firebaseui-idp-text firebaseui-idp-text-long">SIGN UP WITH EMAIL</span>
                </button>
            </div>
        </div>

        <div id="main-login" style="display:<?php echo $isLogin?'block':'none' ?>;text-align: center;">
            <p class="top-content" style="">Sign Up or Log In</p>

            <ul class="firebaseui-idp-list">
                <li class="firebaseui-list-item" >
                    <button id="facebook-login" onClick="scrollWin();javascript:loginFacebook();" class="firebaseui-idp-button mdl-button mdl-js-button mdl-button--raised firebaseui-idp-facebook firebaseui-id-idp-button" data-provider-id="facebook.com" data-upgraded=",MaterialButton">
				<span class="firebaseui-idp-icon-wrapper">
					<img class="firebaseui-idp-icon" alt="" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/facebook.svg">
				</span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-long">CONTINUE WITH FACEBOOK</span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-short">FACEBOOK</span>
                    </button>
                </li>
                <li class="firebaseui-list-item">
                    <button onClick="scrollWin();javascript:loginGoogle();" class="firebaseui-idp-button mdl-button mdl-js-button mdl-button--raised firebaseui-idp-google firebaseui-id-idp-button" data-provider-id="google.com" data-upgraded=",MaterialButton">
                        <span class="firebaseui-idp-icon-wrapper"><img class="firebaseui-idp-icon" alt="" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"></span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-long">CONTINUE WITH GOOGLE</span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-short">Google</span>
                    </button>
                </li>
            </ul>

            <div class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-upgraded" data-upgraded=",MaterialTextfield">
                <div style="margin-bottom: 24px;">
                    <form method="post" onsubmit="loginEmailCheck(); return false;">
                        <div class="form-group">
                            <label class="mdl-textfield__label firebaseui-label" for="email">Email</label>
                            <input onkeydown = "if (event.keyCode == 13)
                                document.getElementById('mailbutton').click()"  name="email" id="log-email" autocomplete="on" class="mdl-textfield__input firebaseui-input firebaseui-id-email" value="" type="email">
                            <div id="login-msg" style="color:#d0021b;display:none;">Don't recognize your email.</div>
                        </div>
                    </form>
                </div>
                <button type="submit" id="mailbutton" onClick="scrollWin();javascript:loginEmailCheck();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 30px;" class="firebaseui-idp-text firebaseui-idp-text-long">CONTINUE WITH EMAIL</span>
                </button>
            </div>
            <div>By creating an account, you accept our <a target="_blank" href="https://www.one20.com/terms/">Terms &amp; Conditions</a> and <a target="_blank" href="https://www.one20.com/privacy/">Privacy Policy</a>.</div>
            
        </div>

        <div id="special-login" style="display:none;text-align: center;">
            <p class="top-content" style="">Good news</p>
            <div class="sub-title" style="">You already have an account with us. Please log in to your <span id="account-type">Google</span> account.</div>
            <ul class="firebaseui-idp-list">
                <li class="firebaseui-list-item" id="facebook">
                    <button onClick="scrollWin();javascript:loginFacebook();" class="firebaseui-idp-button mdl-button mdl-js-button mdl-button--raised firebaseui-idp-facebook firebaseui-id-idp-button" data-provider-id="facebook.com" data-upgraded=",MaterialButton">
				<span class="firebaseui-idp-icon-wrapper">
					<img class="firebaseui-idp-icon" alt="" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/facebook.svg"></span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-long">Continue with Facebook</span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-short">Facebook</span>
                    </button>
                </li>
                <li class="firebaseui-list-item" id="google">
                    <button onClick="scrollWin();javascript:loginGoogle();" class="firebaseui-idp-button mdl-button mdl-js-button mdl-button--raised firebaseui-idp-google firebaseui-id-idp-button" data-provider-id="google.com" data-upgraded=",MaterialButton">
                        <span class="firebaseui-idp-icon-wrapper"><img class="firebaseui-idp-icon" alt="" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"></span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-long">Continue with Google</span>
                        <span class="firebaseui-idp-text firebaseui-idp-text-short">Google</span>
                    </button>
                </li>
            </ul>
        </div>

        <div id="bad-email" style="display:none;text-align: center;">
            <p class="top-content" style="margin-bottom: 0px;">Sorry, we didn't recognize your email.</p>
            <div class="sub-title" style="">Please re-enter your email or create a new account.</div>

            <div style="width: 260px;" class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-upgraded" data-upgraded=",MaterialTextfield">
                <div>
                    <label class="mdl-textfield__label firebaseui-label" for="email">Email</label>
                    <input  name="email" id="bad-email-enter" autocomplete="email" class="mdl-textfield__input firebaseui-input firebaseui-id-email" value="" type="email">
                    <input id="pre-state" type="text" style="display:none" />
                    <div id="invalid-email" style="color:#d0021b;">Please enter a vaild email.</div>
                </div>

                <button id="login-but2" style="position: relative;margin-top: 25px;" onClick="scrollWin();javascript:loginEmailCheck();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 88px;" class="firebaseui-idp-text firebaseui-idp-text-long">LOGIN</span>
                </button>
                <button id="create-btn" style="position: relative;margin-top: 5px;" onClick="scrollWin();javascript:movetosignup();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left:33px;" class="firebaseui-idp-text firebaseui-idp-text-long">CREATE A NEW ACCOUNT</span>
                </button>
            </div>
        </div>

        <div id="facebook-email" style="display:none;text-align: center;">
            <p class="top-content" style="margin-bottom: 0px;">Looks like we need your email address.</p>
            <div class="sub-title" style="">Please re-enter your email or create a new account.</div>

            <div style="width: 260px;" class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-upgraded" data-upgraded=",MaterialTextfield">
                <div class="form-group">
                    <label class="mdl-textfield__label firebaseui-label" for="facebook-email1">Email</label>
                    <input name="email1" id="facebook-email1" autocomplete="off" class="mdl-textfield__input firebaseui-input firebaseui-id-email" value="" type="text" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
                </div>
                <div class="form-group">
                    <label class="mdl-textfield__label firebaseui-label" style="position: relative;" for="facebook-email2">Verify your email</label>
                    <input name="email2" id="facebook-email2" autocomplete="off" class="mdl-textfield__input firebaseui-input firebaseui-id-email" value="" type="text" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
                    <div id="facebook-email-msg" style="display:none;color:#d0021b;">Your emails don't match.</div>
                </div>
                <button id="email-next" style="position:relative;margin-top:25px;display:none;" onClick="scrollWin();javascript:facebookEmailDone();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 88px;" class="firebaseui-idp-text firebaseui-idp-text-long">NEXT</span>
                </button>
                <button id="email-next-fake" style="position:relative;margin-top:25px;display:block;background: rgba(155,155,155,0.60);" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 88px;" class="firebaseui-idp-text firebaseui-idp-text-long">NEXT</span>
                </button>
            </div>
        </div>

        <div id="forgot-pwd" style="display:none;text-align: center;">
            <p class="top-content" style="margin-bottom:0px;">Forgot your password?</p>
            <div class="sub-title" style="">Don't worry! We will send you an email</div>
            <div class="sub-title" style="">that will help you create a new password.</div>

            <div style="width: 260px;" class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-upgraded" data-upgraded=",MaterialTextfield">
                <div>
                    <label class="mdl-textfield__label firebaseui-label" for="email">Email</label>
                    <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('btn-reset-pwd').click()"  name="email" id="confirm-email" autocomplete="email" class="mdl-textfield__input firebaseui-input firebaseui-id-email" value="" type="email">
                    <div id="forgot-pwd-msg" style="color:#d0021b;display:none;">Don't recognize your email.</div>
                </div>

                <button id="btn-reset-pwd" style="position: relative;margin-top: 25px;" onClick="scrollWin();javascript:resetpwd();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 90px;" class="firebaseui-idp-text firebaseui-idp-text-long">SEND</span>
                </button>
            </div>
        </div>

        <div id="pwd-send" style="display:none;text-align: center;">
            <p class="top-content" style="margin-bottom:0px;">Check your email</p>
            <div class="sub-title" style="">We just sent you an email to reset your password.</div>

            <div style="width: 260px;" class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-upgraded" data-upgraded=",MaterialTextfield">
                <div><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/mailicon.png' ?>" />
                </div>
            </div>
        </div>

        <div id="pickusertype"style="display:none;text-align: center;">
            <p class="top-content" style="">What kind of driver are you？</p>
            <div style="width: 257px; text-align: left; margin: 24px auto;">
                <div class="radio radio-yellow">
                    <!-- TOM Fix value -->
                    <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('step3-next').click()" type="radio" name="optionsRadios" id="optionsRadios1" value="INDEPENDENT_OWNER_OPERATOR" onClick="radioButton()">
                    <label for="optionsRadios1" class="common-text">
                        Owner operator
                    </label>
                </div>
                <div class="radio radio-yellow">
                    <!-- TOM Fix value -->
                    <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('step3-next').click()" type="radio" name="optionsRadios" id="optionsRadios2" value="COMPANY_DRIVER" onClick="radioButton()">
                    <label for="optionsRadios2" class="common-text">
                        Company driver
                    </label>
                </div>
                <div class="radio radio-yellow">
                    <!-- TOM Fix value -->
                    <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('step3-next').click()" type="radio" name="optionsRadios" id="optionsRadios3" value="OTHER" onClick="radioButton()">
                    <label for="optionsRadios3" class="common-text">
                        I am not a professional driver
                    </label>
                </div>
            </div>
            <p class="step3Next">
                <button id="step3-next" onClick="scrollWin();javascript:moveToStep4();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 96px;" class="firebaseui-idp-text firebaseui-idp-text-long">NEXT</span>
                </button>
                <button id="step3-next-fake" class="action-button firebaseui-idp-button mdl-button" style="background: rgba(155,155,155,0.60);">
                    <span style="padding-left: 96px;" class="firebaseui-idp-text firebaseui-idp-text-long">NEXT</span>
                </button>
            </p>
        </div>
        <div id="step4"style="display:none;text-align: center;">
            <div class="top-content" style="">Tell us about yourself</div>
            <div style="max-width: 260px;margin: 24px auto;">
                <form>
                    <div class="form-group">
                        <label style="position: unset;" class="mdl-textfield__label firebaseui-label" for="first-name">First name</label>
                        <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('step-done').click()" type="text" class="text-placeholder mdl-textfield__input firebaseui-input firebaseui-id-password form-control" id="first-name" placeholder="John" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label style="position: unset;" class="mdl-textfield__label firebaseui-label" for="last-name">Last name</label>
                        <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('step-done').click()" type="text" class="text-placeholder mdl-textfield__input firebaseui-input firebaseui-id-password form-control" id="last-name" placeholder="Doe"  autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label style="position: unset;" class="mdl-textfield__label firebaseui-label" for="phone-number">Phone number</label>
                        <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('step-done').click()" class="text-placeholder mdl-textfield__input firebaseui-input firebaseui-id-password form-control" id="phone-number" placeholder="555-555-5555 (optional)"  autocomplete="off" type="number" pattern="[0-9]*" maxlength="12">
                    </div>
                    <div class="form-group">
                        <label style="position: unset;" class="mdl-textfield__label firebaseui-label" for="one20-handle">ONE20 Handle</label>
                        <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('step-done').click()" type="text" class="text-placeholder mdl-textfield__input firebaseui-input firebaseui-id-password form-control" id="one20-handle" placeholder="Frenchy"  autocomplete="off" required>
                        <div id="info" style="font-size: 12px;">This is your identity in the ONE20 community</div>
                    </div>
                </form>
            </div>
            <p class="step-done">
                <button id="step-done" onClick="scrollWin();javascript:loginDone();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 93px;" class="firebaseui-idp-text firebaseui-idp-text-long">DONE</span>
                </button>
                <button id="step-done-fake" class="action-button firebaseui-idp-button mdl-button" style="background: rgba(155,155,155,0.60);">
                    <span style="padding-left: 93px;" class="firebaseui-idp-text firebaseui-idp-text-long">DONE</span>
                </button>
            </p>
        </div>

        <div id="add-pwd"style="display:none;text-align:center">
            <div class="top-content" id="add-pwd-title" style="">Add your password</div>
            <div style="max-width: 260px;margin: 0 auto;">
                <form style="margin: 24px 0px;">
                    <div class="form-group">
                        <input type="text" readonly="readonly" class="mdl-textfield__input firebaseui-input firebaseui-id-password form-control" id="new-email" placeholder="" style="display:block;" />
                    </div>
                    <div class="form-group">
                        <label style="position: unset;" class="mdl-textfield__label firebaseui-label" for="password">Password</label>
                        <input  onpropertychange="onpropchanged(event)" type="password" class="mdl-textfield__input firebaseui-input firebaseui-id-password form-control" id="pwd1" autocomplete="on">
                        <div class="info-msg">Minimum of 6 characters</div>
                    </div>
                    <div class="form-group">
                        <label style="position: unset;" class="mdl-textfield__label firebaseui-label" for="confirm-pwd">Confirm password</label>
                        <input onkeydown = "if (event.keyCode == 13)
                        document.getElementById('step-addpwd').click()" type="password" class="mdl-textfield__input firebaseui-input firebaseui-id-password form-control" id="pwd2" autocomplete="on">
                        <span id="pwdnotmatch" class="waring-msg" style="color:#d0021b;display:none">Your passwords don’t match</span>
                    </div>
                </form>
                <p class="step-done">
                    <button style="display:none" id="step-addpwd" onClick="scrollWin();javascript:addpwd();" class="action-button firebaseui-idp-button mdl-button">
                        <span style="padding-left: 87px;" class="firebaseui-idp-text firebaseui-idp-text-long">SIGN UP</span>
                    </button>
                    <button style="display:block;background: rgba(155,155,155,0.60);" id="step-addpwd-fake" class="action-button firebaseui-idp-button mdl-button">
                        <span style="padding-left: 87px;" class="firebaseui-idp-text firebaseui-idp-text-long">SIGN UP</span>
                    </button>
                </p>
                <a style="float: left;" href="/member-login">< Back</a>
            </div>
        </div>

        <div id="mailstep2"style="display:none;text-align:center">
            <div class="top-content" style="">Welcome back!</div>
            <div class="sub-title" style="">Log in using your existing ONE20 password.</div>

            <div  style="margin-top: 24px;" class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-upgraded" data-upgraded=",MaterialTextfield">
                <form onsubmit="loginMail(); return false;">
                    <div class="form-group">
                        <label class="mdl-textfield__label firebaseui-label" for="mailpassword">Password</label>
                        <input type="password" class="mdl-textfield__input firebaseui-input firebaseui-id-password form-control" id="mailpassword" autocomplete="on" onClick="scrollWin();javascript:jQuery('#mailErrorMsg').hide();">
                    </div>
                </form>
                <div id="mailErrorMsg" class="waring-msg" style="display:none">Oops! That password didn't work. Please try again.</div>
            </div>
            <p class="step-done">
                <button id="mail-getpwd" style="" onClick="scrollWin();javascript:loginMail();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 86px;" class="firebaseui-idp-text firebaseui-idp-text-long">SIGN IN</span>
                </button>
            </p>
            <div style="text-align:center;text-align:center;">
                <a><span class="forgot-password" onClick="scrollWin();showforgotpwd();">Forgot password?</span></a>
            </div>

        </div>

        <div id="done" style="display:none;text-align:; max-width: 260px; margin: 0 auto;">
            <div class="top-content" style="">Welcome to ONE20!</div>
            <div class="sub-title" style="">To make the most of your membership, please check your inbox at <span id="verify-email"></span> and verify your email.</div>
            <div class="step-done">
                <button id="step-complete" onClick="scrollWin();javascript:complete();" class="action-button firebaseui-idp-button mdl-button">
                    <span style="padding-left: 47px;" class="action-button firebaseui-idp-text firebaseui-idp-text-long">ACCESS MY BENEFITS</span>
                </button>
                <br/ >
            </div>
        </div>

        <!-- Show errors if there are any -->
		<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
			<?php foreach ( $attributes['errors'] as $error ) : ?>
                <p class="login-error">
					<?php echo $error; ?>
                </p>
			<?php endforeach; ?>
		<?php endif; ?>

        <div class="login-form" style="display:none;">
            <form method="post" id="loginForm" action="<?php echo wp_login_url(); ?>">
                <p class="login-username">
                    <label for="user_login"><?php _e( 'Email', 'personalize-login' ); ?></label>
                    <input type="text" name="log" id="user_login">
                </p>
                <p class="login-password">
                    <label for="user_pass"><?php _e( 'Password', 'personalize-login' ); ?></label>
                    <input type="password" name="pwd" id="user_pass" autocomplete="on">
                </p>
                <p class="login-submit">
                    <input type="submit" style="display:none;vibility:hidden" value="<?php _e( 'Sign In', 'personalize-login' ); ?>">
                </p>
                <input type="hidden" id="user_id">
                <input type="hidden" id="user_name">
                <input type="hidden" id="action" name="action" value="">
                <input type="hidden" id="redirectUrl" name="redirectUrl" value="<?php echo !empty($redirectUrl) ? $redirectUrl  : ""; ?>">
            </form>
        </div>


    </div>
<?php else : ?>
    <div class="login-form">
        <form method="post" id="loginForm" action="<?php echo wp_login_url(); ?>">
            <p class="login-username">
                <label for="user_login"><?php _e( 'Email', 'personalize-login' ); ?></label>
                <input type="text" name="log" id="user_login">
            </p>
            <p class="login-password">
                <label for="user_pass"><?php _e( 'Password', 'personalize-login' ); ?></label>
                <input type="password" name="pwd" id="user_pass" autocomplete="on">
            </p>
            <p class="login-submit">
                <input type="submit" style="display:none;vibility:hidden" value="<?php _e( 'Sign In', 'personalize-login' ); ?>">
            </p>
            <input type="hidden" id="user_id">
            <input type="hidden" id="user_name">
        </form>
    </div>
<?php endif; ?>
<div id="dvLoading" class="loading-image" style="display:none;">
</div>
<script>
    jQuery('#step-addpwd').click(function(){
        $(this).prop('disabled', true);
    });
</script>
<style>

    /*
	.login-form-container input[placeholder], [placeholder], *[placeholder] {
		color: rgba(155,155,155,0.60) !important;
		font-size:12px;
	}
	*/
    /* mozilla专用 */
    .login-form-container .text-placeholder::-moz-placeholder { color:rgba(155,155,155,0.60); }
    .login-form-container .text-placeholder { color: #2c2c2c; }

    .top-content{
        font-size: 28px;
        margin-bottom: 0px;
    }

    .login-form-container .waring-msg {
        color:#d0021b;
        font-size:12px;
    }

    .login-form-container .info-msg {
        color:#9b9b9b;
        font-size:12px;
    }
    label {
        font-size: 12px !important;
    }
    .radio-yellow input[type="radio"] + label::after {
        background-color: #E7BA21; }
    .login-form-container .radio-yellow input[type="radio"]:checked + label::before {
        border-color: #E7BA21; }
    .login-form-container .radio-yellow input[type="radio"]:checked + label::after {
        background-color: #E7BA21; }

    .login-form-container .common-text {
        color: black;
        font-size:14px;
    }

    #pickusertype label {
        font-weight:normal;
        font-size: 16px !Important;
    }

    #btn-signup-mail,
    #mailbutton,
    #step3-next,
    #step-done,
    #mail-getpwd,
    #step-addpwd,
    #step-complete,
    #step-resendmail,
    .action-button {
        background-color: #E7BA21;
    }

    #step-complete{
        margin-top: 50px;
    }

    #pwdnotmatch {
        margin-left: -80px;
    }
    #btn-signup-mail span,
    #mailbutton span,
    #step3-next span,
    #step-done span,
    #mail-getpwd span,
    #step-addpwd span{
        color:black;
    }

    .sub-title{
        font-size: 16px;
        font-weight: 200;
    }

    .firebaseui-idp-button,
    .mdl-textfield__input {
        width: 260px !important;
        max-width: none !important;
    }

    .login-form-container {
        width:100%;
    }

    .firebaseui-textfield {
        width: inherit;
    }


    .forgot-password {
        cursor:pointer;
    }

    .action-button,
    #login-but2,
    #step-complete,
    #step-resendmail {
        background-color:#E7BA21;
    }

    .action-button span{
        color: black;
    }

    .action-button:hover,
    #login-but2:hover,
    .action-button:visited,
    #login-but2:visited{
        background-color: #E7BA21;
    }
    .action-button:focus:not(:active) {
        background-color: #E7BA21;
    }

    .full-width {
        padding: 60px 0;
    }
input[type="radio"]:focus {
    outline: none !important;
    outline-color: transparent !important;
    box-shadow:none !important;
    border:1px solid #ccc !important;
    outline: -webkit-focus-ring-color auto 0px !important;
}
</style>
