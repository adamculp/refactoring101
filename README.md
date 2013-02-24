refactoring101
==============

The code here, represented as steps, is the progression of multiple refactorings on a legacy codebase.  The files were created to go along with a "Refactoring 101" talk, as a PHP version of the Java code shown in Martin Fowler's book "Refactoring". http://www.refactoring.com/

The code is based on the PHP 5+ object model, so older versions of PHP will not work.

NOTE: While this series of files/steps highlights common refactorings, it is not representative of "great code" or a finished product.  But is in fact a "work in progress".  By that I mean there is still more refactoring/work to be done which is beyond the scope of this project.  These files are meant for training only, and not meant to be a completed project.

Also note the whitespace is not how I would normally handle it.  However, since this code will be used in presentation slides for a talk I have handled whitespace differntly so code blocks are compact, thus enabling code to be shown larger on the slides.

Files/Steps
-----------

* 001-begin.php
* 002-extract_method.php
* 003-rename_variables.php
* 004-rename_method.php
* 005-move_method.php
* 006-replace_temp_with_query.php
* 007-extract_method.php
* 008-replace_temp_with_query.php
* 009-replace_temp_with_query.php
* 010-multiple_statement_types.php
* 011-move_method.php
* 012-move_method.php
* 013-replace_type_code_with_state.php
* 014-move_method.php
* 015-repl_conditional_w_polymorphism.php
* 016-repl_conditional_w_polymorphism.php

How to use these files:
-----------------------

These files are best used by doing a diff-like side-by-side view of the previous and next versions.  I have included a doc block in the head of each file to explains the changes, and in some cases why they were done. (Which also coincides with the book mentioned earlier.) However, it is best to look at the code itself to see what was done with each iteration.

Enjoy!

