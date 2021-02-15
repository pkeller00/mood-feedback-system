# These instructions don't work yet :-(

## How to run this project
Once you have cloned this project, enter the root directory of the project:
    
    cd mood-feedback-system

Copy the contents of `.env.example` to a new file called `.env`

You will need to install [Docker](https://www.docker.com/get-started) to run this project. Once Docker is installed, run:
    
    ./vendor/bin/sail up
    sail artisan key:generate

At this stage,

    sail artisan migrate:fresh --seed

To access the website, visit 
    
    localhost:8888 
