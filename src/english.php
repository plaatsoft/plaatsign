<?php

/* 
**  ==========
**  PlaatSign
**  ==========
**
**  Created by wplaat
**
**  For more information visit the following website.
**  Website : www.plaatsoft.nl 
**
**  Or send an email to the following address.
**  Email   : info@plaatsoft.nl
**
**  All copyrights reserved (c) 2008-2016 PlaatSoft
*/

/*
** ------------------
** LANGUAGES
** ------------------
*/

$lang['LANGUAGE_0'] = 'English';
$lang['LANGUAGE_1'] = 'Dutch';
 
/*
** ------------------
** TYPE
** ------------------
*/

$lang['TYPE_0'] = 'Images';
$lang['TYPE_1'] = 'Videos';
$lang['TYPE_2'] = 'Scripts';

/*
** ------------------
** ROLES
** ------------------
*/

$lang['ROLE_0'] = 'User';
$lang['ROLE_1'] = 'Admin';

/*
** ------------------
** LINKS
** ------------------
*/

$lang['LINK_LOGIN']  = 'Login'; 
$lang['LINK_HOME']  = 'Home'; 
$lang['LINK_CONTENT']  = 'Content'; 
$lang['LINK_MANUAL']  = 'Manual'; 
$lang['LINK_SCRIPTS']  = 'Scripts';
$lang['LINK_IMAGES']  = 'Images'; 
$lang['LINK_MOVIES']  = 'Videos'; 
$lang['LINK_SETTINGS'] = 'Settings'; 
$lang['LINK_HELP'] = 'Help'; 
$lang['LINK_LOGOUT']  = 'Logout';
 
$lang['LINK_GENERAL'] = 'General';
$lang['LINK_USERS']  = 'Users';
$lang['LINK_EDIT'] = 'Edit';
$lang['LINK_VIEW'] = 'View';
$lang['LINK_SAVE'] = 'Save';
$lang['LINK_DELETE'] = 'Delete'; 
$lang['LINK_CANCEL']  = 'Cancel';
$lang['LINK_INSTRUCTIONS']  = 'Instructions';
$lang['LINK_RELEASE_NOTES'] = 'Release Notes';
$lang['LINK_CREDITS']  = 'Credits';
$lang['LINK_DONATE']  = 'Donate';
$lang['LINK_ABOUT']  = 'About';
$lang['LINK_OK']  = 'Ok';
$lang['LINK_ADD']  = 'Add';
$lang['LINK_INSERT']  = 'Insert';
$lang['LINK_UPLOAD']  = 'Upload';

/*
** ------------------
** GENERAL
** ------------------
*/

$lang['GENERAL_REQUIRED_FIELD'] = '* Required field'; 

$lang['GENERAL_USERNAME'] = 'Username'; 
$lang['GENERAL_PASSWORD'] = 'Password'; 
$lang['GENERAL_NAME'] = 'Name';
$lang['GENERAL_EMAIL'] = 'Email';
$lang['GENERAL_UID'] = 'UID';
$lang['GENERAL_LAST_ACTIVITY'] = 'Last Activity';
$lang['GENERAL_REQUESTS'] = 'Requests';
$lang['GENERAL_ACTION' ] = 'Action';
$lang['GENERAL_CID'] = 'CID';
$lang['GENERAL_FILENAME'] = 'Filename';
$lang['GENERAL_FILESIZE'] = 'Filesize';
$lang['GENERAL_CREATED'] = 'Created';
$lang['GENERAL_ENABLED'] = 'Enabled';
$lang['GENERAL_ROLE'] = 'Role';
$lang['GENERAL_LANGUAGE'] = 'Language';
$lang['GENERAL_IMAGE'] = 'Image';
$lang['GENERAL_CONTENT'] = 'Content';
$lang['GENERAL_OWNER'] = 'Owner';
$lang['GENERAL_TIMEZONE'] = 'Timezone';
$lang['GENERAL_TYPE'] = 'Type';
$lang['GENERAL_REFRESH'] = 'Refresh every';

$lang['GENERAL_INFO'] = 'INFO';
$lang['GENERAL_WARNING'] = 'WARNING';
$lang['GENERAL_DEBUG'] = 'DEBUG';

$lang['COPYRIGHT'] = 'All Copyright Reserved &copy; <a class="normal_link" href="http://www.plaatsoft.nl/">PlaatSoft</a> 2008 - ' . date("Y");

/*
** ------------------
**  LOGIN
** ------------------
*/

$lang ['CONGIG_BAD' ] = 'The following file "config.php" is missing in installation directory.<br/><br/>
Rename config.php.sample to config.php, update the database settings en press F5 in your browser!';

