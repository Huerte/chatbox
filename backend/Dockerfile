FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST
ARG VITE_REVERB_PORT
ARG VITE_REVERB_SCHEME
ENV VITE_REVERB_APP_KEY=$VITE_REVERB_APP_KEY
ENV VITE_REVERB_HOST=$VITE_REVERB_HOST
ENV VITE_REVERB_PORT=$VITE_REVERB_PORT
ENV VITE_REVERB_SCHEME=$VITE_REVERB_SCHEME
RUN npm run build

FROM webdevops/php-nginx:8.4-alpine

ENV WEB_DOCUMENT_ROOT=/app/public
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
 
COPY --from=frontend /app/public/build ./public/build

COPY scripts/entrypoint.d/ /opt/docker/provision/entrypoint.d/

COPY scripts/supervisor/reverb.conf /opt/docker/etc/supervisor.d/reverb.conf
COPY scripts/nginx/vhost.common.d/reverb.conf /opt/docker/etc/nginx/vhost.common.d/reverb.conf

RUN chmod +x /opt/docker/provision/entrypoint.d/*.sh \
 && chown -R application:application /app
