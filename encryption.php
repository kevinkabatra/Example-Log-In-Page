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
        
    /**
     * Generates a hash value (message digest).
     * <p>    
     * The password_hash() function generates a new password hash using a strong
     * one-way hashing algorithm. PASSWORD_BCRYPT uses the CRYPT_BLOWFISH 
     * algorithm to create the hash. This will produce a standard crypt() 
     * compatible hash using the "$2y$" identifier. The result will always be a 
     * 60 character string, or FALSE on failure. The used algorithm, cost and 
     * salt are returned as part of the hash. Therefore, all information that's 
     * needed to verify the hash is included in it. This allows the 
     * password_verify() function to verify the hash without needing separate 
     * storage for the salt or algorithm information.
     * <p>
     * Example code:
     *      $string = 'Hello World';
     *      $hashedString = hashInput($string);
     * <p>
     * @param {string} $mSubmittedInstream - String variable that was passed
     * @returns {string} $mSubmittedInstream - Returning hashed String variable
     */
    function encryption_hashInput($mSubmittedInstream) {
        $bcrypt_options = [
            'cost' =>12,
        ];
        $mSubmittedInstream = password_hash($mSubmittedInstream,
                PASSWORD_BCRYPT, $bcrypt_options);
        return $mSubmittedInstream;
    }    
        
    /**
     * Generates a hash value (message digest).
     * <p>
     * The hash() function generates a hash value (message digest). Returns a 
     * string containing the calculated message digest as lowercase hexits 
     * unless raw_output is set to true in which case the raw binary 
     * representation of the message digest is returned.
     * <p>
     * Example code:
     *      $string = 'Hello World';
     *      $encryptedString = encryptInput($string);
     * <p>     
     * @param {string} $mSubmittedInstream - String variable that was passed
     * @returns {string} $mSubmittedInstream - Returning hashed String variable
     */
    function encryption_encryptInput($mSubmittedInstream) {
        $mSubmittedInstream = hash("sha512", $mSubmittedInstream, FALSE);
        return $mSubmittedInstream;
    }            
