<?php
return [
    'greeting' => 'Hello!',
    'salutation' => 'Regards, :team',
    'reset' => [
        'subject' => 'Reset Password Notification',
        'reason' => 'You are receiving this email because we received a password reset request for your account.',
        'action' => 'Reset Password',
        'expire' => 'This password reset link will expire in :count minutes.',
        'wrongRequest' => 'If you did not request a password reset, no further action is required.'
    ],
    'verify' => [
        'subject' => 'Verify Email Address',
        'info' => 'Account information:',
        'login' => 'Login: :login',
        'question' => 'Secret question: :question',
        'answer' => 'Answer: :answer',
        'reason' => 'Please click the button below to verify your email address.',
        'action' => 'Verify Email Address',
        'wrongRequest' => 'If you did not create an account, no further action is required.'
    ]
];