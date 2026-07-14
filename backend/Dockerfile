# Stage 1: build frontend assets (Vite + Tailwind)
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP 8.4 + Nginx runtime
FROM webdevops/php-nginx:8.4-alpine

ENV WEB_DOCUMENT_ROOT=/app/public
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
 
COPY --from=frontend /app/public/build ./public/build

# Entrypoint hook — runs on every container start, before nginx/php-fpm boots
COPY scripts/entrypoint.d/ /opt/docker/provision/entrypoint.d/
RUN chmod +x /opt/docker/provision/entrypoint.d/*.sh \
 && chown -R application:application /app
