
<?php
/**
 * Template Name: Member - Profile Page
 */


get_header(); ?>

<script>
    // firebase config
    var homeUrl = "<?php echo home_url()?>";
    var env = "QA";
    if (homeUrl === "https://one20.com") {
        env = "Prod";
    }
    var config = [];
    var ProfileURL = []; // add var to hold URL - TOMM

    var profileObject = {};
    var userToken = "";
    var signinProvider = "";

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

    if (firebase.apps.length === 0) {
        debugPrint('fire base is Inactive');
    } else {
        debugPrint('fire base is Active');
    }

    firebase.auth().onAuthStateChanged(function(currentUser) {
        if (currentUser) {
            debugPrint('User is logged in');
            currentUser.getIdToken().then(function(token) {
                debugPrint(token);
                // Get some info
                try {
                    // For a new account, a message will be returned by the API
                    // We should return null value for this case.
                    debugPrint('Start AJAX call...');
                    debugPrint(ProfileURL[env]);
                    var returnObject = jQuery.ajax({
                        url: ProfileURL[env],
                        type: 'GET',
                        async: false,
                        cache: false,
                        headers: { "access-token":token }
                    });

                    userToken = token;

                    profileObject = returnObject["responseJSON"];
                    fillInProfileAttributes();

                    var user = firebase.auth().currentUser;
                    checkSignInProvider(user);


                } catch ( error) {
                    debugPrint("getUserProfile AJAX return " + error);
                    throw {code:"profile/get-profile-faild", message:error};
                }
            });
        }
    });

    function updateUserProfile() {
        firebase.auth().onAuthStateChanged(function(currentUser) {
            if (currentUser) {
                currentUser.getIdToken().then(function (token) {
                    var data = {
                        "driverType": jQuery('#your_role').val(),
                        "firstName": jQuery('#first_name').val(),
                        "lastName": jQuery('#last_name').val(),
                        "phoneNumber": jQuery('#phone').val()
                    };

                    debugPrint(data);

                    if ( jQuery('#first_name').val() == '' || jQuery('#last_name').val() == '' ) {
                        // show error
                        jQuery('#error-first-last').show();
                        return false;
                    } else {
                        jQuery.ajax({
                            url: ProfileURL[env],
                            type: 'PUT',
                            data: JSON.stringify(data),
                            async: false,
                            cache: false,
                            headers: {"access-token": token, "Content-Type": "application/json"},
                            success: function( response ) {
                                jQuery("#infoSaved").show();
                                debugPrint(JSON.stringify(response));

                                // Segment identify event
                                window.dataLayer.push({
                                    "event": "event",
                                    "eventName": "profileUpdated",
                                    "identify": "true",
                                    "user": {
                                        "firstName": data["firstName"],
                                        "lastName": data["lastName"],
                                        "phoneNumber": data["phoneNumber"],
                                        "driverType": data["driverType"]
                                    }
                                });

                                location.reload();
                            },
                            error: function(error){
                                debugPrint(error);
                                throw { code:'profile/updateUserProfile failed', message:'none'};
                            }
                        });
                    }

                });
            }
        });
    }

    function fillInProfileAttributes() {
        debugPrint(profileObject);

        jQuery('#first_name').val(profileObject["firstName"]);
        jQuery('#last_name').val(profileObject["lastName"]);
        jQuery('#phone').val(profileObject["phoneNumber"]);
        jQuery('#email').val(profileObject["email"]);
        jQuery('#your_role').val(profileObject["driverType"]);
    }

    function moveToPassword() {
        jQuery("#my-profile-about-me").hide();
        jQuery("#my-profile-account-security").show();
    }

    function verifyOldPassword() {

        const current_password = jQuery('#old_password').val();

        const user = firebase.auth().currentUser;
        const credential = firebase.auth.EmailAuthProvider.credential(
            user.email,
            current_password
        );

        user.reauthenticateWithCredential(credential)
            .then(function() {
                debugPrint("Old password verified");
                checkPassword();
            })
            .catch(function(error) {
                // Error Handling
                console.error('Error checking old password');
                console.error(error.message);
                document.getElementById("err").innerHTML = "Please re-enter your old password";
            });
        return false;
    }

    function checkPassword() {

        var user = firebase.auth().currentUser;
        var newPassword = jQuery('#new_password').val();
        var newPassword2 = jQuery('#new_password2').val();

        // set new password
        if ( newPassword !== '' && newPassword === newPassword2 ) {
            user.updatePassword(newPassword).then(function() {
                // Update successful.
                jQuery("#my-profile-account-security").hide();
                jQuery("#password-saved").show();
                debugPrint('password changed');

                jQuery("#changePasswordForm").submit();
            }).catch(function(error) {
                // An error happened.
                debugPrint('erro: password not changed');
            });
        } else {
            document.getElementById("err-passwords").innerHTML = "New passwords cannot be blank and must match";
            debugPrint("New password cannot be blank and passwords have to match");
        }
    }

    function checkSignInProvider(user) {
        if (user != null) {
            user.providerData.forEach(function (profile) {
                signinProvider = profile.providerId;
            });
        }
        switch (signinProvider) {
            case "password":
                break;
            default:
                jQuery("#old_password").prop('disabled', true);
                jQuery("#new_password").prop('disabled', true);
                jQuery("#new_password2").prop('disabled', true);
                jQuery("#changePassword").prop('disabled', true);
                break;
        }
    }

    function debugPrint(statement) {
        if (env !== "Prod") {
            console.log(statement);
        }
    }

