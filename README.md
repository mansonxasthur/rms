# RMS (Restaurant Management System)

## Disclaimer:
- I build this project with a package of mine called [Dust]:(https://github.com/Cyberbugz/dust).
- The aim to help me to build a DDD architecture with some helpers based on experience with such architecture.
- I put mind handling redundant procedures I do in my work.
- There's a room for improvements, still a good start point.
- Feel free to ask about it.

<hr>

## Steps to get up and running:
- Prerequisite:
  - Docker installed on your machine.
  - Docker compose cli installed on your machine.
  - Port `8080` should not be busy.
- Change directory to root folder.
- Run `docker compose up -d --build`.
- Once images are built and started run `docker compose exec api bash`
- Inside the container run `composer install && php artisan migrate --seed`
- Seeding in the previous step just for the purpose of testing on Postman if needed.
- Application now running on `http://localhost:8080`.

<hr>

## Notes:
- You can run tests inside the `api` container by executing `php artisan test` command.
- Presumptions:
  - No authentication required for this challenge as per the provided document.
  - Inventory replenishment flow not required for this challenge.
  - Merchant email is a static email we got from the config.
- My approach for this challenge were based solely on requirements, in a real world scenario I'd
  handle the inventory structure differently in a way that gives us traceability, also I'd use fixed threshold
  instead of percentage but that's up to business requirements and planning team.
- Caching is applicable in some parts of this project, I assumed it's not required.
