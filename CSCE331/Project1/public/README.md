# 331-docker


## DO NOT CLONE/FORK this 
## Create a new *PRIVATE* repo from the template (use a new repo name)

Yoonsuck Choe

A quick guide to getting started:

1. download this repo:
   * Click on the green [Use this template] on the upper right to create a new repo from this source.
   * DO NOT clone this repo directly! 
1. install docker and run the daemon 
   * method may vary, depending on OS
   * see https://docs.docker.com/get-docker/
1. install docker-compose 
   * method may vary, depending on OS
   * see https://docs.docker.com/compose/install/
1. in the 315-docker directory, run:
   ```
   Linux & MacOS
   sudo docker-compose up -d

   Windows
   docker-compose up -d
   ```
1. now you can access the web server on http://localhost:5555
1. Note: the web site root directory is 331-docker/public/ (the top-level directory may be different depending on how you named it when you created a new repo from the template)
   * files in the above directory will be visible at http://localhost:5555
   * edits in your public/ directory in your host filesystem will immediately become visible on the web site.
1. to stop and remove the instances from the docker runtime, run (in the top directory for 315-docker):
   ```
   Linux & MacOS
   sudo docker-compose stop

   Windows
   docker-compose stop
   ```
    
Credits: https://phpdocker.io/generator was used to seed this. Here, you can created a custom yml file with different php versions and options.

