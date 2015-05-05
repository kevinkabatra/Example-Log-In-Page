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
    
    include_once 'sanitize_input.php';
    include_once '../log/error_logging.php';
    
    /**
     * 
     * @return string
     */
    function get_ip_address_getIpAddress() {
        /**
         * FILTER_SANITIZE_NUMBER_FLOAT removes all characters except digits, 
         * +- and optionally .,eE.
         *   
         * The preg_match() function searches a string for pattern, returning
         * true if the pattern exists, and false otherwise. 
         */       
        if(filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP)) {
            //On POST Request clear exceptions from $postInputException
            $mException = "";

            if(empty(filter_input(INPUT_SERVER, 'REMOTE_ADDR',
                    FILTER_VALIDATE_IP))) {
                $mException .= 'Error: get_ip_address.php: ' 
                        . 'IP Address failed filter_input().';
            } else {
                $mIpAddress = sanitize_input(filter_var(
                        filter_input(INPUT_SERVER, 'REMOTE_ADDR',
                        FILTER_VALIDATE_IP), FILTER_SANITIZE_NUMBER_FLOAT,
                        FILTER_FLAG_ALLOW_FRACTION));
                if(!preg_match("/^[0-9.:]*$/",$mIpAddress)) {
                    $mException .= 'Error: get_ip_address.php: ' 
                            . 'IP Address failed preg_match()';
                }

                if($mException != "") {
                    error_logging_appendErrorLog($mException);
                } else {
                    $mIpAddress = 'get_ip_address.php:' . $mIpAddress;
                    return $mIpAddress;
                }
            }
        }
    }
