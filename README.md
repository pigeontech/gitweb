GitWeb
=========

GitWeb will create a repo that runs parallel to your website. It keeps the Git files out of the web root.  After you push an update to the repo, it will use a post-receive to update your actual web root.  This means you can push updates to your actual live website just as easily as you push your latest updates to GitHub.

It currently is configured for Dreamhost. You may need to change some things, such as the paths that go into the post-receive file. Dreamhost's works like `/home/username/websitename`, so that's how I have it.

It will look to see which directories exist in the directory that you put `gitweb.php`, assuming they are your websites, and ask you to choose one.  That's all the user interaction there is.  Behind the scenes, it's creating a hidden `.git` folder, and creating a `websitename.git` repo inside that. The script then automatically configures `post-receive` so that it will update your actual `websitename` folder.

Usage
-----------

 - Upload `gitweb.php` to your server in the same level as your website folders.  SSH into your webserver. `ssh user@domain.com`.
 - Run the script by typing `php gitweb.php`. It will list folders.
 - Type a number for the folder you want. The script creates a repo of the same name with ".git" added on inside a hidden directly named ".git". So, it'll be like `/home/username/.git/websitename.git`.  You can `cd` into that directory and do `ls` to make sure it worked.  You can also `cat hooks/post-receive` to make sure it got created and has stuff in it, to include the path to your actual website folder.
 - You're done on the server side. Type `exit`.

Now back on your local repo of the website, you need to add a remote to the repo you just created.
```
git remote add web ssh://user@domain.com/home/user/myrepos/websitename.git
```
Then type:
```
git push web +master:refs/heads/master
```
And from now on just type `git push web` to update the site.

More Info
-------
I wrote a [blog post](http://www.scottpigeon.com/setting-up-ssh-and-git-on-dreamhost/) about doing this the manual way, which is what motivated me to make this script to automate things. The blog post is more thorough if you want to understand how things work so that you can better customize this script to your needs.
