Refactoring101
==============

The code here, represented as steps, is the progression of multiple refactorings on a legacy codebase.  The files were created to go along with a "Refactoring 101" talk (outdated slides found at [http://www.slideshare.net/adamculp/refactoring-23666462](http://www.slideshare.net/adamculp/refactoring-23666462)), as a PHP version of the Java code shown in Martin Fowler's book "Refactoring". [https://www.refactoring.com/](https://www.refactoring.com/)

More recently, I created a collection of refactoring videos on my Beachcasts YouTube Channel. These can be found at [https://www.youtube.com/playlist?list=PL6_nF0awZMoNH0_9n_Qjq925IB5RGiB2g](https://www.youtube.com/playlist?list=PL6_nF0awZMoNH0_9n_Qjq925IB5RGiB2g)

The code is based on the PHP 7.4 which includes types, so older versions of PHP may not work as expected. If you notice errors preventing usage, please let me know.

IMPORTANT:

* In an IDE, ignore the warnings of duplicated code fragments. Those are is expected.
* While this series of files/steps highlights common refactorings, it is not representative of "great code" or a finished product.  But is in fact a "work in progress".  By that I mean there is still more refactoring/work to be done which is beyond the scope of this project.  These files are meant for training only, and not meant to be a completed project.
* For brevity of this training material, some common code conventions, and/or PHP conventions were not followed. Such as one class per file, and proper namespaces.
* Also for brevity, this code should not be thought of as the optimal way to create logic. After any refactor, performance should also be taken into account.

Also note the whitespace is not how I would normally handle it.  However, since this code will be used in presentation slides for a talk I have handled whitespace differently so code blocks are compact, thus enabling code to be shown larger on the slides.

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

These files are best used by doing a side-by-side diff-like view of the previous and next versions.  I have included a doc block in the head of each file to explains the changes, and in some cases why they were done. (Which also coincides with the book mentioned earlier.) However, it is best to look at the code itself to see what was done with each iteration.

Enjoy!
