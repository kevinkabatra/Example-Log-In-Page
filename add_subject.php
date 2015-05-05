<?php

/* 
 * Copyright (c) 2015, Kevin Kabatra 
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 * 1. Redistributions of source code must retain the above copyright notice, 
 *    this list of conditions and the following disclaimer. 
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE 
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE.
 */

/* 
 * The code follows the Follow Field Naming Conventions from the 
 * AOSP (Android Open Source Project) Code Style Guidelines for Contributors :
 *     Non-public, non-static field names start with m.
 *     Static field names start with s.
 *     Other fields start with a lower case letter.
 *     Public static final fields (constants) are ALL_CAPS_WITH_UNDERSCORES
 * Hyperlink: (too long for one line)
 *     http://source.android.com/source/code-style
 *     .html#follow-field-naming-conventions
 */

    include_once 'php_data_objects.php';
    include_once '../log/error_logging.php';
    include_once '../security/api_key_generator.php';
    include_once '../security/encryption.php';   
    include_once '../security/get_ip_address.php';
    include_once '../security/php_cookie.php';

    function add_subject_addThisSubject($mFirstName, $mLastName, $mSubject,
            $mPassword) {
        $mDatabaseName = 'databaseExamples';
        $mServerName = 'localhost';
        $mDatabaseUsername = 'databaseExamples';
        $mDatabasePassword = 'ew4pkd8d0bf3e4c6ab';
        
        try {
            $mConnection = connectDatabase($mDatabaseName, $mServerName, 
                    $mDatabaseUsername, $mDatabasePassword);
            if(gettype($mConnection) === 'string') {
                //TODO: create server error response. Unable to create 
                //connection with database
            } else {
                $mConnection->beginTransaction();
                
                $mUserID = api_key_generator_generateApiKey();
                $mSessionID = api_key_generator_generateApiKey();
                $mIpAddress = get_ip_address_getIpAddress();

                //Hash validated $password
                $mHashedPassword = encryption_hashInput($mPassword);
                // Find out where the salt starts. $2y$ = 4. 4 + Cost + 1.            
                $mStart = strrpos($mHashedPassword, "$") + 1;
                $mSalt = substr($mHashedPassword, $mStart, 22);

                //Encrypt hashed $password
                $mEncryptedPassword = encryption_encryptInput($mHashedPassword);

                //Fields and Values to send to database
                $mSubjectPostParameter = array(
                    'userID' => $mUserID,
                    'subjects' => $mSubject,
                    'salt' => $mSalt,
                );
                //Send userID, subject, salt to bizTwoBusiness_subjects
                $mDatabaseInputException = insertOneIntoDatabase($mConnection,
                        'subjects', $mSubjectPostParameter);
                unset($mSubject);
                unset($mSalt);
                unset($mSubjectPostParameter);

                //Fields and Values to send to database
                $mPassword1PostParameter = array(
                    'userID' => $mUserID,
                    'passwords' => substr($mEncryptedPassword, 0, 64),
                );
                //Send password chunk 1 to bizTwoBusiness_passwords1
                $mDatabaseInputException .= insertOneIntoDatabase($mConnection,
                        'passwords1', $mPassword1PostParameter);
                unset($mPassword1PostParameter);

                //Fields and Values to send to database
                $mPassword2PostParameter = array(
                    'userID' => $mUserID,
                    'passwords' => substr($mEncryptedPassword, 64),
                );
                //Send password chunk 2 to bizTwoBusiness_passwords2
                $mDatabaseInputException .= insertOneIntoDatabase($mConnection,
                        'passwords2', $mPassword2PostParameter);
                unset($mEncryptedPassword);
                unset($mPassword2PostParameter);

                //SessionID to send to database
                $mSessionIdPostParameter = array(
                    'userID' => $mUserID,
                    'sessionID' => $mSessionID,
                    'ipAddress' => $mIpAddress,
                );
                //Send sessionID to bizTwoBusiness_activeSessions
                $mDatabaseInputException .= insertOneIntoDatabase($mConnection,
                        'activeSessions', $mSessionIdPostParameter);
                unset($mUserID);
                unset($mIpAddress);
                unset($mSessionIdPostParameter);

                if(empty($mDatabaseInputException)) {
                    $mConnection->commit();
                    //Create session cookie
                    php_cookie_setCookie('sessionID', $mSessionID);
                    unset($mSessionID);
                    $result = 'OK: Subject added.';
                } else {
                    $mConnection->rollBack();
                    $result = 'Error: add_subject.php: ' 
                            . '$databaseInputException was not empty. ' 
                            . $mDatabaseInputException;
                }
            }
        } catch (Exception $ex) {

        
        } catch(PDOException $mPdoException) {
                $result = 'Error: add_subject.php: ' 
                        . $mPdoException;
        } finally {
                unset($mConnection);
                unset($mPdoException);
        }   
        
        //Unset remaining variables in alphabetical order
        unset($mFirstName);
        unset($mLastName);
        
        unset($mDatabaseName);
        unset($mServerName);
        unset($mDatabaseUsername);
        unset($mDatabasePassword);
        
        unset($mDatabaseInputException);
        if(strpos($result, 'Error:') !== FALSE) {
            error_logging_appendErrorLog($result);
        }
        echo $result;
    }
