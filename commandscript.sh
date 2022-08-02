#!/bin/bash

# get vars from file.  from: https://zwbetz.com/set-environment-variables-in-your-bash-shell-from-a-env-file/
export $(grep -v '^#' coreapp/coreapp/.env | xargs)
export $(grep -v '^#' coredb/.env | xargs)

if [ $# -eq 0 ]
then
    echo "Usage: ./commandscript command [command...]"
    exit
fi

while [ $# -ne 0 ]
do
    COMMAND=$1

    case $COMMAND in
        build-base)
            cd ci4base && docker build -t ci4base .
            ;;
        build)
            docker-compose build 
            ;;
        up)
            docker-compose up -d
            ;;
        destroy)
            docker-compose down
            ;;
        destroy-volumes)
            docker-compose down -v
            ;;
        db-cli)
            docker-compose run db-cli mysql --host $DB_HOST --port 3306 --user $MYSQL_USER --password=$MYSQL_PASSWORD --database $MYSQL_DATABASE
            ;;
        seed-db)
            docker-compose exec coreapp php spark db:seed Auth
            ;;
        coreapp-shell)
            docker-compose exec coreapp /bin/bash
            ;;
        coreapp-copycomposer)
            docker-compose cp coreapp:/var/www/html/ci4app/composer.lock coreapp/coreapp/composer.lock
            docker-compose cp coreapp:/var/www/html/ci4app/composer.json coreapp/coreapp/composer.json
            ;;
        coreapp-reload)
            docker-compose up coreapp --build -d
            ;;
        
                    
    esac
    shift
done

              
