# facebook-groupper
PHP app for working with facebook groups. 

Make post for groups
Watch post from group in timeline format


##How it works
Hello boys and girls, you wanna make posts to fb groups, easy.

First: 
Copy src/configs/sample-private.php to src/configs/my.private.php, nd fill it

Second: 
Open src/web/login.php in browser and add facebook rights for access

Third:
Fill tablse in db (import db/sa_fb_groupper first) - groups, cron and posts

Fourth:
Add src/con/crop.php and sync-group-feeds to cron

Thats all follks!

Whole your posts from cron table will be added to facebook groups, and whole groups 
feed will be added to feed table.
 