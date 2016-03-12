<?php

return [

    //------------------------//
    // SYSTEM SETTINGS
    //------------------------//

    /**
     * Registration Needs Activation.
     *
     * If set to true, upon registration, users will have to activate their accounts using email account activation.
     */
    'rna' => false,

    /**
     * Login With Email.
     *
     * If set to true, users will have to login using email/password combo.
     */
    'lwe' => false, 

    /**
     * Force Strong Password.
     *
     * If set to true, users will have to use passwords with strength determined by StrengthValidator.
     */
    'fsp' => false,

    /**
     * Set the password reset token expiration time.
     */
    'user.passwordResetTokenExpire' => 3600,

    /**
     * Set the list of usernames that we do not want to allow to users to take upon registration or profile change.
     */
    'user.spamNames' => 'admin|superadmin|creator|thecreator|username',

    //------------------------//
    // EMAILS
    //------------------------//

    /**
     * Email used in contact form.
     * Users will send you emails to this address.
     */
    'adminEmail' => 'admin@example.com', 

    /**
     * Email used in sign up form, when we are sending email with account activation link.
     * You will send emails to users from this address.
     */
    'supportEmail' => 'support@example.com',
];
