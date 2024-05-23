#!/bin/bash

php artisan serve &
sleep 1
start http://localhost:8000 &
npm run dev
