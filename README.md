# Application to read some github repo

The application show github repo's list and details about issues 

## Recommendation for local adaptation
- need to build a base php image from /infra folder like - docker build -t base:latest - < infra/Dockerfile-base
- base on docker-compose.yml build and then run the application
- need to create next folders /cache and /var/log with full access (chmod 777 /var/log)