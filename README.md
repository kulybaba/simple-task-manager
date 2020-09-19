**SIMPLE TASK MANAGER**

**Setup:**
1. git clone https://github.com/petrokulybaba/simple-task-manager.git
2. git checkout dev
3. docker-compose up -d
4. docker-compose exec app sh -c 'make setup'
5. docker-compose exec app sh -c 'make start'
6. Follow http://127.0.0.1:8000

**Stop server:** docker-compose exec app sh -c 'make stop'

**Tests:** docker-compose exec app sh -c 'make test'
