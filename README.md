# PHPUnit for Symfony Apps

It makes unit tests trivial for symfony apps. It just set the project's path, see the logs, celebrate and be happy.

## Use it
* Download or clone the repo
* Go to the folder of the project and run: ``` docker build -t <container_name> .``` (wait it finishes)
* ``` docker run -d --name <friendly_name> -v <project_folder>:/var/www/html <container_name> ```
* ``` docker logs -f <friendly_name> ```
* You just need do this the first time, them always you shoud run: ``` docker start <friendly_name> ``` and see the logs

## TODO
* Make more tasks (maybe run the tests once and them exit)