$lang['DATABASE_CONNECTION_FAILED' ] = 'The connection to the database failed. Please check if config.php settings!';

$lang['LOGIN_TITLE'] = 'LOGIN';

$lang['LOGIN_WELCOME_TITLE'] = 'WELCOME';

$lang['LOGIN_WELCOME'] = '
<p><b>PlaatSign</b> is an digital content viewer for the Raspberry Pi.</p></p>With this product you can managed digital content 
(images, videos and dynamic content scripts) remotely. The content is realtime displayed on an attached monitor.</p>
<p><b>PlaatSign</b> is created by <a target="new" href="http://www.plaatsoft.nl">PlaatSoft</a>. PlaatSoft is a 
small christian non profit organization with as mission to created high quality software. This 
software is open source and may be copied, distributed or modified under the terms of the GNU General Public License (GPL) 
Version 3.</p><p>If you are interresed or have any questions please writing an email to 
<a href="mailto:info@plaatsoft.nl"/>info@plaatsoft.nl</a></p>';

$lang['LOGIN_FAILED'] = 'Login failed!';
$lang['LOGIN_LOGOUT'] = 'You have logout!';

$lang['LOGIN_REGISTER'] = 'REGISTER';
$lang['LOGIN_USERNAME_EXIST'] = 'Username already exist!'; 
$lang['LOGIN_USERNAME_TO_SHORT'] = 'Username to short! Minimal length is 5'; 
$lang['LOGIN_PASSWORD_TO_SHORT'] = 'Password to short! Minimal length is 5';
$lang['LOGIN_NAME_TO_SHORT'] = 'Name to short! Minimal length is 3'; 
$lang['LOGIN_EMAIL_INVALID' ] = 'Email address is not valid';

$lang['LOGIN_RECOVER'] = 'RECOVER';

/*
** ------------------
**  HOME
** ------------------
*/

$lang['HOME_TITLE'] = 'HOME';
$lang['HOME_CONTENT'] = 'The attached monitor display the following content.';

/*
** ------------------
** CONTENT
** ------------------
*/

$lang['CONTENT_TITLE'] = 'CONTENT';
$lang['CONTENT_MINUTES'] = 'minutes';

$lang['CONTENT_ADDED'] = 'Content added';
$lang['CONTENT_UPDATED'] = 'Content updated';
$lang['CONTENT_DELETED'] = 'Content deleted';

$lang['CONTENT_FILE_NOT_FOUND'] = 'File not found, upload aborted!';
$lang['CONTENT_ALREADY_EXIST'] = 'Content (filename) already exist, upload aborted!';
$lang['CONTENT_TO_LARGE'] = 'File is to large. Maximum file is %s';

$lang['CONTENT_TYPE_NOT_SUPPORTED_0'] = 'Sorry, only JPG, JPEG, PNG or GIF file format is allowed, upload aborted!';
$lang['CONTENT_REMARK_0'] = '* Only JPG, PNG & GIF file format is supported and maximum filesize is %s';
$lang['CONTENT_TYPE_NOT_SUPPORTED_1'] = 'Sorry, only MP4 file format is allowed, upload aborted!';
$lang['CONTENT_REMARK_1'] = '* Only MP4 file format is supported and maximum filesize is %s';
$lang['CONTENT_TYPE_NOT_SUPPORTED_2'] = 'Sorry, only PHP file format is allowed, upload aborted!';
$lang['CONTENT_REMARK_2'] = '* Only PHP file format is supported and maximum filesize is %s';

/*
** ------------------
** SETTINGS - GENERAL
** ------------------
*/

$lang['SETTINGS_TITLE'] = 'SETTINGS';

$lang['SETTINGS_CONTENT'] = 'The page contain an overview of settings of PlaatSign.';

$lang['SLIDE_SHOW_DELAY'] = 'Slide delay:';
$lang['SLIDE_SECONDS'] = 'seconds';

$lang['SETTING_UPDATED'] = 'Setting items updated!';

$lang['SETTING_TIMER_NO_NUMBER'] = 'Timer setting must contain number!';
$lang['SETTING_TIMER_TOO_LOW'] = 'Timer setting value must be atleast one second!';

/*
** ------------------
** SETTINGS - USER
** ------------------
*/

$lang['USERS_TITLE'] = 'USERS';
$lang['USER_TITLE'] = 'USER';

