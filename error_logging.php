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
  
    include_once '../security/get_ip_address.php';
    
    /**
     * Appends new error messages to message log.
     * <p>
     * If file cannot be found, fopen() will create the file.
     * <p>
     * @param string $mErrorMessage String containing an error message.
     */
    function error_logging_appendErrorLog($mErrorMessage) {
        //Open error log file.
        $mErrorLog = fopen('/var/www/html/examples/example log in/log/' 
                + 'error.log', 'a');
        /*
         * Find current milliseconds, PHP documentation incorrectly states that
         * DateTime supports milliseconds. DateTime always returns 000000.
         */
        $mMicroTime = microtime(true);
        $mSeconds = floor($mMicroTime);
        $mMilliseconds = round(($mMicroTime - $mSeconds) * 1000000);        
        //Find date and time.
        $mDateTime = '[date => ' . date('D M d H:i:s.' . $mMilliseconds 
                . ' Y]');
        //Find client IP Address.
        $mClientIP = '[client => ' . get_ip_address_getIpAddress() . ']';
        //Append strings together.
        $mErrorMessage = $mDateTime . ' ' . $mClientIP . ' ' . $mErrorMessage 
                . "\n";
        //Write to file.
        fwrite($mErrorLog, $mErrorMessage);
        //Close file.
        fclose($mErrorLog);
    }
