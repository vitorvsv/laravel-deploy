# Laravel Deploy Project

A complete Docker-based development environment for Laravel applications with automated CI/CD pipeline for deployment to AWS EKS. This project provides a production-ready setup for containerized Laravel applications with Kubernetes orchestration.

## Project Purpose

This project demonstrates a full-stack deployment solution for Laravel applications, featuring:

- Local development environment with Docker Compose
- Automated CI/CD pipeline using GitHub Actions (Tests, Build and Deploy)
- Production deployment to AWS EKS (Elastic Kubernetes Service)
- Container image management with AWS ECR
- Infrastructure as Code with Kubernetes manifests

## Technologies Used

### Core Technologies

- **Laravel** - PHP web framework
- **PHP** - Server-side programming language
- **Docker** - Containerization platform
- **Docker Compose** - Multi-container orchestration
- **Kubernetes** - Container orchestration
- **Nginx** - Web server and reverse proxy

### AWS Services

- **EKS (Elastic Kubernetes Service)** - Managed Kubernetes clusters
- **ECR (Elastic Container Registry)** - Container image storage
- **S3** - Object storage for configuration files
- **IAM** - Identity and access management

### CI/CD & DevOps

- **GitHub Actions** - Continuous integration and deployment
- **OpenID Connect** - Secure AWS authentication
- **Kubernetes YAML** - Infrastructure as Code

## Local Development Setup

### Prerequisites

- Docker and Docker Compose installed
- Git (for cloning the repository)

### Step 1: Clone and Setup

```bash
git clone <repository-url>
cd laravel-deploy
```

### Step 2: Create Laravel Application

```bash
# Create a new Laravel application in the src directory
docker-compose run --rm composer create-project laravel/laravel .
```

### Step 3: Clone and edit .env

```bash
# Edit with your configurations
cp src/.env.example src/.env
```

### Step 4: Install Dependencies

```bash
# Install PHP dependencies (skip if done in previous step)
docker-compose run --rm composer install
```

### Step 5: Start Development Environment

```bash
# Start all services in detached mode
docker-compose up -d server
```

### Step 6: Access Application

- Open your browser and navigate to `http://localhost`
- The Laravel application should be running

## Testing Production Artifact Locally

### Build Production Image

```bash
# Build the production-ready Docker image
docker build -f dockerfiles/artifact.dockerfile -t laravel-deploy:latest .
```

### Run Production Container

```bash
# Run the production container locally
docker run -d --name laravel-deploy -p 80:80 laravel-deploy:latest
```

### Verify Deployment

- Access the application at `http://localhost`
- Check container logs: `docker logs laravel-deploy`

## AWS EKS Deployment

### Prerequisites

- AWS CLI configured with appropriate permissions
- kubectl installed and configured
- EKS cluster created with x86_64 worker nodes
- ECR repository created

### Step 1: Configure AWS Authentication

```bash
# Configure AWS CLI (if not already done)
aws configure
```

### Step 2: Create EKS Cluster

```bash
# Create EKS cluster (replace with your preferred settings)
eksctl create cluster --name CLUSTER_NAME --region CLUSTER_REGION --nodegroup-name workers --node-type t3.medium --nodes 2
```

### Step 3: Configure kubectl

```bash
# Update kubeconfig for your cluster
aws eks update-kubeconfig --region CLUSTER_REGION --name CLUSTER_NAME
```

### Step 4: Set Up GitHub Actions Secrets

Configure the following secrets in your GitHub repository:

- `AWS_REGION` - Your AWS region (e.g., us-west-2)
- `ECR_URI` - Your ECR repository URI
- `EKS_CLUSTER_NAME` - Your EKS cluster name
- `S3_BUCKET_NAME` - S3 bucket for configuration files

### Step 5: Configure OpenID Connect

1. Create an IAM OIDC identity provider for GitHub Actions
2. Create an IAM role with trust policy for GitHub Actions
3. Attach necessary permissions (ECR, EKS, S3)

### Step 6: Update Kubernetes Manifest

Edit `kubernetes/laravel-app.yaml`:

- Update the image URI to match your ECR repository
- Modify resource limits and requests as needed
- Configure environment variables

### Step 7: Deploy via GitHub Actions

1. Push your code to the main branch
2. Trigger GitHub Actions on dispatch for:
   - Configure the application
   - Build the Docker image
   - Push to ECR
   - Deploy to EKS

## Manual Deployment Commands

### Build and Push to ECR

```bash
# Authenticate with ECR
aws ecr get-login-password --region $AWS_REGION | docker login --username AWS --password-stdin $ECR_URI

# Build and tag image
docker build -f dockerfiles/artifact.dockerfile -t laravel-deploy:latest .
docker tag laravel-deploy:latest $ECR_URI:latest

# Push to ECR
docker push $ECR_URI:latest
```

### Deploy to EKS

```bash
# Apply Kubernetes manifest
kubectl apply -f kubernetes/laravel-app.yaml

# Check deployment status
kubectl get pods
kubectl get services
```

## Project Structure

```
├── dockerfiles/        # Docker configuration files
├── kubernetes/         # Kubernetes manifests
├── nginx/              # Nginx configuration
├── src/                # Laravel application source code
├── .github/workflows/  # GitHub Actions CI/CD pipelines
└── docker-compose.yml  # Local development environment
```

## Important Notes

- **S3 Configuration**: This project uses S3 for storing environment files and SQLite database. In production, consider using RDS for database and proper secret management.
- **Security**: Ensure proper IAM roles and policies are configured for least privilege access.
- **Monitoring**: Consider adding monitoring and logging solutions for production deployments.
- **Scaling**: The Kubernetes manifest can be modified to support horizontal pod autoscaling.

## Troubleshooting

### Common Issues

1. **Docker build fails**: Check Dockerfile syntax and dependencies
2. **EKS connection issues**: Verify AWS credentials and cluster configuration
3. **Image pull errors**: Ensure ECR permissions are correctly configured
4. **Pod startup failures**: Check logs with `kubectl logs <pod-name>`

### Useful Commands

```bash
# Check cluster status
kubectl cluster-info

# View pod logs
kubectl logs -f deployment/laravel-app

# Scale deployment
kubectl scale deployment laravel-app --replicas=3

# Delete deployment
kubectl delete -f kubernetes/laravel-app.yaml
```
