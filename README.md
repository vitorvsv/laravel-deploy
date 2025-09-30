# Description

Project that provide a complete development docker environment for Laravel. You can use it in your localhost and deploy the artifact with `dockerfiles/artifact.dockerfile` and create containers in a kubernetes cluster.

## Tecnologies used
- Kubernetes
- AWS: ECR, EKS, IAM, S3
- CI/CD: GitHub + GitHub Actions (Build + Deploy)
- Containers: Docker, Docker Compose, Dockerfile
- Others: Laravel + PHP + Open Connect

## How to run locally

- Copy the Laravel code to `/src` folder or create a new Laravel application \
  `docker-compose run --rm composer create-project laravel/laravel .`

- Installing dependencies with (skip it if it was did in the last step) \
  `docker-compose run --rm composer install`

- Run the server with \
  `docker-compose up -d server`

## How to test artifact in your local machine

- Build locally \
  docker build -f dockerfiles/artifact.dockerfile -t vitorvsv/ilaravel-deploy .

- Running container locally \
  docker run -d --name laravel-deploy -p 80:80 vitorvsv/ilaravel-deploy

## Publishing image to ECR

- Added permission for Docker publish in AWS ECR \
  aws ecr get-login-password --region ${{ AWS_REGION }} | docker login --username AWS --password-stdin ${{ ECR_URI }}

- Tagging image according \
  docker tag ilaravel-deploy:latest ${{ ECR_URI }}

- Send image to ECR \
  docker push ${{ ECR_URI }}:${{ TAG_VERSION }}

## How deploy to a kubernetes cluster

- Create a kubernetes cluster in AWS EKS. Remember to use arch x86_64 for worker nodes
- Create an IAM access entries in your cluster (to be able to use kubectl in your local machine, if you want)
- The file `kubernetes/laravel-app.yaml` is apply when you run ci_cd workflow, feel free to change it
  - Remmeber to change the image path for your ECR image URI
- You need create a s3 bucket to be copied env file and sqlite file
  - In a real project it is discouraged using this approach, I'm using here to studies purposes
- In CI step is builded the `dockerfiles/artifact.dockerfile`
- See the `.github/workflows/development-ci-cd` and create secrets and variables as needed.
- You need to connect the AWS with Git Hub Actions using [OpenID connect](https://aws.amazon.com/blogs/security/use-iam-roles-to-connect-github-actions-to-actions-in-aws/)