$lang['USER_GENERAL'] = 'General';
$lang['USER_TEXT'] = 'Currently the following users are available:';
$lang['USER_UPDATED'] = 'User updated!';
$lang['USER_ADDED'] = 'User added!';
$lang['USER_FAILED'] = 'User data failed!';
$lang['USER_DELETED'] = 'User deleted';
$lang['USER_DELETE_CONFIRM'] = 'Are you sure?';

$lang['USER_USERNAME_EXIST'] = 'Username already exist!'; 
$lang['USER_USERNAME_TO_SHORT'] = 'Username to short! Minimal length is 5'; 
$lang['USER_PASSWORD_TO_SHORT'] = 'Password to short! Minimal length is 8';
$lang['USER_NAME_TO_SHORT'] = 'Name to short! Minimal length is 3'; 
$lang['USER_EMAIL_INVALID' ] = 'Email address is not valid';

$lang['USER_EMAIL_VALID1'] = 'Your email address is validated!';
$lang['USER_EMAIL_VALID2'] = 'This email address is validated!';
$lang['USER_EMAIL_CONFIRM_SENT'] = 'Confirm email sent!'; 
$lang['USER_EMAIL_CONFIRM_NEEDED'] = 'Please confirm your email address by clicking %s!';

$lang['USER_HACK'] = 'Take over user %s [%s] session!';

/*
** ------------------
** HELP - MANUAL
** ------------------
*/

$lang['MANUAL_TITLE'] = 'MANUAL';

$lang['MANUAL_CONTENT'] = '

<h2>Instruction</h2>
<p>
This website is self explaining. So no instruction is added.
</p>

<h2>Remark</h2>
<p>
Do not forget to change the default admin password after the installation!
</p>';

/*
** ------------------
** HELP - RELEASE NOTES
** ------------------
*/

$lang['RELEASENOTES_TITLE'] = 'RELEASE NOTES';

/*
** ------------------
** HELP - CREDITS
** ------------------
*/

$lang['CREDITS_TITLE'] = 'CREDITS';

$lang['CREDITS_CONTENT'] = '

<p>
Many thanks to the following people who helped creating <b>PlaatSign</b>.
</p>

<h2>Software Architect</h2>
<p>
<ul>
<li>wplaat</li>
</ul>
</p>

<h2>Software Developers</h2>
<p>
<ul>
<li>wplaat</li>
<li>bplaat</li>
</ul>
</p>

<h2>Software Testers</h2>
<p>
<ul>
<li>wplaat</li>
<li>lplaat</li>
<li>golbertijn</li>
<li>mwind</li>
<li>pbrink</li>
</ul>
</p>

<h2>Others</h2>
<p>
<ul>
<li>and of course all other users who sent great feedback to improve this application</li>
</ul>
</p>';

/*
** ------------------
** DONATE
** ------------------
*/

$lang['DONATE_TITLE'] = 'DONATE';

$lang['DONATE_SUBTITLE1'] = 'General';
$lang['DONATE_CONTENT1'] = 'PlaatSign may be used free of charge, but if you wish to express your appreciation 
for the time and resources the author spent developing and supporting it over the years, we do accept and 
appreciate donations.';

$lang['DONATE_SUBTITLE2'] = 'PayPal';
$lang['DONATE_CONTENT2'] = 'To make a donation online using your credit card, or PayPal account, click 
below and enter the amount you would like to contribute. Your credit card will be processed by PayPal, 
a trusted name in secure online transactions. Many thanks for your support.';


/*
** ------------------
** ABOUT
** ------------------
*/

$lang['ABOUT_TITLE'] = 'ABOUT';
$lang['ABOUT_CONTENT'] = '

<h2>Background</h2>
<p>
PlaatSign is created by <a target="new" href="http://www.plaatsoft.nl">PlaatSoft</a>. PlaatSoft is a small christian 
non profit organization with as mission to created high quality software. All created software is open source and 
may be copied, distributed or modified under the terms of the GNU General Public License (GPL) Version 3. If you want 
more information about us, please send an email to info@plaatsoft.nl
</p>

<h2>Disclaimer</h2>
<p>
The program is provided AS IT IS with NO WARRANTY OF ANY KIND, INCLUDING THE WARRANTY OF DESIGN,
MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
</p>

<h2>License</h2>
<p>
This program is free software: you can redistribute it and/or modify it under the terms of the GNU 
General Public License as published by the Free Software Foundation, either version 3 of the License, 
or (at your option) any later version.
</p>

<p>
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU 
General Public License for more details.
</p>

<p>
You should have received a copy of the GNU General Public License along with this program. If not, 
see http://www.gnu.org/licenses/.
</p>';

/*
** ------------------
** THE END
** ------------------
*/

?>