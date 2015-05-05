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
   
    include_once '../database tools/add_subject.php';
    include_once '../log/error_logging.php';      
    include_once '../security/get_active_session.php';   
    include_once '../security/get_subject.php';
    include_once '../security/get_ip_address.php';
    include_once '../security/php_cookie.php';    
    include_once '../security/remove_active_session.php';
    include_once '../security/sanitize_input.php';
    include_once '../security/update_session_id.php';

    //Define and set variables
    $postParameter = '';
    
    /**
     * FILTER_SANITIZE_URL Remove all characters except letters, digits and 
     * $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=.
     */       
    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD', 
            FILTER_SANITIZE_URL) === "POST") {
        //On POST Request clear exceptions from $postInputException
        $postInputException = "";
        if(empty(filter_input(INPUT_POST, 'postParameter',
                FILTER_SANITIZE_URL))) {
        } else {
            $postParameter = sanitize_input(filter_input(INPUT_POST,
                    'postParameter', FILTER_SANITIZE_URL));

            /*
             * The preg_match() function searches a string for pattern, 
             * returning true if the pattern exists, and false otherwise. 
             * 
             * NEVER Allow: Due to Cross Site Scripting 
             *  Misc: 
             *      &: can be used for ASCII codes: &#60 (<) or &lt (<).
             *      <: can be used to complete html, xml, php tags
             * Allow:
             *  Alphabet: a-zA-Z
             *  Numbers: 0-9
             *  Braces: []: for defining array bounds
             *  Misc: 
             *      =: for key/value pair symbol =>
             *      >: for key/value pair symbol =>
             *      ': for defining key and value =  
             *      ,: for delimiter between key/value pairs
             *      _: for file names
             */
            if(!preg_match("/^[a-zA-Z0-9[\]=>,\'_ ]*$/",
                    $postParameter)) {               
                $postInputException .= 'Error: php_cookie.php: ' 
                        . 'Error: $postParameter failed preg_match.' 
                        . $postParameter;
            }
        }                
        
        if($postInputException != "") {
            error_logging_appendErrorLog($postInputException);
        } else {
            //declare and set array, to be used for POST parameters
            $arrayPostParameter = null;
            /*
             * Check to see if there are remaining key/value pairs.
             * String must be formatted as follows:
             *     *note - Each key/value pair must end with the delimeter ','
             *     var postParameter = '[' 
             *        + '\'foo\'=>\'bar\','
             *        + '\'baz\'=>\'qux\','
             *        + '\'quux\'=>\'corge\','
             *        + ']';
             */
            while(strpos($postParameter,'=>') !== false) {
                //Find $key
                $intBeginKeyPosition = strpos($postParameter, '\'') + 1;
                $intEndKeyPosition = strpos($postParameter, '=>') - 1;
                $intKeyLength = $intEndKeyPosition - $intBeginKeyPosition;
                $key = substr($postParameter, $intBeginKeyPosition, 
                        $intKeyLength);
                
                //Remove $key from $postParameter
                $postParameter = substr($postParameter, $intEndKeyPosition + 1); 
                //Find $value
                $intBeginValuePosition = strpos($postParameter, '\'') + 1;
                $intEndValuePosition = strpos($postParameter, ',') - 1;
                $intValueLength = $intEndValuePosition - $intBeginValuePosition;
                $value = substr($postParameter, $intBeginValuePosition, 
                        $intValueLength);
            
                //Remove $value from $postParameter
                $postParameter = substr($postParameter, $intEndValuePosition 
                        + 1);
                
                //Add $key and $value to $arrayPostParameter
                $arrayPostParameter[$key] = $value;                                
            }
            
            //Cases ordered alphabetically
            switch ($arrayPostParameter['function']) {
                case 'add_subject_addThisSubject':
                    echo add_subject_addThisSubject(
                            $arrayPostParameter['firstName'],
                            $arrayPostParameter['lastName'],
                            $arrayPostParameter['subject'],
                            $arrayPostParameter['password']);
                    break;
                case 'get_active_session_getThisActiveSession':
                    echo get_active_session_getThisActiveSession(
                            $arrayPostParameter['sessionID']);
                    break;
                case 'get_ip_address_getIpAddress':
                    echo get_ip_address_getIpAddress();
                    break;
                case 'get_subject_getThisSubject':
                    echo get_subject_getThisSubject(
                            $arrayPostParameter['userID']);
                    break;
                case 'get_user_id_getThisUserId':
                    echo get_user_id_getThisUserId(
                        $arrayPostParameter['sessionID']);
                    break;
                case 'remove_active_session_removeThisSession':
                    echo remove_active_session_removeThisSession(
                            $arrayPostParameter['sessionID']);
                    break;
                case 'php_cookie_deleteCookie':
                    echo php_cookie_deleteCookie(
                            $arrayPostParameter['cookie_name']);
                    break;
                case 'php_cookie_getCookie':
                    echo php_cookie_getCookie(
                            $arrayPostParameter['cookie_name']);
                    break;
                case 'update_session_id_updateThisSessionId':                    
                    echo update_session_id_updateThisSessionId(
                            $arrayPostParameter['userID']);
                    break;
                default: break;
            }
        }
    }
