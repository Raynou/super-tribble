FROM node:22.11.0 AS node
FROM composer
FROM php:8.0-apache
FROM ghcr.io/raynou/moodle:latest

WORKDIR /var/www/html/moodle/auth/faceid

EXPOSE 3000