</script>


<div class="my-profile">
    <div class="wrapper">
        <div class="grid">
            <!-- my profile -->
            <div class="col-3 sm-hidden"></div>
            <div class="col-6_sm-12">
                <div class="">
                        <div id="my-profile-about-me">
                            <form id="myProfileForm" method="post" onsubmit="updateUserProfile();return false;">
                                <h1>MY PROFILE</h1>
                                <p>Manage your ONE20 member profile. This is used across all ONE20 products and services.</p>
                                <div id="info">
                                    <span class="form-head" style="display:block;font-weight:700;text-transform: uppercase;padding: 15px 0;">About Me</span>
                                    <div id="error-first-last" style="display:none;color: red;">First and Last name cannot be empty.</div>
                                    <label for="first_name">First Name</label>
                                    <input id="first_name" maxlength="40" name="first_name" size="20" type="text">
                                    <label for="last_name">Last Name</label>
                                    <input id="last_name" maxlength="40" name="last_name" size="20" type="text">
                                    <label for="phone">Phone Number</label>
                                    <input id="phone" maxlength="40" name="phone" size="20" type="text">
                                    <label for="email">Email</label>
                                    <input id="email" class="disabled-field" maxlength="40" name="email" size="20" type="text" disabled>
                                    <label for="type_of_driver">Type of Driver</label>
                                    <select id="your_role" title="Your Role" name="00N3600000RmtfC">
                                        <option value="">–None–</option>
                                        <option value="INDEPENDENT_OWNER_OPERATOR">Owner Operator</option>
                                        <option value="COMPANY_DRIVER">Company Driver</option>
                                        <option value="OTHER">I am not a professional driver</option>
                                    </select>
                                    <input name="submit" type="submit" value="Save" data-event-category="internal_link" data-event-action="update_profile" style="display:inline-block">
                                    <span id="infoSaved" style="display:none;padding:0 10px;">Changes saved!</span>
                                    <div style="clear:both;"></div>
                                </div>
                            </form>
                        </div><!-- #my-profile-about-me -->
                        <span class="form-head" style="display:block;font-weight:700;text-transform: uppercase;padding: 15px 0;">Account Security</span>
                        <a href="#" style="margin: 0 0 1em;display: inline-block;" onclick="javascript:moveToPassword();">Change Password</a>
                        <div id="password-saved" style="display:none">
                            <p>Your password has been updated</p>
                        </div>
                        <div id="my-profile-account-security" style="display:none">
                            <form id="changePasswordForm" method="post">
                                <label for="old_password">Old Password</label>
                                <div id="err" class="error-message"></div>
                                <input id="old_password" maxlength="40" name="old_password" size="20" type="password">
                                <label for="new_password">New Password</label>
                                <div id="err-passwords" class="error-message"></div>
                                <input id="new_password" maxlength="40" name="new_password" size="20" type="password">
                                <label for="new_password2">Re-Enter Password</label>
                                <input id="new_password2" maxlength="40" name="new_password2" size="20" type="password">
                                <input id="changePassword" type="button" value="Change Password" onclick="javascript:verifyOldPassword();" data-event-category="internal_link" data-event-action="change_password">
                            </form>
                            <a style="float: left;" href="/my-profile">&lt; Back</a>
                        </div><!-- #my-profile-account-security -->

                    </form>
                </div><!-- / center-column-->
            </div><!-- / my-profile-->
            <div class="col-3 sm-hidden"></div>
        </div><!-- .grid -->
    </div><!-- .wrapper -->
</div>

<?php get_footer();
