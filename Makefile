build:
	docker build --no-cache -t nginx .
 
run:
	docker run -d -p 4001:80 --name zazu-dash ubuntu

clean:
	docker stop zazu-dash && docker rm zazu-dash -v