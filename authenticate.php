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

    include_once '../database tools/php_data_objects.php';
    include_once '../log/error_logging.php';
    include_once '../security/encryption.php'; 
    
    function authenticate_authenticateSubject($mSubject, $mPassword) {
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
                $mTableNameSubjects = 'subjects';
                $mPostParameterSubjects = array(
                    'subjects' => $mSubject,
                );
                $mSelectedSubjectId = selectOneFromDatabase($mConnection,
                        $mTableNameSubjects, $mPostParameterSubjects);
                unset($mTableNameSubjects);
                unset($mPostParameterSubjects);
                
                foreach($mSelectedSubjectId as $mKeySalt =>
                        $mValueSalt) {
                    if($mKeySalt === 'salt') {
                        $mSalt = $mValueSalt;
                    }
                    unset($mKeySalt);
                    unset($mValueSalt);
                }
                
                //Hash validated $password
                $mPassword = authenticate_hashInput($mPassword, $mSalt);
                unset($mSalt);
                //Encrypt hashed $password
                $mPassword = encryption_encryptInput($mPassword);
                
                //Find id's password #1
                $mTableNamePasswords1 = 'passwords1';
                foreach($mSelectedSubjectId as $mKeyUserId =>
                        $mValueUserId) {
                    if($mKeyUserId === 'userID') {
                        $mPostParameterPasswords1 = array(                        
                            $mKeyUserId => $mValueUserId,
                        );
                    }
                }
                $mSelectedPassword1 = selectOneFromDatabase($mConnection,
                        $mTableNamePasswords1, $mPostParameterPasswords1);
                unset($mTableNamePasswords1);
                unset($mPostParameterPasswords1);
                
                //Find id's password #2
                $mTableNamePasswords2 = 'passwords2';
                foreach($mSelectedSubjectId as $mKeyUserId =>
                        $mValueUserId) {
                    if($mKeyUserId === 'userID') {
                        $mPostParameterPasswords2 = array(                        
                            $mKeyUserId => $mValueUserId,
                        );
                    }
                }
                $mSelectedPassword2 = selectOneFromDatabase($mConnection,
                        $mTableNamePasswords2, $mPostParameterPasswords2);
                unset($mTableNamePasswords2);
                unset($mPostParameterPasswords2);
                
                foreach($mSelectedPassword1 as $mKeyPasswords => 
                        $mValuePasswords) {
                    if($mKeyPasswords === 'passwords') {
                        $mStoredPassword = $mValuePasswords;
                    }
                }
                unset($mSelectedPassword1);
                
                foreach($mSelectedPassword2 as $mKeyPasswords => 
                        $mValuePasswords) {
                        if($mKeyPasswords === 'passwords') {
                            $mStoredPassword .= $mValuePasswords;
                        }
                }
                unset($mSelectedPassword2);
                
                if($mPassword === $mStoredPassword) {
                    foreach ($mSelectedSubjectId as $mKeyUserId =>
                            $mValueUserId) {
                        if ($mKeyUserId === 'userID') {
                            $userID = $mValueUserId;
                        }
                        unset($mKeyUserId);
                        unset($mValueUserId);
                    }
                    $result = 'authenticate.php:' . $userID;
                } else {
                    $result = 'ERROR: User not authenticated';
                }
                unset($mPassword);
                unset($mStoredPassword);
                unset($mSelectedSubjectId);
                unset($userID);
            }
        } catch(PDOException $pdoException) {
            return handlePdoExceptions($pdoException->getMessage());
        } finally {
            unset($mDatabaseName);
            unset($mServerName);
            unset($mDatabaseUsername);
            unset($mDatabasePassword);
            unset($mConnection);

            echo $result;
        }
    }
    
    //
    /**
     * The password_hash() function generates a new password hash using a strong
     * one-way hashing algorithm. PASSWORD_BCRYPT uses the CRYPT_BLOWFISH 
     * algorithm to create the hash. This will produce a standard crypt() 
     * compatible hash using the "$2y$" identifier. The result will always be a 
     * 60 character string, or FALSE on failure. The used algorithm, cost and 
     * salt are returned as part of the hash. Therefore, all information that's 
     * needed to verify the hash is included in it. This allows the 
     * password_verify() function to verify the hash without needing separate 
     * storage for the salt or algorithm information.
     * 
     * @param {string} $mPassword - String variable that was passed
     * @param {string} $mSalt - String variable that was passed
     * @returns {string}
     */
    function authenticate_hashInput($mPassword, $mSalt) {
        $bcrypt_options = [
            'cost' =>12,
            'salt' => $mSalt,
        ];
        $mPassword = password_hash($mPassword,
                PASSWORD_BCRYPT, $bcrypt_options);
        return $mPassword;
    }
