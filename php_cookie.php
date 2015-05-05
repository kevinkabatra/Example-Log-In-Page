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

    /**
     * 
     * @param type $mCookieName
     */
    function php_cookie_deleteCookie($mCookieName) {
        setcookie($mCookieName, '', time() - 1, '/',
                'kevinkabatra.ignorelist.com', FALSE, TRUE);
        return 'php_cookie.php:deleteCookie';
    }
    
    /**
     * 
     * @param type $mCookieName
     * @return boolean
     */
    function php_cookie_getCookie($mCookieName) {
        if(empty(filter_input(INPUT_COOKIE, $mCookieName,
                FILTER_SANITIZE_FULL_SPECIAL_CHARS))) {
            return 'php_cookie.php:' . $mCookieName . ':empty'; 
        } else {
            return 'php_cookie.php:' . $mCookieName . ':' 
                    . filter_input(INPUT_COOKIE, $mCookieName,
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }

    /**
     * 
     * @param type $mCookieName 
     * @param type $mCookieValue
     */
    function php_cookie_setCookie($mCookieName, $mCookieValue) {
        setcookie($mCookieName, $mCookieValue, time() + 86400 * 30,
                '/', 'kevinkabatra.ignorelist.com', FALSE, TRUE);
        return 'php_cookie.php:setCookie';
    }
