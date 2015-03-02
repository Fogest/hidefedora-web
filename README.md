Hide Fedora Website
==================

The website is currently using the Laravel framework. 

##Requirements
1. [Composer](https://getcomposer.org/)
2. [Laravel](http://laravel.com/) (This will be downloaded via Composer, no need to download this!)
3. Apache, PHP, MySQL
4. Google+ API key (Only need this is if you plan to work with report submission)

##Setup/Contributing
1. Create a fork and then clone the repository: `git clone git@github.com:Fogest/hidefedora-web.git`
2. To get all the required elements use composer: `composer install` (you may have to use `composer.phar`)
3. Copy the .env.example file to a new file named .env: `cp .env.example .env` (linux)
4. Customize the `.env` file. You only need to change the `DB_` items, `BASE_URL` to website, and `GOOGLE_PLUS_API_KEY` (if using it)
5. You now need to get the `hidefedora` database. To do this create a database named `hidefedora`. Once you have the database it is simple to get up to date with the schema. Just run `php artisan migrate` and you are set! Laravel handles getting all the tables in!
6. You are now ready to code. Make whatever changes you'd like, commit the changes (`git commit`), then push those changes to your fork (`git push`). From here you can create a pull request on Github. I will then review your pull request and choose whether to accept it (likely will accept). 

##Misc
If you have any issues or questions don't be afraid to contact me or create an "Issue" and I will help you out!
