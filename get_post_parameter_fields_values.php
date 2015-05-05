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
 * Creates string from passed array using array's keys or values.
 * <p>
 * Allows for dynamic creation of PDO prepared statements. Uses passed variable
 * $mFieldOrValue to determine if $mOutput should contain the values or keys
 * from passed variable $mPostParameters. $mOutput is formatted for use in 
 * prepared SQL statements.
 * <p>
 * Examples of $mOutput:
 *      1) "field1, field2, field3"
 *      2) "value1, value2"
 * <p>
 * Example Code:
 *     //prepare SQL statement
 *     $mFields = getPostParameterFieldsValues("field", $mPostParameter);
 *     $mBindFields = getPostParameterBindFields($mPostParameter);
 *     $mStatement = $mConnection->prepare("INSERT INTO $mTableName ("
 *             . "$mFields) VALUES ($mBindFields)");
 *     //bind parameters
 *     $mKey = $mValue = null;
 *     foreach($mPostParameter as $mKey => $mValue) {
 *         $mStatement->bindValue(':' . $mKey, $mValue);
 *     }
 *     $mStatement->execute();
 * <p>
 * @param string $mFieldOrValue String passed variable that determines $output.
 * @param array $mPostParameters Array passed that contains keys and values.
 * @return string $mOutput String formatted for use in prepared SQL statements.
 */
function getPostParameterFieldsValues($mFieldOrValue, $mPostParameters) {
    //declare and set variables
    $mKey = $mValue = null;
    $mOutput = "";
    $mLoopIteration = 0;
    $mArrayLength = count($mPostParameters);    

    if($mFieldOrValue === "field" || $mFieldOrValue === "Field" 
            || $mFieldOrValue === "FIELD") {
        foreach($mPostParameters as $mKey => $mValue) {
            $mOutput .= $mKey;
            //Is this the last iteration of the foreach loop?
            if($mLoopIteration !== $mArrayLength - 1) {
                $mOutput .= ', ';
                $mLoopIteration++;
            }
        }
    } else if($mFieldOrValue === "value" || $mFieldOrValue === "Value" 
            || $mFieldOrValue === "VALUE"){
        foreach($mPostParameters as $mValue) {
            $mOutput .= $mValue;
            //Is this the last iteration of the foreach loop?
            if($mLoopIteration !== $mArrayLength - 1) {
                $mOutput .= ',';
                $mLoopIteration++;
            }
        }
    }

    //unset() destroys the specified variables.
    unset($mPostParameters);
    unset($mFieldOrValue);
    unset($mKey);
    unset($mValue);
    unset($mLoopIteration);
    unset($mArrayLength);

    return $mOutput;
}

/**
 * Creates string from passed array using array's keys.
 * <p>
 * Allows for dynamic creation of PDO prepared statements. Sends keys from
 * passed variable $mPostParameters to $mOutput. $mOutput is formatted for use 
 * in prepared SQL statements.
 * <p>
 * Example of $mOutput:
 *      1) ":field1, :field2, :field3"
 * <p> 
 * Example Code:
 *     //prepare SQL statement
 *     $mFields = getPostParameterFieldsValues("field", $mPostParameter);
 *     $mBindFields = getPostParameterBindFields($mPostParameter);
 *     $mStatement = $mConnection->prepare("INSERT INTO $mTableName ("
 *             . "$mFields) VALUES ($mBindFields)");
 *     //bind parameters
 *     $mKey = $mValue = null;
 *     foreach($mPostParameter as $mKey => $mValue) {
 *         $mStatement->bindValue(':' . $mKey, $mValue);
 *     }
 *     $mStatement->execute();
 * <p>     
 * @param array $mPostParameters Array passed that contains keys and values.
 * @return string $mOutput String formatted for use in prepared SQL statements.
 */
function getPostParameterBindFields($mPostParameters) {
    //declare and set variables
    $mKey = $mValue = null;
    $mOutput = "";
    $mLoopIteration = 0;
    $mArrayLength = count($mPostParameters);
    
    //$mValue is only present in code to get $mKey
    //could also use array_keys($mPostParameters to return the keys
    foreach($mPostParameters as $mKey => $mValue) {
        $mOutput .= ':' . $mKey;
        //Is this the last iteration of the foreach loop?
        if($mLoopIteration !== $mArrayLength - 1) {
            $mOutput .= ', ';
                $mLoopIteration++;
            }
        }
    
    //unset() destroys the specified variables.
    unset($mPostParameters);
    unset($mKey);
    unset($mValue);
    unset($mLoopIteration);
    unset($mArrayLength);
    
    return $mOutput;
}
