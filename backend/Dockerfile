# Stage 1: build frontend assets (Vite + Tailwind) using a proper Node 20 image
FROM node:20-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: the actual PHP app image — no Node/npm shipped in the final image
FROM richarvey/nginx-php-fpm:latest

COPY . .

# Bring in the compiled assets from the frontend stage
COPY --from=frontend /app/public/build ./public/build

ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
