<?php
//This comment is related with the first function

print(fn1('arg1', 'arg2', 3));
fn2($var);
fn3(fn4('arg4'), 'arg5', fn5(6, 7.5));
fn6(['arr']);
fn7(CONSTANT_1);
// fn_8();
/* ALLOW: This is a comment to fn9 */
return fn9(ARG_8);

/* Comment to fn10 */ fn10();

//Related comment 1
fn11(/* ALLOW: Related comment 2 */ 'arg9', 'arg10' /* No related comment 3 */);

/*
    Related comment 
    number one
*/
echo fn12(
    /* Related comment 2 */
    'arg11',
    /* ALLOW: Related comment 3 */
    'arg12'
    /* No Related comment 4 */
);

fn13("Translatable string","",["context"=>"Context string", 'foo']);