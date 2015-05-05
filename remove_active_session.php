<?php

/* 
 * Copyright (c) 2015, Kevin Kabatra <kevinkabatra@gmail.com>
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

    //Select record that contains specified sessionID
    function remove_active_session_removeThisSession($mSessionID) {
        //Define variables for database login
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
                $mTableName = 'activeSessions';
                $mPostParameter = array(
                    'sessionID' => $mSessionID,
                );

                deleteOneFromDatabase($mConnection, $mTableName,
                        $mPostParameter);

                return 'remove_active_session_removeThisSession: ' 
                        . 'subject logged out';
            }
        } catch(PDOException $mPdoException) {
            error_logging_appendErrorLog($mPdoException->getMessage());
        } finally {
            unset($mConnection);
        }
    }
