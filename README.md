# These instructions should work

## Setting up environment
Install Docker for your OS: [Mac](https://docs.docker.com/docker-for-mac/install/) or
[Windows with WSL](https://docs.docker.com/docker-for-windows/wsl/). Or for those comfortable with Linux, there is documentation on the Docker website as well!

If you are using Windows WSL, you'll want to `cd ~` to use the Linux home directory rather than the Windows bindings. To access this in VSCode, there is the [Remote WSL](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl)
plugin.

Clone the project into a suitable place on your computer, you'll need `git` for this; for Windows you want to be doing this in the WSL Subsystem.

## Cloning Project
Once you have cloned this project, enter the root directory of the project.
    
    cd mood-feedback-system

Copy the contents of `.env.example` to a new file called `.env`.

    cp .env.example .env

Run the following command to install dependencies that aren't shared on GitHub

    docker run --rm -v $(pwd):/opt -w /opt laravelsail/php80-composer:latest composer install
## Starting System
Make an alias to the `sail` command; this should be in your `~/.bashrc` or `~/.zshrc` configuration file.

    alias sail='bash vendor/bin/sail'

Then to start the system:

    sail up -d

To make the database according to what has been written and generate some test data:

    sail artisan migrate:fresh --seed

To access the website, visit: 
    
    localhost:8888 

You'll probably be told an `APP_KEY` does not exist, press the button to generate one.

If you want to stop the system running at any point:

    sail down
