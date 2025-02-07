# My pet project "core"
## install
1. clone repo:
   ```bash
   git clone https://github.com/sardykoivan/core.git
   ```
2. build & run application on docker
   ``` bash
   docker compose build
   docker compose up -d
   ```
3. install composer deps
   ``` bash
   docker compose exec php bash
   ```
## using
### application runs on port 8092
   ``` bash
   curl -X GET --location "http://localhost:8092/api/status"
   ```
   
### run stat analysis and tests (inside php container)
   ``` bash
   composer phpcs
   composer phpstan
   composer psalm
   composer test
   ```
